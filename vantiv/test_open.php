<?php
include 'vantiv_curl.php';
error_reporting(E_ALL);
$arr = array(
        'Credentials'=>array('AccountId'=>'1506','Token'=>'a8d16d40-81e3-4c4f-b10d-2c264f0f96b4'),
        'Request'=>array(
            'AccountType'=>'1',
            'AffiliateAcro'=>'vantivcard',
            'CorrelationId'=>'1627aea5',
            'FeeType'=>'1',
            'IntegrationDetail'=>array('Name'=>'Robert','Value'=>'Carpenter'),
            'OpenAccountItems'=>array(
                'Address'=>array('AddressLine1'=>'New York University',
                                'AddressLine2'=>'70 Washington Square S',
                                'City'=>'New York',
                                'Country'=>'United States',
                                'PostalCode'=>'10012',
                                'State'=>'New York'
                            ),
                'CardId'=>'243',
                'CardNumber'=>'243',
                'Cardholder'=>array('CardId'=>'243',
                                        'CardholderIdInfo'=>array(
                                            'IdIssuer'=>'243',
                                            'IdNumber'=>'123',
                                            'IdType'=>'0'
                                        ),
                                    'DOB'=>'\/Date(928164000000-0400)\/',
                                    'EmailAddress'=>'RCarpenter@theciviccard.com',
                                    'FirstName'=>'Robert',
                                    'IntegrationDetail'=>array('Name'=>'Robert','Value'=>'Carpenter'),
                                    'LastName'=>'Carpenter',
                                    'Phone'=>'(123) 123-1234',
                                    'SSN'=>'721-07-4426'
                                ),
                'IdentityValidationKey'=>'1627aea5-8e0a-4371-9022-9b504344e724',
                'ProductId'=>'145',
                'ValidateIdentity'=>'true'
            ),
            )
        );
$json_arr = json_encode($arr);


$curl = new Vantiv_curl();
//echo 'curl started';
echo $curl->OpenAccount($json_arr);

?>
