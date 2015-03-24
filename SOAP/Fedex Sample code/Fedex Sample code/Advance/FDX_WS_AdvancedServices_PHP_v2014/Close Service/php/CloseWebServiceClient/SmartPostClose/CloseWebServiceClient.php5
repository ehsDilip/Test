<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 2.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/CloseService_v3.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** SmartPost Close Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'clos', 
	'Major' => '3', 
	'Intermediate' => '1', 
	'Minor' => '0'
);
$request['HubId'] = getProperty('hubid');  
$request['CustomerManifestId'] = 'manifest-Id';  
$request['DestinationCountryCode'] = 'US';  
$request['PickUpCarrier'] = 'FXSP';  



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->smartPostClose($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>