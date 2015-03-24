<?php

include 'vantiv_curl.php';

$array_openAcc = array(
    'Credentials' => array('AccountId' => '1506', 'Token' => 'a8d16d40-81e3-4c4f-b10d-2c264f0f96b4'),
    'Request' => array(
        'Address' => array('Address1' => 'New York University',
                        'Address2' => '70 Washington Square S',
                        'City' => 'New York',
                        'PostalCode' => '10012',
                        'Country' => 'United States',
                        'State' => 'New York'
        ),
        'Answers' => array(
            'Answer' => 'this is answer',
            'QuestionType' => '1'
        ),
        'CardId' => '243',
        'CardholderIdInfo' => array(
            'IdIssuer' => '243',
            'IdNumber' => '123',
            'IdType' => '0'
        ),
        'Dob' => '\/Date(928164000000-0400)\/',
        'DocumentImage' => array(
            '81',
            '109',
            '70',
            '122',
            '90',
            '83',
            '65',
            '50',
            '78',
            '67',
            '66',
            '84',
            '100',
            '72',
            '74',
            '108',
            '89',
            '87',
            '48',
            '61'
            ),
        'Email' => 'RCarpenter@theciviccard.com',
        'FirstName' => 'Robert',
        'IdentityValidationKey' => 'a8d16d40-81e3-4c4f-b10d-2c264f0f96b4',
        'LastName' => 'Carpenter',
        'PhoneNumber' => '(123) 456-8910',
        'ProductId' => '145'
    )
);

$json_arr = json_encode($array_openAcc);

$curl = new Vantiv_curl();

echo $curl->ValidateCardholderIdentity($json_arr);
?>
