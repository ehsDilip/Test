<?php


$lines = file('http://www.if-not-true-then-false.com/2010/php-calculate-real-differences-between-two-dates-or-timestamps/');
foreach ($lines as $line_num => $line) { 
	// loop thru each line and prepend line numbers
	echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include './world_cities_array.php';
////$json_arr = json_encode($json);
//
//foreach ($cities_array as $val) {
//    echo $val['city'] . '<br>';
//}
//echo "<pre>";
//print_r($cities_array);
