<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0

require_once('../library/fedex-common.php5');

$newline = "<br />";
//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../wsdl/ASYNCService_v1.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");
 
$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' =>array(
		'Key' => getProperty('key'), 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), 
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Retrieve Job Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'async', 
	'Major' => '1', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['JobId'] = getProperty('jobid');



try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client -> retrieveJobResults($request);
        
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
    	printSuccess($client, $response);

		//loop through each artifact that is returned in the reply
		if(is_array($response->Artifacts)){
			$result = count($response->Artifacts);
			foreach ($response->Artifacts As $artifact){
				processArtifact($artifact);
			}
		}else{
			processArtifact($response->Artifacts);
		}
	}else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
   printFault($exception, $client);        
}
function processArtifact($artifact){
	//create file and save
	$docType = $artifact->AccessReference;
	$docFormat = $artifact->Format;
	if ($docFormat == "TEXT"){
		$docFormat = "txt";
	}
	$docFileName = $docType. "." .$docFormat;
			
	//open created file and write contents, then close file
	$fp = fopen($docFileName, 'w'); 	
	fwrite($fp, ($artifact->Parts->Contents));
	fclose($fp); 
	
	//create link to doc on screen
	echo '<a href="./'.$docFileName.'">'.$docFileName.'</a> was generated. <br>' ;
}
?>