<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0

require_once('../../library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../wsdl/OpenShipService_v7.wsdl";

define('SHIP_LABEL', 'shiplabel.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
$index="index_" + rand(100000, 1000000);;
$consolidationKey;
$consolidationIndex;
$shipDate=date('c');
$ConsolidationTrackingNumber;
$deleteConsolidation=false;
try{
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	/*
		This set of methods can exceed default maximum alloted time for execution, especially if asynchronous processing occurs.
		
		To Consolidation methods are being ran in the following order:
			CreateConsolidation
			CreateOpenShipment
			RetrieveConsolidation
			ModifyConsolidation
			RetrieveConsolidatedCommodities
			ConfirmConsolidation
		If any of these methods is not successful the process stops.  If the CreateConsolidation is created the
		DeleteConsolidation method is run.	
	*/
	global $deleteConsolidation;
	echo "<br/><br>++++Create Consolidation++++<br/><br/>";
	$responseCreateConsolidation = $client->createConsolidation(buildCreateConsolidatedRequest());
	if(isSuccess($client, $responseCreateConsolidation)){
		$deleteConsolidation = true;
		processCreateConsolidationResponse($responseCreateConsolidation);
		echo "<br/><br>++++Create Open Shipment++++<br/><br/>";
		$responseCreateOpenShipment = $client->createOpenShipment(buildCreateOpenShipmentRequest());
		if(isSuccess($client, $responseCreateOpenShipment)){
			processCreateOpenShipmentResponse($client, $responseCreateOpenShipment);
			echo "<br/><br>++++Retrieve Consolidation++++<br/><br/>";
			$responseRetrieveConsolidation = $client->retrieveConsolidation(buildRetrieveConsolidationRequest());
			if(isSuccess($client, $responseRetrieveConsolidation)){
				processRetrieveConsolidationResponse($responseRetrieveConsolidation);
				echo "<br/><br>++++Modify Consolidation++++<br/><br/>";
				$responseModifyConsolidationShipment = $client->modifyConsolidation(buildModifyConsolidationRequest($responseRetrieveConsolidation->RequestedConsolidation));
				if(isSuccess($client, $responseModifyConsolidationShipment)){
					processModifyConsolidationResponse($responseModifyConsolidationShipment);
					echo "<br/><br>++++Retrieve Consolidated Commodities++++<br/><br/>";
					$responseRetrieveCommodities = $client->retrieveConsolidatedCommodities(buildRetrieveConsolidatedCommoditiesRequest());
					if(isSuccess($client, $responseRetrieveCommodities)){
						processRetrieveCommoditiesResponse($responseRetrieveCommodities);
						echo "<br/><br>++++Confirm Consolidation++++<br/><br/>";
						$responseConfirmConsolidation = $client->confirmConsolidation(buildConfirmConsolidationRequest());
						if(isSuccess($client, $responseConfirmConsolidation)){
							$deleteConsolidation = false;
							processConfirmConsolidationResponse($responseConfirmConsolidation, $client);
						}
					}
				}
			}
		}
	}
}
catch (SoapFault $exception) {
    printFault($exception, $client);
}

function getIndex(){
	global $index;
	return $index;
}

function isSuccess($client, $response){
	printNotifications($response->Notifications);
	printRequestResponse($client);
	if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
		return true;
	}else
	{
		global $deleteConsolidation;
		if($deleteConsolidation)
		{
			echo "<br/><br>++++Delete Consolidation++++<br/><br/>";
			$deleteReply=$client->deleteOpenConsolidation(buildDeleteOpenConsolidationRequest()); 
			printNotifications($deleteReply->Notifications);
			printRequestResponse($client);
		}
		return false;
	}
}
//This function creates the portion of OpenShip request common to all methods.
function buildTransactionDetail($transactionId){
	$request['WebAuthenticationDetail'] = array(
		'UserCredential' => array(
			'Key'=> getProperty('key'), 
			'Password'=> getProperty('password')
		)
	);
	$request['ClientDetail'] = array(
		'AccountNumber' => getProperty('shipaccount'), 
		'MeterNumber' => getProperty('meter')
	);
	$request['TransactionDetail'] = array('CustomerTransactionId' => $transactionId);
	$request['Version'] = array(
		'ServiceId' => 'ship', 
		'Major' => '7', 
		'Intermediate' => '0', 
		'Minor' => '0'
		
	);
	return $request;
}

function createConsolidationIndex()
{
	global $consolidationIndex;
	if ($consolidationIndex == null)
	{
		$consolidationIndex = "consolidation_" . rand(100000, 1000000);
	}
	return $consolidationIndex;
}

function getConsolidationKey()
{
	global $consolidationKey;
	return $consolidationKey;
}

function setConsolidationKey($key)
{
	global $consolidationKey;
	$consolidationKey = $key;
}

function buildCreateConsolidatedRequest()
{
	$request = buildTransactionDetail('***Create TD Consolidation using PHP***');
	$request['ConsolidationIndex'] = createConsolidationIndex(); //Set this to value used to reference consolidation
	$request['RequestedConsolidation'] = addRequestedConsolidation();
	return $request;
}

function addRequestedConsolidation()
{
	global $shipDate;
	$consolidation = array(
		'ConsolidationType' => "TRANSBORDER_DISTRIBUTION",
		'ShipDate' => date('Y-m-d'),
		'Shipper' => addShipper(),
		'Origin' => addOrigin(),
		'SoldTo' => addSoldTo(),
		'ConsolidationDataSources' => array(
			'0' => array(
				'Field' => "TOTAL_CUSTOMS_VALUE",
				'FieldSpecified' => true,
				"Source" => "ACCUMULATED"
			)
		),
		'CustomerReferences' => array(
			'0' => array(
				'CustomerReferenceType' => "CUSTOMER_REFERENCE",
				'Value' => "USD"
			)
		),
		'LabelSpecification' => array(
			'Dispositions' => array(
				'DispositionType' => 'RETURNED',
				'Grouping' => 'CONSOLIDATED_BY_DOCUMENT_TYPE'
			),
			'LabelFormatType' => 'COMMON2D',
			'ImageType' => 'PDF',
			'LabelStockType' => 'PAPER_4X8'
		),
		'InternationalDistributionDetail' => addInternationaDistributionDetail(),
		'TransborderDistributionDetail' => addTransborderDistributionDetail(),
		'DistributionLocations' => array(
			'0' => array(
				'Type' => "FEDEX_EXPRESS_STATION",
				'LocationId' => getProperty("locationid")
			)
		),
		'CustomsClearanceDetail' => addCustomsClearanceDetail(),
		'ShippingChargesPayment' => addShippingChargesPayment(),
		'ConsolidationDocumentSpecification' => addConsolidationDocumentSpecification()
		);
	return $consolidation;
}

function addConsolidationDocumentSpecification()
{
	$CDS = array(
		'ConsolidationDocumentTypes' => array(
			'0' => 'CONSOLIDATED_COMMERCIAL_INVOICE',
			'1' => 'CRN_REPORT'
		),
		'CrnReportDetail' => array(
			'Format' => array(
				'CustomDocumentIdentifier' => "CrnReport123",
				'Dispositions' => array(
					'0' => array('DispositionType' => 'RETURNED')
				),
				'ImageType' => 'PDF'
			)
		),
		'ConsolidatedCommercialInvoiceDetail' => array(
			'Format' => array(
				'CustomDocumentIdentifier' => 'CrnReport123',
				'Dispositions' => array(
					'0' => array('DispositionType' => 'RETURNED')
				),
				'ImageType' => 'PDF'
			)
		)
	);
	return $CDS;
}

function addCustomsClearanceDetail()
{
	$ccd = array(
		'Brokers' => array(
			'0' => array(
				'Type' => 'IMPORT',
				'BrokerageProcessingChargesPayment' => array(
					'AccountNumber' => getProperty("brokeraccount"),
					'Tins' => array(
						'TinType' => 'BUSINESS_NATIONAL',
						'Number' => 'INDIVIDUAL'
					),
					'Contact' => array(
						'CompanyName' => 'Importer Company',
						'PersonName' => 'Importer',
						'PhoneNumber' => '1234567890',
						'EMailAddress' => 'broker@importer.com'
					),
					'Address' => array(
						'StreetLines' => array( '0' => '1 Importer Street'),
						'City' => 'Memphis',
						'StateOrProvinceCode' => 'TN',
						'PostalCode' => '38110',
						'CountryCode' => 'US',
						'Residential' => false
					),
					'payment' => array(
						'PaymentType' => 'SENDER',
						'Payor' => array(
							'ResponsibleParty' => array(
								'AccountNumber' => getProperty("brokeraccount"),
								'Tins' => array(
									'0' => array(
										'TinType' => 'BUSINESS_NATIONAL',
										'Number' => 'INDIVIDUAL'
									)
								),
								'Contact' => array(
									'CompanyName' => 'Importer Company',
									'PersonName' => 'Importer',
									'PhoneNumber' => '1234567890',
									'EMailAddress' => 'broker@importer.com'
								),
								'Address' => array(
									'StreetLines' => array('0' => '1 Importer Street'),
									'City' => 'Richmond',
									'StateOrProvinceCode' => 'BC',
									'PostalCode' => 'V7C4V7',
									'CountryCode' => 'CA',
									'Residential' => false
								)
							)
						)
					)
				)
			)

		),
		'CustomsOptions' => array(
			'Type' => 'OTHER',
			'Description' => 'Consolidatedproduct'
		),
		'ImporterOfRecord' => array(
			'AccountNumber' => getProperty("importeraccount"),
			'Contact' => array(
				'CompanyName' => 'Importer Company',
				'PersonName' => 'Importer Person',
				'PhoneNumber' => '1234567890',
				'EMailAddress' => 'importer@company.com'
			),
			'Address' => array(
				'StreetLines' => array('0' => '1 Importer St'),
				'City' => 'Richmond',
				'StateOrProvinceCode' => 'BC',
				'PostalCode' => 'V7C4V5',
				'CountryCode' => 'CA',
				'Residential' => false
			)
		),
		'RecipientCustomsId' => array(
			'Type' => 'COMPANY',
			'Value' => '125'
		),
		'DutiesPayment' => array(
			'PaymentType' => 'SENDER',
			'Payor' => array(
				'ResponsibleParty' => array(
					'AccountNumber' => getProperty("dutiesaccount"),
					'Contact' => array(
						'CompanyName' => 'Company Name',
						'PersonName' => 'Person Name',
						'PhoneNumber' => '1234567890',
						'EMailAddress' => 'person@company.com'
					),
					'Address' => array(
						'StreetLines' => array('0' => '1 Address Line'),
						'City' => 'Memphis',
						'StateOrProvinceCode' => 'TN',
						'PostalCode' => '38118',
						'CountryCode' => 'US',
						'Residential' => false
					)
				)
			)
		),
		'DocumentContent' => 'NON_DOCUMENTS',
		'CustomsValue' => array(
			'Currency' => 'USD',
			'Amount' => '100.0'
		),
		'InsuranceCharges' => array(
			'Currency' => 'USD',
			'Amount' => '5.0'
		),
		'CommercialInvoice' => array(
			'TermsOfSale' => 'DDP'
		),
		'Commodities' => array(
			'0' => addCommodity()
		),
		'ExportDetail' => array(
			'ExportComplianceStatement' => '30.37(f)'
		)
	);
	return $ccd;
}

function addCommodity()
{
	$commodity = array(
		'Name' => 'product1',
		'NumberOfPieces' => '1',
		'Description' => 'Maple Syrup',
		'HarmonizedCode' => '170220229',
		'CountryOfManufacture' => 'CA',
		'Weight' => array(
			'Units' => 'LB',
			'Value' => '1.0'
		),
		'Quantity' => '1',
		'QuantityUnits' => 'EA',
		'UnitPrice' => array(
			'Currency' => 'USD',
			'Amount' => '10.0'
		),
		'CustomsValue' => array(
			'Currency' => 'USD',
			'Amount' => '10.0'
		),
		'ExportLicenseNumber' => '123456',
		'ExportLicenseExpirationDate' => '2014-11-01',
		'CIMarksAndNumbers' => '124553',
		'PartNumber' => '1245'
	);
	return $commodity;
}

function addTransborderDistributionDetail()
{
	$tdd = array(
		'SpecialServicesRequested' => array(
			'SpecialServiceTypes' => array('0' => 'FEDEX_LTL'),
			'TransborderDistributionLtlDetail' => addTransborderDistributionLtlDetail()
		)
	);
	return $tdd;
}

function addTransborderDistributionLtlDetail()
{
	$detail = array(
			'Payment' => array(
				'PaymentType' => 'SENDER',
				'Payor' => array(
					'ResponsibleParty' => array(
						'AccountNumber' => getProperty("distributionaccount"),
						'Contact' => array(
							'PersonName' => 'Distribution Person',
							'CompanyName' => 'Distribution Company',
							'PhoneNumber' => '1234567890',
							'EMailAddress' => 'distribution@company.com'
						),
						'Address' => array(
							'StreetLines' => array('0' => '1 Distribution Street'),
							'City' => 'Richmond',
							'StateOrProvinceCode' => 'BC',
							'PostalCode' => 'V7C4V7',
							'CountryCode' => 'CA',
							'Residential' => false
					)
				)
			)
		),
		'LtlScacCode' => 'Scac'
	);
	return $detail;
}

function addInternationaDistributionDetail()
{
	$idd = array(
		'DropoffType' => 'REGULAR_PICKUP',
		'TotalDimensions' => array(
			'Length' => '12',
			'Width' => '12',
			'Height' => '12',
			'Units' => 'IN'
		),
		'TotalInsuredValue' => array(
			'Currency' => 'USD',
			'Amount' => '5'
		),
		'UnitSystem' => 'ENGLISH',
		'DeclarationCurrencies' => array(
			'0' => array(
				'Value' => 'CUSTOMS_VALUE',
				'Currency' => 'USD'
			)
		),
		'ClearanceFacilityLocationId' => getProperty("locationid")
	);
	return $idd;
}

function addOrigin()
{
	// Origin information
	$origin = array(
		'Contact' => array(
		'PersonName' => 'Origin Name',
		'CompanyName' => 'Origin Company Name',
		'PhoneNumber' => '0123456789'
		),
		'Address' => array(
			'StreetLines' => array('0' => 'Address Line 1'),
			'City' => 'Richmond',
			'StateOrProvinceCode' => 'BC',
			'PostalCode' => 'V7C4V7',
			'CountryCode' => 'CA',
			'Residential' => false
		)
	);
	return $origin;
}

function addSoldTo()
{
	// SoldTo information
	$soldto = array(
		'Contact' => array(
			'PersonName' => 'Soldto Name',
			'CompanyName' => 'Soldto Company Name',
			'PhoneNumber' => '0123456789'
		),
		'Address' => array(
			'StreetLines' => array('0' => 'Address Line 1'),
			'City' => 'Memphis',
			'StateOrProvinceCode' => 'TN',
			'PostalCode' => '38118',
			'CountryCode' => 'US',
			'Residential' => false
		)
	);
	return $soldto;
}

function addPrintedLabelOrigin()
{
	$labelorigin = array(
		'Contact' => array(
			'PersonName' => 'Print Origin Name',
			'CompanyName' => 'Print Origin Company',
			"PhoneNumber" => '1234567890',
			'EMailAddress' => 'person@company.com'	
		),
		'Address' => array(
			'StreetLines' => array( '0' => '1 Print Origin St'),
			'City' => 'Collierville',
			'StateOrProvinceCode' => 'TN',
			'PostalCode' => '38017',
			'CountryCode' => 'US',
			'Residential' => 'false'
		)
	);
	return $labelorigin;
}

function buildGetConfirmConsolidationResultsRequest($JobId)
{
	$request = array();
	$request = buildTransactionDetail('*** Get Confirm Consolidation Results Request using PHP ***');
	$request['JobId'] = JobId;
	return $request;
}

function buildCreateOpenShipmentRequest()
{
	$request = buildTransactionDetail('*** Create OpenShip Service Request using PHP ***');
	$request['Index'] = getIndex();
	$request['AsynchronousProcessingOptions'] = array('0' => 'ALLOW_ASYNCHRONOUS');
	$request['ConsolidationKey'] = getConsolidationKey();
	$request['Actions'] = array('0' => 'CONFIRM');
	$request['RequestedShipment'] = addRequestedShipment();
	return $request;
}

function addRequestedShipment()
{
	global $shipDate;
	$NumberOfPackages = getProperty("packagecount");
	$requestedShipment = array(
		'ShipTimestamp' => $shipDate,
		'DropoffType' => 'REGULAR_PICKUP',
		'ServiceType' => 'PRIORITY_OVERNIGHT',
		'PackagingType' => 'YOUR_PACKAGING',
		'TotalWeight' => array(
			'Units' => 'LB',
			'Value' => '80.0'
		),
		'TotalInsuredValue' => array(
			'Currency' => 'USD',
			'Amount' => '5.0',
		),
		'Shipper' => addShipper(),
		'Recipient' => addRecipient(),
		'ShippingChargesPayment' => addShippingChargesPayment(),
		'ProcessingOptionsRequested' => array('0' => 'PACKAGE_LEVEL_COMMODITIES'),
		'ConsolidationDetail' => addShipmentConsolidationDetail(),
		'LabelSpecification' => addLabelSpecification(),
		'PackageCount' => $NumberOfPackages,
		'RequestedPackageLineItems' => addRequestedPackageLineItem('12', '12', '12', 'IN', '20.0', 'LB', $NumberOfPackages)
	);
	return $requestedShipment;
}

function buildModifyConsolidationRequest($consolidation)
{
	global $ConsolidationTrackingNumber;
	if (!empty($consolidation->CustomerReferences) && is_array($consolidation->CustomerReferences))
	{
		foreach ($consolidation->CustomerReferences as $reference)
		{
			if ($reference->CustomerReferenceType === 'CUSTOMER_REFERENCE')
			{
				$reference-> Value = "Mod_" + $ConsolidationTrackingNumber;
			}
		}
	}elseif(!empty($consolidation->CustomerReferences))
	{
		if($consolidation->CustomerReferences->CustomerReferenceType === 'CUSTOMER_REFERENCE')
		{
			$consolidation->CustomerReferences->Value = "Mod_" . $ConsolidationTrackingNumber;
		}
	}
	$request = array();
	$request = buildTransactionDetail('*** Modify Consolidation Request using PHP ***');
	$request['ConsolidationKey'] = getConsolidationKey();
	$request['RequestedConsolidation'] = $consolidation;
	return $request;
}

function buildConfirmConsolidationRequest()
{
	$request = array();
	$request = buildTransactionDetail('*** Confirm Consolidation Request using PHP ***');
	$request['ConsolidationKey'] = getConsolidationKey();
	$request['RateRequestTypes'] = array( '0' => 'PREFERRED');
	return $request;
}

function buildRetrieveConsolidationRequest()
{
	$request = array();
	$request = buildTransactionDetail('*** Retrieve Consolidation Request using PHP ***');
	$request['ConsolidationKey'] = getConsolidationKey();
	return $request;
}

function addShipmentConsolidationDetail()
{
	$scd = array(
		'Type' => 'TRANSBORDER_DISTRIBUTION',
		'InternationalDistributionDetail' => addShipmentInternationalDistributionDetail()
	);
	return $scd;
}

function addShipmentInternationalDistributionDetail()
{
	$sidd = array(
		'ClearanceFacilityLocId' => getProperty('locationid'),
		'ClearanceType' => 'DESTINATION_COUNTRY_CLEARANCE',
		'SummaryDetail' => array(
			'TotalWeight' => array(
				'Units' => 'LB',
				'Value' => '10.0'
			),
			'TotalPackageCount' => '3',
			'TotalUniqueAddressCount' => '1'
		),
		'TotalCustomsValue' => array(
			'Currency' => 'USD',
			'Amount' => '100.0'
		),
		'TotalInsuredValue' => array(
			'Currency' => 'USD',
			'Amount' => '5.0'
		)
	);
	return $sidd;
}

function buildRetrieveConsolidatedCommoditiesRequest()
{
	$request = array();
	$request = buildTransactionDetail('*** Retrieve Consolidated Commodities Request using PHP ***');
	$request['ConsolidationKey'] = getConsolidationKey();
	return $request;
}

function buildDeleteOpenConsolidationRequest()
{
	$request = array();
	$request = buildTransactionDetail('*** Delete Consolidation Request using PHP ***');
	$request['ConsolidationKey'] = getConsolidationKey();
	return $request;
}

function addWebAuthenticationDetail()
{
	$wad = array(
		'UserCredential' => array(
			'Key' => getProperty("key"), // Replace "XXX" with the Key
			'Password' => getProperty("password") // Replace "XXX" with the Password
		)
	);
	return $wad;
}

function addClientDetail()
{
	$clientDetail = array(
		'AccountNumber' => getProperty("accountnumber"), // Replace "XXX" with client's account number
		'MeterNumber' => getProperty("meternumber") // Replace "XXX" with client's meter number
	);
	return $clientDetail;
}

function addShipper()
{
	// Sender information
	$sender = array(
		'AccountNumber' => getProperty("accountnumber"),
		'Tins' => array(
			'0' => array(
				'TinType' => 'BUSINESS_NATIONAL',
				'Number' => 'INDIVIDUAL'
			)
		),
		'Contact' => array(
			'PersonName' => 'Sender Name',
			'CompanyName' => 'Sender Company Name',
			'PhoneNumber' => '0123456789'
		),
		'Address' => Array(
			'StreetLines' => array('0' => 'Address Line 1'),
			'City' => 'Richmond',
			'StateOrProvinceCode' => 'BC',
			'PostalCode' => 'V7C4V7',
			'CountryCode' => 'CA',
			'Residential' => false
		)
	);
	return $sender;
}

function addRecipient()
{
	// Recipient information
	$recipient = array(
		'Contact' => array(
			'PersonName' => 'Recipient Name',
			'CompanyName' => 'Recipient Company Name',
			'PhoneNumber' => '1234567890'
		),
		'Address' => array(
			'StreetLines' => array('0' => 'Address Line 1'),
			'City' => 'Windsor',
			'StateOrProvinceCode' => 'CT',
			'PostalCode' => '06006',
			'CountryCode' => 'US'
		)
	);
	return $recipient;
}

function addShippingChargesPayment()
{
	// Payment information
	$payment = array(
		'PaymentType' => 'SENDER',
		'Payor' => array(
			'ResponsibleParty' => array(
				'AccountNumber' => getProperty("billaccount"),
				'Contact' => array(
					'PersonName' => 'Payor Contact',
					'CompanyName' => 'Payor Company',
					'PhoneNumber' => '1234567890',
					'EMailAddress' => 'person@payorcompany.com'
				),
				'Address' => array(
					'StreetLines' => array( '0', '1 Sender Street'),
					'City' => 'Memphis',
					'StateOrProvinceCode' => 'TN',
					'PostalCode' => '38017',
					'CountryCode' => 'US'
				)
			)
		)
	);
	return $payment;
}

function addLabelSpecification()
{
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D',
		'Dispositions' => array(
			'0' => array(
				'DispositionType' => 'RETURNED',
				'Grouping' => 'CONSOLIDATED_BY_DOCUMENT_TYPE'
			)
		),
		'ImageType' => 'PDF',
		'LabelStockType' => 'PAPER_4X6',
		'PrintedLabelOrigin' => addPrintedLabelOrigin()
	);
	return $labelSpecification;
}

