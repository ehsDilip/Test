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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Create Pickup Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'disp', 
	'Major' => 9, 
	'Intermediate' => 0, 
	'Minor' => 0
);
$request['OriginDetail'] = array(
	'PickupLocation' => array(
		'Contact' => array(
			'PersonName' => 'Contact Name',
          	'CompanyName' => 'Company Name',
        	'PhoneNumber' => '1234567890'
        ),
      	'Address' => array(
      		'StreetLines' => array('Address Line 1'),
          	'City' => 'Foster City',
          	'StateOrProvinceCode' => 'CA',
         	'PostalCode' => '94404',
           	'CountryCode' => 'US')
       	),
   	'PackageLocation' => 'FRONT', // valid values NONE, FRONT, REAR and SIDE
    'BuildingPartCode' => 'SUITE', // valid values APARTMENT, BUILDING, DEPARTMENT, SUITE, FLOOR and ROOM
    'BuildingPartDescription' => '3B',
    'ReadyTimestamp' => getProperty('pickuptimestamp'), // Replace with your ready date time
    'CompanyCloseTime' => '20:00:00'
);
$request['PackageCount'] = '1';
$request['TotalWeight'] = array(
	'Value' => '1.0', 
	'Units' => 'LB' // valid values LB and KG
); 
$request['CarrierCode'] = 'FDXE'; // valid values FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
//$request['OversizePackageCount'] = '1';
$request['CourierRemarks'] = 'This is a test.  Do not pickup';



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->createPickup($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
        echo 'Location: '.$response -> Location .Newline;
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