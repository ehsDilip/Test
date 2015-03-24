<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 5.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/PickupService_v9.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Create Freight Pickup Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'disp', 
	'Major' => 9, 
	'Intermediate' => 0, 
	'Minor' => 0
);
$request['AssociatedAccountNumber']=array('Type' => 'FEDEX_FREIGHT', 
		'AccountNumber' => getProperty('freightacount'));
$request['OriginDetail'] = array(
	'PickupLocation' => array(
		'Contact' => array(
			'PersonName' => 'Contact Name',
          	'CompanyName' => 'Company Name',
        	'PhoneNumber' => '1234567890'
        ),
      	'Address' => array(
      		'StreetLines' => array('1202 Chalet Ln', 'Do Not Delete - Test Account'),
          	'City' => 'Harrison',
          	'StateOrProvinceCode' => 'AR',
         	'PostalCode' => '72601',
           	'CountryCode' => 'US')
       	),
   	'PackageLocation' => 'FRONT', // valid values NONE, FRONT, REAR and SIDE
    'BuildingPartCode' => 'BUILDING', // valid values APARTMENT, BUILDING, DEPARTMENT, SUITE, FLOOR and ROOM
    'BuildingPartDescription' => 'Front Desk',
    'ReadyTimestamp' => getProperty('pickuptimestamp'), // Replace with your ready date time
    'CompanyCloseTime' => '20:00:00'
);
$request['FreightPickupDetail'] = array(
	'Payment' => 'SENDER',
	'Role' => 'SHIPPER',
	'LineItems' => array(
		'Service' => 'FEDEX_FREIGHT_PRIORITY',
		'Destination' => array(
			'Streetlines' => array('123 Do Not Ship Lane'),
			'City' => 'Collierville',
			'StateOrProvinceCode' => 'TN',
			'PostalCode' => '38017',
			'CountryCode' => 'US',
			'Residential' => false),
		'Packaging' => 'PALLET',
		'Pieces' => '1',
		'Weight' => array('Units' => 'LB', 'Value' => '200'),
		'TotalHandlingUnits' => '1',
		'PurchaseOrderNumber' => 'PO1234',
		'Description' => 'Freight Line Item')
);
$request['PackageCount'] = '1';
$request['TotalWeight'] = array(
	'Units' => 'LB', // valid values LB and KG
	'Value' => '200'
); 
$request['CarrierCode'] = 'FXFR'; // valid values FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
//$request['OversizePackageCount'] = '1';
$request['CourierRemarks'] = 'This is a test.  Do not pickup';



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->createPickup($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        if(isset($response -> PickupConfirmationNumber)){
        	echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
        }
		if(isset($response -> Location)){
			echo 'Location: '.$response -> Location .Newline;
		}
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
    printSuccess($client, $response);              
}
?>