function addRequestedPackageLineItem($length, $width, $height, $linearUnits, $packageWeightValue, $weightUnits, $groupPackageCount)
{
	$requestedPackageLineItems = array(
		'0' => array(
			'SequenceNumber' => '1',
			'GroupPackageCount' => $groupPackageCount,
			'Weight' => addPackageWeight($packageWeightValue, $weightUnits),
			'Dimensions' => addPackageDimensions($length, $width, $height, $linearUnits),
			'Commodities' => array(addCommodity())
		)
	);	
	return $requestedPackageLineItems;
}

function addPackageWeight($packageWeight, $weightUnits)
{
	$weight = array(
			'Units' => $weightUnits,
		'Value' => $packageWeight
	);
	return $weight;
}

function addPackageDimensions($length, $width, $height, $units)
{
	$dimensions = array(
		'Length' => $length,
		'Width' => $width,
		'Height' => $height,
		'Units' => $units
	);
	return $dimensions;
}

function processCreateOpenShipmentResponse($client, $reply)
{
	echo "Processing Create Open Ship Reply<br\>";
	foreach ($reply->AsynchronousProcessingResults As $AsynchResults)
	{
		if ($AsynchResults === 'ASYNCHRONOUSLY_PROCESSED')
		{
			$maxwait = 1;
			while (processGetCreateOpenShipmentResults($client, $reply->JobId) && $maxwait <= 3){ $maxwait++; }
		}
	}
	if ($reply->HighestSeverity === 'SUCCESS' ||
		$reply->HighestSeverity === 'WARNING' ||
		$reply->HighestSeverity === 'NOTE')
	{
		printCompletetedShipmentDetail($reply->CompletedShipmentDetail);
	}
}

