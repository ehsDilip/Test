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
		'Key' => getProperty('key'), 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), 
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Close Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'clos', 
	'Major' => '3', 
	'Intermediate' => '1', 
	'Minor' => '0'
);
$request['TimeUpToWhichShipmentsAreToBeClosed'] = date('c');  



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client->groundClose($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        printSuccess($client, $response);
		if(array_key_exists("Manifest", $response)){
			$fp = fopen(GROUND_REPORT, 'wb');
			fwrite($fp, $response->Manifest->File); //Create manifest report
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