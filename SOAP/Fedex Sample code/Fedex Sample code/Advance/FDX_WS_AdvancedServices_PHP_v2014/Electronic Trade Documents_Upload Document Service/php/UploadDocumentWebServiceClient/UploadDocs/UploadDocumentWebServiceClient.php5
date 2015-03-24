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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Upload Documents Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'cdus', 
	'Major' => '7', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['OriginCountryCode'] = 'US';  
$request['DestinationCountryCode'] = 'CA';  
$request['Documents'] = array(
	'0' => array (
		'LineNumber' => '1', 
		'CustomerReference' => 'refId-1',
		'DocumentType' => 'CERTIFICATE_OF_ORIGIN', 
		'FileName' => 'CertificateOfOrigin.pdf',
		'DocumentContent' => stream_get_contents(fopen("CertificateOfOrigin.pdf", "r"))
	),
	'1' => array (
		'LineNumber' => '2', 
		'CustomerReference' => 'refId-2',
		'DocumentType' => 'CERTIFICATE_OF_ORIGIN', 
		'FileName' => 'CertificateOfOrigin.pdf',
		'DocumentContent' => stream_get_contents(fopen("CertificateOfOrigin.pdf", "r"))
	)
);                                            



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->uploadDocuments($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
    	foreach($response -> DocumentStatuses as $documentStatuses){
            echo $documentStatuses -> FileName. ' (';
            echo $documentStatuses -> Status.') - Document ID ';
            echo $documentStatuses -> DocumentId.Newline; 
            
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