function processRetrieveConsolidationResponse($reply)
{
	echo "Processing Retrieve Consolidation Reply<br\>";
}

function processModifyConsolidationResponse($reply)
{
	echo "Modify Consolidation Reply<br\>";
}

function processGetCreateOpenShipmentResults($client, $JobId)
{
	$reply = $client->getCreateOpenShipmentResults(buildGetCreateOpenShipmentResultsRequest($JobId));
	if($reply->HighestSeverity === 'ERROR' && checkForAsynchronousReply($reply->Notifications))
	{
		echo "<br/>Open Shipment Results still processing.  Waiting 5 seconds.<br/>";
		sleep(5);
		return true;
	}
	if ($reply->HighestSeverity === 'SUCCESS' ||
	$reply->HighestSeverity === 'WARNING' ||
	$reply->HighestSeverity === 'NOTE')
	{
		printCompletetedShipmentDetail($reply->CompletedShipmentDetail);
	}
	return false;
}

function buildGetCreateOpenShipmentResultsRequest($JobId)
{
	$request = array();
	$request = buildTransactionDetail('*** Get Open Ship Results Request using PHP ***');
	$request['JobId'] = $JobId;
	return $request;
}

function processRetrieveCommoditiesResponse($reply)
{
	echo "<br\>Retrieve Commodities Reply details:<br/>";
	$summary = $reply->ConsolidatedCommoditiesSummary;
	if ($summary->TotalCustomsValue != null)
	{
		echo "Total Customs: {$summary->TotalCustomsValue->Amount} {$summary->TotalCustomsValue->Currency}<br/>"; 
	}
	$detail;
	if ($summary->ConsolidatedCommodities != null && is_array($summary->ConsolidatedCommodities))
	{
		foreach ($summary->ConsolidatedCommodities As $detail)
		{
			printConsolidatedCommodity($detail);
		}
	}elseif($summary->ConsolidatedCommodities != null)
	{
		printConsolidatedCommodity($summary->ConsolidatedCommodities);
	}
}

