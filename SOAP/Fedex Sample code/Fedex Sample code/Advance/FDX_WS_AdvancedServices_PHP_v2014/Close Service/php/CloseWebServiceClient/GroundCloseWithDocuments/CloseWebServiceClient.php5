<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 2.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/CloseService_v3.wsdl";
$iteration=0;
ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' => array(
		'Key' => getProperty('key'),  // Replace 'XXX' and 'YYY' with FedEx provided credentials 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), // Replace 'XXX' with your account and meter number
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Close With Documents Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'clos', 
	'Major' => '3', 
	'Intermediate' => '1', 
	'Minor' => '0'
);
$request['CloseDate'] = date('Y-m-d');
$request['CloseDocumentSpecification'] = array(
	'CloseDocumentTypes' => array('OP_950','MANIFEST'),
	'Op950Detail' => array('Format' => array('ImageType' => 'PDF')));

try {

	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->groundCloseWithDocuments($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
    	if(array_key_exists("CloseDocuments", $response) && is_array ($response->CloseDocuments)){ 
			foreach($response->CloseDocuments as $closeDocument){
				getDocument($closeDocument);
			}
		}elseif(array_key_exists("CloseDocuments", $response)){
			getDocument($response->CloseDocuments);
		}
	}
	printReply($client, $response);
	writeToLog($client);    // Write to log file
}
catch (SoapFault $exception) {
    printFault($exception, $client);
}

function getDocument($closeDocument){
	global $iteration;
	$type=$closeDocument->Type;
	$extension="";
	$identifier="";
	$fileType="";
	if($type=="OP_950"){
		$extension=".pdf";
		$identifier=$iteration;
		$iteration++;
		$fileType="Op950";
	}else{
		$extension=".txt";
		$identifier=$closeDocument->ShippingCycle;
		$fileType="Manifest";
	}
	$fileName=$fileType.'_'.$identifier.$extension;
	$fp = fopen($fileName, 'wb');
	if(is_array($closeDocument->Parts)){
		foreach($closeDocument->Parts as $part){
			fwrite($fp, $part->Image);
		}
	}else{
		fwrite($fp, $closeDocument->Parts->Image);
	}
	fclose($fp);
	echo '<a href="./'.$fileName.'">'.$fileName.'</a> was generated.'.Newline;
}
?>