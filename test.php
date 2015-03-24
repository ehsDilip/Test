<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include './world_cities_array.php';
//$json_arr = json_encode($json);

foreach ($cities_array as $val) {
    echo $val['city'] . '<br>';
}
//echo "<pre>";
//print_r($cities_array);