function printConsolidatedCommodity($consolidatedCommodity)
{
	if ($consolidatedCommodity != null)
	{
		if ($consolidatedCommodity->GroupDescription != null)
		{
			echo "Group Description: {$consolidatedCommodity->GroupDescription}<br/>";
		}
		if ($consolidatedCommodity->SubtotalCustomsValue != null)
		{
			echo "  Customs subtotal: {$consolidatedCommodity->SubtotalCustomsValue->Amount} {$consolidatedCommodity->SubtotalCustomsValue->Currency}<br/>";
		}
		echo "  Quatity subtotal: {$consolidatedCommodity->SubtotalQuantity}<br/>";
		if($consolidatedCommodity->Commodities != null && is_array($consolidatedCommodity->Commodities))
		{
			foreach($consolidatedCommodity->Commodities As $commodities)
			{
				printCommodity($commodities->Commodity);
			}
		}elseif($consolidatedCommodity->Commodities != null)
		{
			printCommodity($consolidatedCommodity->Commodities->Commodity);
		}
	}
}

function printCommodity($commodity)
{
	if($commodity != null)
	{
		echo "&nbsp;&nbsp;Name: {$commodity->Name}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Description: {$commodity->Description}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Harmonized code: {$commodity->HarmonizedCode}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Country of manufacture: {$commodity->CountryOfManufacture}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Unit price: {$commodity->UnitPrice->Amount} {$commodity->UnitPrice->Currency}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Customs amount: {$commodity->CustomsValue->Amount} {$commodity->CustomsValue->Currency}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Number of pieces: {$commodity->NumberOfPieces}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Quantity: {$commodity->Quantity} {$commodity->QuantityUnits}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Weight: {$commodity->Weight->Value} {$commodity->Weight->Units}<br/>";
	}
}

