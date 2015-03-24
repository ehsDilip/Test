<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0

require_once('../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../wsdl/ShipService_v15.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Validate Shipping Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'ship', 
	'Major' => '15', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['RequestedShipment'] = array(
	'ShipTimestamp' => date('c'),
   	'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
   	'ServiceType' => 'PRIORITY_OVERNIGHT', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
  	'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
  	'TotalWeight' => array(
  		'Value' => 50.0, 
  		'Units' => 'LB' // valid values LB and KG
  	), 
   	'Shipper' => getProperty('shipper'),
	'Recipient' => getProperty('recipient'),
	'ShippingChargesPayment' => getProperty('shippingchargespayment'),
 	'SpecialServicesRequested' => array(
 		'SpecialServiceTypes' => array('COD'),
       	'CodDetail' => array(
       		'CodCollectionAmount' => array(
       			'Currency' => 'USD', 
       			'Amount' => 150
       		),
          	'CollectionType' => 'ANY'
		)
	), // ANY, GUARANTEED_FUNDS
	'LabelSpecification' => array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75'
	), 
  	'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
   	'PackageCount' => 1,
 	'PackageDetail' => 'INDIVIDUAL_PACKAGES',
  	'RequestedPackageLineItems' => array(
     	'0' => array(
       		'SequenceNumber' => '1',
           	'Weight' => array(
           		'Value' => 50.0, 
               	'Units' => 'LB'// valid values LB and KG
           	)
       	)
	)
); 



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client->validateShipment($request);  // FedEx web service invocation

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
        echo 'Validate Ship transaction passed.'.Newline;
        
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    }

    writeToLog($client);    // Write to log file
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>