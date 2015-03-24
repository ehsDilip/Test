<?php
$search='card';
$url = file_get_contents('http://www.pinterest.com/search/pins/?q='.$search);
$images= '@<div class="hoverMask"></div>(.*?)<img src="(.*?)"@si';

preg_match_all($images,$url,$get_images);
//echo "<pre>";
//print_r($get_images[2]);

//for($i=0;$i<5;$i++){
//    echo $get_images[2][$i];
//    echo "<br>";
//}
?>