function printMasterTrackNumber($completedShipmentDetail)
{
	if ($completedShipmentDetail == null) return;
	if (empty($completedShipmentDetail->MasterTrackingId)) return;
	echo "Master Tracking Type: {$completedShipmentDetail->MasterTrackingId->TrackingIdType}<br/>";
	echo "Master Tracking Number: {$completedShipmentDetail->MasterTrackingId->TrackingNumber}<br/>";
}

static $jobId;
function setJobId($JobId)
{
	$jobId = $JobId;
}
function getJobId()
{
	return $jobId;
}

function processCreateConsolidationResponse($reply)
{	
	global $ConsolidationTrackingNumber;
	if($reply->ConsolidationKey != null)
	{
		setConsolidationKey($reply->ConsolidationKey);
	}
	if($reply->TrackingIds != null)
	{	
		$trackId = $reply->TrackingIds;
		if(is_array($trackId))
		{
			foreach($trackId as $track)
			{
				echo "Consolidation tracking number: $track->TrackingNumber ";
				$ConsolidationTrackingNumber = $track->TrackingNumber;
			}
		}
		else
		{
			echo "Consolidation tracking number: $trackId->TrackingNumber ";
			$ConsolidationTrackingNumber = $trackId->TrackingNumber;
		}
		echo "<br/><br/>";
	}
}

