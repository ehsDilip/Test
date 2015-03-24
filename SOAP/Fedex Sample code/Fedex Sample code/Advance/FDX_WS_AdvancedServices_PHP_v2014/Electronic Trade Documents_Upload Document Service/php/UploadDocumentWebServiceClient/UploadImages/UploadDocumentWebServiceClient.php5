<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 1.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/UploadDocumentService_v7.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' => array(
		'Key' => getProperty('key'), 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('billaccount'), 
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Upload Images Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'cdus', 
	'Major' => '7', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['OriginCountryCode'] = 'US';  
$request['DestinationCountryCode'] = 'CA';  
$request['Images'] = array(
	'0' => array (
		'Id' => 'IMAGE_1', 
  		'Image' => stream_get_contents(fopen("FedexImage.png", "r"))
  	),
   	'1' => array (
   		'Id' => 'IMAGE_2', 
		'Image' => stream_get_contents(fopen("FedexImage.png", "r"))
	)
);                                            



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->uploadImages($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        foreach($response -> ImageStatuses as $imageStatus){
        	if($imageStatus -> Status == 'SUCCESS')
            {
         	    echo $imageStatus -> Id. ' (';
                echo $imageStatus -> Status.')'.Newline;           	
            }else{
            	echo $imageStatus -> Id. ' (';
                echo $imageStatus -> Status.') - Reason: ';
                echo $imageStatus -> StatusInfo .Newline;
                echo $imageStatus -> Message.Newline;
            }
        }
        printSuccess($client, $response);
    }else{
        printError($client, $response); 
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
	echo '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';  
  	echo "\n";
    printFault($exception, $client);
}
?>