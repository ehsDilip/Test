<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 1.0.1

require_once('../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../wsdl/ReturnTagService_v1.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** ExpressTagAvailability Request v1 using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'wsi', 
	'Major' => '1', 
	'Intermediate' => '1', 
	'Minor' => '0'
);
$request['ReadyDateTime'] = getProperty('readydate');  // Replace with your ready date time
$request['Service'] = 'PRIORITY_OVERNIGHT';
$request['Packaging'] = 'FEDEX_ENVELOPE';
$request['OriginAddress'] = array(
	'StreetLines' => array('13450 Farmcrest Ct'),
	'City' => 'Collierville',
	'StateOrProvinceCode' => 'TN',
	'PostalCode' => '38017',
	'CountryCode' => 'US',
	'Residential' => 1
);



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->getExpressTagAvailability($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        echo 'The following Tag options are available.'. Newline;
        echo '<table border="1">';
        if(array_key_exists("AccessTime", $response)) printRow($response->AccessTime, "Access Time");
		if(array_key_exists("ReadyTime", $response)) printRow($response->ReadyTime, "Ready Time");
		if(array_key_exists("Availability", $response)) printRow($response->Availability, "Availability");
		echo '</table><br/>';
		printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}



function printRow($var, $description){
	echo '<tr><td>' . $description . '</td><td>' . $var . '</td></tr>';
}
?>