function processConfirmConsolidationResponse($reply, $client)
{
	echo "<br/>ConfirmConsolidation Reply details:<br/>";
	foreach ($reply->AsynchronousProcessingResults As $AsynchResults)
	{
		if ($AsynchResults === 'ASYNCHRONOUSLY_PROCESSED')
		{
			$maxwait = 1;
			while (processGetConfirmConsolidation($client, $reply->JobId) && $maxwait <= 3){ $maxwait++; }
		}
	}
	printCompletedConsolidationDetail($reply->CompletedConsolidationDetail);
}

function printCompletedConsolidationDetail($ccd)
{
	if($ccd->ConsolidationShipments != null && is_array($ccd->ConsolidationShipments))
	{
		foreach ($ccd->ConsolidationShipments As $cs)
		{
			printConsolidatedShipments($cs);
		}
	}elseif($ccd->ConsolidationShipments != null)
	{
		printConsolidatedShipments($ccd->ConsolidationShipments);
	}
	if ($ccd->Documents != null && getProperty("printdocuments")) { printConsolidationDocuments($ccd->Documents); }
}

function printConsolidatedShipments($cs)
{
	if($cs != null)
	{
		if ($cs->ShipmentRoleType != null) { echo "Shipment Role: {$cs->ShipmentRoleType}<br/>"; }
		$csd = $cs->CompletedShipmentDetail;
		if ($csd->CarrierCode != null) { echo "Carrier code: {$csd->CarrierCode}<br/>"; }
		printMasterTrackNumber($csd);
	}
}

function printCompletetedShipmentDetail($csd)
{
	if ($csd == null) return;
	printMasterTrackNumber($csd);
	if (!empty($csd->OperationalDetail)) { printShipmentOperationalDetails($csd->OperationalDetail); }
	if (!empty($csd->ShipmentRating)) { printShipmentRateDetails($csd->ShipmentRating); }
	if (!empty($csd->CompletedPackageDetails)) { printPackageDetails($csd->CompletedPackageDetails); }
}

function printConsolidationDocuments($Documents)
{
	global $ConsolidationTrackingNumber;
	if ($Documents != null)
	{
		$iteration = 0;
		foreach ($Documents As $document)
		{
			$docType = "docType";
			if ($document->Type != null) { $docType = $document->Type; }
			$imageType = "imageType";
			if ($document->ImageType != null) { $imageType = $document->ImageType; }
			$docIdentifier = $ConsolidationTrackingNumber . "_" . $iteration;
			$iteration++;
			$labelName = $docType . "_" . $docIdentifier . "." . $imageType;
			$fp = fopen($labelName, 'wb');
			if($document->Parts != null && is_array($document->Parts))
			{
				foreach($document->Parts As $sdpart)
				{
  
					fwrite($fp, ($sdpart->Image));
				}
			}elseif($document->Parts != null)
			{
				fwrite($fp, ($document->Parts->Image));
			}
			fclose($fp);
			echo 'Shipping Documents <a href="./'.$labelName.'">'.$labelName."</a> was generated.<br/><br\>";
		}
	}
}

