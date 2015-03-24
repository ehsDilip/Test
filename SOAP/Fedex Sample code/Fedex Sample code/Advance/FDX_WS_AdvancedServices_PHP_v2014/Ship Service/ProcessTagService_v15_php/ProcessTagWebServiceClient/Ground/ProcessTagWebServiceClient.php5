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
		'Key' => getProperty('key'), // Replace 'XXX' and 'YYY' with FedEx provided credentials 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array( 
	'AccountNumber' => getProperty('shipaccount'), // Replace 'XXX' with your account and meter number
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Call Tag Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'ship', 
	'Major' => '15', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = getProperty('shiptimestamp');
$request['RequestedShipment']['ServiceType'] = 'FEDEX_GROUND'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
$request['RequestedShipment']['Shipper'] = array(
	'Contact' => array(
		'PersonName' => 'Sender Name',
       	'CompanyName' => 'Sender Company Name',
     	'PhoneNumber' => '1234567890'
	),
	'Address' => array(
		'StreetLines' => array('Address Line 1'),
      	'City' => 'Collierville',
     	'StateOrProvinceCode' => 'TN',
     	'PostalCode' => '38017',
    	'CountryCode' => 'US',
      	'Residential' => 1
	)
);
$request['RequestedShipment']['Recipient'] = array(
	'Contact' => array(
		'PersonName' => 'Recipient Name',
  		'CompanyName' => 'Recipient Company Name',
  		'PhoneNumber' => '1234567890'
  	),
 	'Address' => array(
 		'StreetLines' => array('Address Line 1'),
       	'City' => 'Herndon',
     	'StateOrProvinceCode' => 'VA',
      	'PostalCode' => '20171',
      	'CountryCode' => 'US',
      	'Residential' => 1
	)
);																	   
$request['RequestedShipment']['ShippingChargesPayment'] = array(
	'PaymentType' => 'SENDER',
  	'Payor' => array(
		'ResponsibleParty' => array(
			'AccountNumber' => getProperty('billaccount'),
			'Contact' => null,
			'Address' => array('CountryCode' => 'US')
		)
	)
);																	 
$request['RequestedShipment']['SpecialServicesRequested'] = array(
	'SpecialServiceTypes' => 'RETURN_SHIPMENT', 
	'ReturnShipmentDetail' => array(
		'ReturnType' => 'FEDEX_TAG',
      	'Rma' => array(
      		'Number' => '012', 
      		'Reason' => 'reason'
      	)
	)
);
$request['RequestedShipment']['PickupDetail'] = array(
	'ReadyDateTime' => getProperty('tag_readytimestamp'), 
	'LatestPickupDateTime' => getProperty('tag_latesttimestamp'), 
	'CourierInstructions' => 'Left on porch'
);
$request['RequestedShipment']['LabelSpecification'] = array(
	'LabelFormatType' => 'COMMON2D',
	'ImageType' => 'PNG'
);
$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
$request['RequestedShipment']['RequestedPackageLineItems'] = array(
	'0' => array(
		'SequenceNumber' => '1',
    	'InsuredValue' => array(
    		'Amount' => 1500.0,
         	'Currency' => 'USD'
		),
   		'ItemDescription' => 'Laptop',
     	'Weight' => array(
     		'Value' => 2.0,
         	'Units' => 'LB'
        ),
       	'Dimensions' => array(
       		'Length' => 25,
          	'Width' => 25,
          	'Height' => 25,
           	'Units' => 'IN'
		),
    	'CustomerReferences' => array(
    		'CustomerReferenceType' => 'INVOICE_NUMBER',
         	'Value' => 'INV4567892'
		)
	)
);



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->processTag($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        // Ground Call Tag Details
        $tagdetails = $response -> CompletedShipmentDetail -> TagDetail;
        if ($tagdetails != null){
            echo 'Confirmation Number: '.$tagdetails -> ConfirmationNumber .Newline;
            if(!empty($tagdetails -> AccessTime)) echo 'Access Time: '.$tagdetails -> AccessTime .Newline;
            if(!empty($tagdetails -> CutoffTime)) echo 'Cutoff Time: '.$tagdetails -> CutoffTime .Newline;
            if(!empty($tagdetails -> Location)) echo 'Location: '.$tagdetails -> Location .Newline;
            if(!empty($tagdetails -> DeliveryCommitment)) echo 'Delivery commitment: '.$tagdetails -> DeliveryCommitment .Newline;
            if(!empty($tagdetails -> DispatchDate)) echo 'Dispatch date: '.$tagdetails -> DispatchDate .Newline;
        }
       
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>