<?php

include '../original_ehs.php';

//$all_country=  get_all_ehs_country();
//echo "<pre>";
//print_r($all_country);
//$region= fetch_ehs_region(array(1,2));
//echo "<pre>";
//print_r($region);
//$city= fetch_ehs_city(array(1,2));
//echo "<pre>";
//print_r($city);

$url = "http://eheuristic.com/getregion.php?lic=64c4a6db2f68f29a33d8136675a46546&data=3&region=1,2,3";
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POST, 0);
//$response = curl_exec($ch);
//
////print_r(curl_getinfo($ch));
//$response = curl_exec($ch);
////    echo $html = str_get_dom($response);
//echo "<pre>";
//print_r(json_decode($response,true));


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        echo $response = curl_exec($ch);

        