function printShipmentRateDetails($rating)
{
	if($rating != null)
	{
		echo "Shipment Rating<br/>";
		if (!empty($rating->ActualRateType)) { echo "Rate type: {$rating->ActualRateType}<br/>"; }
		if (!empty($rating->EffectiveNetDiscount))
		{
			echo "Effective discount: {$rating->EffectiveNetDiscount->Amount} {$rating->EffectiveNetDiscount->Currency}<br/>";
			if ($rating->ShipmentRateDetails != null)
			{
				echo "  Rating details:<br/>";
				foreach ($rating->ShipmentRateDetails As $detail)
				{
					echo "  Total net charge: {$detail->TotalNetCharge->Amount} {$detail->TotalNetCharge->Currency}<br/>";
					echo "  Total net charge with duties and taxes: {$detail->TotalNetChargeWithDutiesAndTaxes->Amount} {$detail->TotalNetChargeWithDutiesAndTaxes->Currency}<br/>";
					echo "  Total net FedEx charge: {$detail->TotalNetFedExCharge->Amount} {$detail->TotalNetFedExCharge->Currency}<br/>";
					echo "  Total net freight: {$detail->TotalNetFreight->Amount} {$detail->TotalNetFreight->Currency}<br/>";
					echo "  Currency exchange from {$detail->CurrencyExchangeRate->FromCurrency} to {$detail->CurrencyExchangeRate->IntoCurrency} at {$detail->CurrencyExchangeRate->Rate}<br/>";
					if (!empty($detail->DimDivisorType)) { echo "  Dim divisor: {$detail->DimDivisor} {$detail->DimDivisorType}<br/>"; }
					if (!empty($detail->FuelSurchargePercent)) { echo "  Fuel surcharge: {$detail->FuelSurchargePercent}<br/>"; }
					if (!empty($detail->MinimumChargeType)) { echo "  Minimum charge type: {$detail->MinimumChargeType}<br/>"; }
					if (!empty($detail->PricingCode)) { echo "  Pricing code: {$detail->PricingCode}<br/>"; }
					if (!empty($detail->RatedWeightMethod)) { echo "  Rate weight method: {$detail->RatedWeightMethod}<br/>"; }
					if (!empty($detail->RateType)) { echo "  Rate type: {$detail->RateType}<br/>"; }
					echo "  Total base charge: {$detail->TotalBaseCharge->Amount} {$detail->TotalBaseCharge->Currency}<br/>";
					echo "  Total billing weight: {$detail->TotalBillingWeight->Value} {$detail->TotalBillingWeight->Units}<br/>";
					echo "  Total dim weight: {$detail->TotalDimWeight->Value} {$detail->TotalDimWeight->Units}<br/>";
					echo "  Total surcharges: {$detail->TotalSurcharges->Amount} {$detail->TotalSurcharges->Currency}<br/>";
					if ($detail->Surcharges != null)
					{
						echo "    Surcharge details";
						foreach ($detail->Surcharges As $surcharge)
						{
							echo "      Description: {$surcharge->Description}<br/>";
							echo "      Amount: {$surcharge->Amount->Amount} {$surcharge->Amount->Currency}";
							if (!empty($surcharge->SurchargeType)) { echo "  Type: {$surcharge->SurchargeType}<br/>"; }
							if (!empty($surcharge->Level)) { echo "  Level: {$surcharge->Level}<br/>"; }
						}
					}
					echo "  Total freight discounts: {$detail->TotalFreightDiscounts->Amount} {$detail->TotalFreightDiscounts->Currency}<br/>";
					if ($detail->FreightDiscounts != null)
					{
						echo "    Discount details<br/>";
						foreach ($detail->FreightDiscounts As $discount)
						{
							echo "      Description: {$discount->Description}<br/>";
							echo "      Amount: {$discount->Amount->Amount} {$discount->Amount->Currency}<br/>";
							if (!empty($discount->RateDiscountType)) { echo "  Type: {$discount->RateDiscountType}<br/>"; }
							if (!empty($discount->Percent)) { echo "  Percent: {$discount->Percent}<br/>"; }
						}
					}
					echo "  Total taxes: {$detail->TotalTaxes->Amount} {$detail->TotalTaxes->Currency}<br/>";
					echo "  Total duties and taxes: {$detail->TotalDutiesAndTaxes->Amount} {$detail->TotalDutiesAndTaxes->Currency}<br/>";
					if ($detail->DutiesAndTaxes != null)
					{
						echo "  Duties and Taxes<br/>";
						foreach ($detail->DutiesAndTaxes As $duties)
						{
							echo "    Harmonized code: {$duties->HarmonizedCode}<br/>";
							foreach ($duties->Taxes As $taxes)
							{
								echo "      Name: {$taxes->Name}<br/>";
								echo "      Description: {$taxes->Description}<br/>";
								echo "      Amount: {$taxes->Amount->Amount} {$taxes->Amount->Currency}<br/>";
								echo "      Taxable value: {$taxes->TaxableValue->Amount} {$taxes->TaxableValue->Currency}<br/>";
								if (!empty($taxes->TaxType)) { echo "      Tax type: {$taxes->TaxType}<br/>"; }
								if (!empty($taxes->EffectiveDate))
								{
									echo "      Effective date: {$taxes->EffectiveDate}<br/>";
								}
								echo "      Formula: {$taxes->Formula}<br/>";
							}
						}
					}
					if ( $detail->TotalVariableHandlingCharges != null )
					{
						$charges = $detail->TotalVariableHandlingCharges;
						echo "  Total variable handling charges: {$charges->TotalCustomerCharge->Amount} {$charges->TotalCustomerCharge->Currency}<br/>";
					}
					if ($detail->VariableHandlingCharges != null)
					{
						$charges = $detail->VariableHandlingCharges;
						echo "    Variable Handling details<br/>";
						echo "      Variable handling charge: {$charges->VariableHandlingCharge->Amount} {$charges->VariableHandlingCharge->Currency}<br/>";
						echo "      Percent handling charge: {$charges->PercentVariableHandlingCharge->Amount} {$charges->PercentVariableHandlingCharge->Currency}<br/>";
						echo "      Fixed handling charge: {$charges->FixedVariableHandlingCharge->Amount} {$charges->FixedVariableHandlingCharge->Currency}<br/>";
						echo "      Total customer charge: {$charges->TotalCustomerCharge->Amount} {$charges->TotalCustomerCharge->Currency}<br/>";
					}
					echo "  Total rebates: {$detail->TotalRebates->Amount} {$detail->TotalRebates->Currency}<br/>";
					if ($detail->Rebates != null)
					{
						echo "    Rebates details<br/>";
						foreach ($detail->Rebates As $rebate)
						{
							echo "      Description: {$rebate->Description}<br/>";
							echo "      Amount: {$rebate->Amount->Amount} {$rebate->Amount->Currency}<br/>";
							if (!empty($rebate->RebateType)) { echo "  Type: {$rebate->RebateType}<br/>"; }
							if (!empty($rebate->Percent)) { echo "  Percent: {$rebate->Percent}<br/>"; }
						}
					}
				}
			}
		}
	}
}

