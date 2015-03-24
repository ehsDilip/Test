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
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Pickup Availability Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'disp', 
	'Major' => 9, 
	'Intermediate' => 0, 
	'Minor' => 0
);
$request['PickupAddress'] = getProperty('address1');
$request['PickupRequestType'] = array('SAME_DAY', 'FUTURE_DAY');
$request['DispatchDate'] = getProperty('pickupdate');
$request['PackageReadyTime'] = getProperty('readytime');
$request['CustomerCloseTime'] = getProperty('closetime');
$request['Carriers'] = array('FDXE','FDXG');
$request['ShipmentAttributes'] = array(
	'Dimensions'=>array(
		'Length'=>'50',
		'Width'=>'45',
		'Height'=>'50',
		'Units'=>'CM'
	),
  	'Weight'=>array(
	  	'Units'=>'KG',
	  	'Value'=>'3.1'
	)
);



try{
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client->getPickupAvailability($request);

	if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
		echo '<table border="1">';
		foreach ($response -> Options as $optionKey => $option){
			echo '<tr><td>';
			if(is_string($option)){
				echo $optionKey . '</td><td>' . $option;
			}else{
				echo '<table border="0">';
				if($option -> Available){
					echo '<tr><td colspan="2">Service Available</td></tr>';
				}
				foreach($option as $subKey => $subOption){
					if(is_string($subOption)){
						echo '<tr><td>'.$subKey.'</td><td>'.$subOption.'</td></tr>';
					}
				}
				if($option -> ResidentialAvailable){
					echo '<tr><td colspan="2">Residential Pickup Available</td></tr>';
				}
				echo '</table>';
			}
			echo '</td></tr>';
		}
		echo '</table>';
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