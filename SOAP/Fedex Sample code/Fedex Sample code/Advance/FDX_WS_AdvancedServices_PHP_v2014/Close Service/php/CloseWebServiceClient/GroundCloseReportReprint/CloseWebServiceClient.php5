<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 2.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/CloseService_v3.wsdl";

define('GROUND_REPORT', 'groundreport.txt');

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' => array(
		'Key' => getProperty('key'),  // Replace 'XXX' and 'YYY' with FedEx provided credentials 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), // Replace 'XXX' with your account and meter number
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Reprint Close Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'clos', 
	'Major' => '3', 
	'Intermediate' => '1', 
	'Minor' => '0'
);
$request['ReportDate'] = date(getProperty('closedate'));
//$request['TrackingNumber'] = getProperty('trackingnumber'); // Replace 'XXX' with your Ground Tracking Number
$request['CloseReportType'] = 'MANIFEST'; // valid values are COD, HAZMAT, MANIFEST



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->groundCloseReportsReprint($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        printSuccess($client, $response);
		if(array_key_exists("Manifests", $response) && is_array ($response->Manifests)){ 
			$fp = fopen(GROUND_REPORT, 'wb');
			foreach($response->Manifests as $manifest){
				fwrite($fp, $manifest->File); //Create manifest report
				fwrite($fp, "\n\n\n");
			} 
			fclose($fp);
			echo '<a href="./'.GROUND_REPORT.'">'.GROUND_REPORT.'</a> was generated.'.Newline;
		}elseif(!empty($response->Manifests)){
			$fp = fopen(GROUND_REPORT, 'wb');
			fwrite($fp, $response->Manifests->File); //Create manifest report
			echo '<a href="./'.GROUND_REPORT.'">'.GROUND_REPORT.'</a> was generated.'.Newline;
			fclose($fp);
		}else{
			echo 'No manifest file was returned.'.Newline;
		}
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>