function printShipmentOperationalDetails($shipmentOperationalDetail)
{
	if ($shipmentOperationalDetail == null) return;
	echo "<br/>Routing details<br/>";
	echo "URSA prefix {$shipmentOperationalDetail->UrsaPrefixCode} suffix {$shipmentOperationalDetail->UrsaSuffixCode}<br/>";
	if (!empty($shipmentOperationalDetail->CommitDay))
	{
		echo "Service Commitment {$shipmentOperationalDetail->CommitDay}<br/>";
	}
	echo "Airport Id {$shipmentOperationalDetail->AirportId}<br/>";
	if (!empty($shipmentOperationalDetail->DeliveryDay))
	{
		echo "Delivery day {$shipmentOperationalDetail->DeliveryDay}<br/>";
	}
	if (!empty($shipmentOperationalDetail->DeliveryDate))
	{
		echo "Delivery date {$shipmentOperationalDetail->DeliveryDate}<br/>";
	}
	if (!empty($shipmentOperationalDetail->TransitTime))
	{
		echo "Transit time {$shipmentOperationalDetail->TransitTime}<br/>";
	}
}

function printPackageDetails($cpd)
{
	if ($cpd == null) return;
	echo 'Package Details<br/>';
	foreach ($cpd as $detail)
	{
		printTrackingDetails($detail);
		$trackingNumber;
		if(!empty($detail->TrackingIds) && is_array($detail->TrackingIds))
		{
			foreach($detail->TrackingIds As $trackid)
			{
				$trackingNumber = $trackid->TrackingNumber;
				echo "Tracking Number: {$trackingNumber} Type: {$trackid->TrackingIdType} Form Id: {$trackid->FormId}<br/>";
				if (getProperty("printlabels")){ printShipmentLabel($detail->Label, $trackingNumber); }
			}
		}elseif(!empty($detail->TrackingIds))
		{
			$trackingNumber = $detail->TrackingIds->TrackingNumber;
			echo "Tracking Number: {$detail->TrackingIds->TrackingNumber} Type: {$detail->TrackingIds->TrackingIdType} Form Id: {$detail->TrackingIds->FormId}<br/>";
		}
		if (getProperty("printlabels")){ printShipmentLabel($detail->Label, $trackingNumber); }
	}
}

function checkForAsynchronousReply ($notifications)
{
	foreach($notifications As $notification)
	{
		if ($notification->Code == "8954") { return true; }
	}
	return false;
}

function printShipmentLabel($shippingDocument, $trackingNumber)
{

	if ($shippingDocument == null) return;
	$labelName = $shippingDocument->Type;
	$labelFileName = $labelName . "_" . $trackingNumber . "_" . '.pdf';
	$fp = fopen($labelFileName, 'wb');
	if(!empty($shippingDocument->Parts) && is_array($shippingDocument->Parts))
	{
		foreach($shippingDocument->Parts as $sdparts)
		{
			foreach ($sdparts As $sdpart)
			{			
					fwrite($fp, ($sdpart->Image));
			}
		}
	}elseif(!empty($shippingDocument->Parts))
	{
		fwrite($fp, ($shippingDocument->Parts->Image));
	}
	fclose($fp);
	echo 'Label <a href="./'.$labelFileName.'">'.$labelFileName."</a> was generated.<br/><br\>";
}

function printTrackingDetails($completedPackageDetail)
{
	if ($completedPackageDetail == null) return;
	if ($completedPackageDetail->TrackingIds == null) return;
    $trackingids = $completedPackageDetail->TrackingIds;
	if(!empty($trackingIds) && is_array($trackingIds))
	{
		foreach ($trackingIds As $trackingId)
		{
			echo "Tracking # {$trackingId->TrackingNumber} Form ID {$trackingId->FormId}";
		}
	}elseif(!empty($trackingIds))
	{
		echo "Tracking # {$trackingIds->TrackingNumber} Form ID {$trackingId->FormId}";
	}
}
?>