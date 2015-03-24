<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/ShipService_v15.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Cancel Express Tag Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'ship', 
	'Major' => '15', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['DispatchLocationId'] = getProperty('dispatchlocationid');  // Replace 'XXX' with your dispatch location id (in ExpressTag response)
$request['DispatchDate'] = getProperty('dispatchdate');  // Replace with your ready date (in ProcessTag request) 
$request['Payment'] = array(
	'PaymentType' => 'SENDER',
  	'Payor' => array(
  		'ResponsibleParty' => array(
  			'AccountNumber' => getProperty('billaccount'),
  			'Contact' => null,
      		'CountryCode' => 'US'
      	)
    )
);
$request['ConfirmationNumber'] = getProperty('dispatchconfirmationnumber'); // Replace 'XXX' with your confirmation number (in ExpressTag response)



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client -> deleteTag($request);

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