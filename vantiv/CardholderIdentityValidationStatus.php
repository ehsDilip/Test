<?php
include 'vantiv_curl.php';
//error_reporting(E_ALL);
$arr = array(
        'Credentials'=>array('AccountId'=>'1506','Token'=>'a8d16d40-81e3-4c4f-b10d-2c264f0f96b4'),
        'Request'=>array(
                'IdentityValidationKey'=>'1627aea5-8e0a-4371-9022-9b504344e724'
            )
        );
$json_arr = json_encode($arr);


$curl = new Vantiv_curl();
//echo 'curl started';
echo $curl->GetCardholderIdentityValidationStatus($json_arr);

?>
