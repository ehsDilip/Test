<?php
include 'google.scraper.class.php';
$obj=new GoogleScraper();
$arr=$obj->getUrlList('php','200.123.187.165:8080');
echo "<pre>";
print_r($arr);
?>