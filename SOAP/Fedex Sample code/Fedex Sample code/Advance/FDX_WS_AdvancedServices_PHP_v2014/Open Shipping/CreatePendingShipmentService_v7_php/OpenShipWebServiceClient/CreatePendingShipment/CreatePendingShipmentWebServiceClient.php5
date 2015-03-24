<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 9.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/OpenShipService_v7.wsdl";

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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Pending Shipment Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'ship', 
	'Major' => '7', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['Actions']=array('TRANSFER');
$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = date('c');
$request['RequestedShipment']['ServiceType'] = 'PRIORITY_OVERNIGHT'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
$request['RequestedShipment']['Shipper'] = getProperty('shipper');
$request['RequestedShipment']['Recipient'] = getProperty('recipient');										   
$request['RequestedShipment']['ShippingChargesPayment'] = getProperty('shippingchargespayment');
$request['RequestedShipment']['SpecialServicesRequested'] = array(
	'SpecialServiceTypes' => array ('RETURN_SHIPMENT', 'PENDING_SHIPMENT'),
	'ReturnShipmentDetail' => array(
		'ReturnType' => 'PENDING',
		'ReturnEMailDetail' => array(
			'MerchantPhoneNumber' => '901 999 9999', 
			'AllowedSpecialServices' => 'SATURDAY_DELIVERY'
		)
	),
	'PendingShipmentDetail' => array(
		'Type' => 'EMAIL', 
		'ExpirationDate' => getProperty('expirationdate'),
		'EmailLabelDetail' => array(
			'Message' => "Email message",
			'Recipients' => array(array(
				'EmailAddress' => 'recipeint@company.com',
				'Role' => 'SHIPMENT_COMPLETOR'
			)),
		)
	)
);                                                                                                                                 
$request['RequestedShipment']['LabelSpecification'] = array(
	'LabelFormatType' => 'COMMON2D',
	'ImageType' => 'PDF'
);
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['RequestedPackageLineItems'] = array(
	'0' => array(
		'SequenceNumber' => '1',
		'InsuredValue' => array(
			'Amount' => 20.0,
			'Currency' => 'USD'
		),
		'Weight' => array(
			'Value' => 2.0,
			'Units' => 'LB'
		),
		'Dimensions' => array(
			'Length' => 25,
			'Width' => 10,
			'Height' => 10,
			'Units' => 'IN'
		),
		'ItemDescription' => 'College Transcripts',
		'CustomerReferences' => array(
			'CustomerReferenceType' => 'CUSTOMER_REFERENCE',
			'Value' => 'Undergraduate application'
		)
	)
);



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->createPendingShipment($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
    	echo 'Role: '.$response -> CompletedShipmentDetail -> AccessDetail -> AccessorDetails -> Role.Newline;
    	echo 'Url: '.$response -> CompletedShipmentDetail -> AccessDetail -> AccessorDetails -> EmailLabelUrl.Newline;
        echo 'User Id: '.$response -> CompletedShipmentDetail -> AccessDetail -> AccessorDetails -> UserId.Newline;
        echo 'Password: '.$response -> CompletedShipmentDetail -> AccessDetail -> AccessorDetails -> Password.Newline;
        echo 'Tracking Number: '.$response -> CompletedShipmentDetail -> CompletedPackageDetails -> TrackingIds -> TrackingNumber.Newline;
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>