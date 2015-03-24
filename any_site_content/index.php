<?php
$url='http://codex.wordpress.org/Function_Reference/wp_update_post';
//function str_get_dom($str, $lowercase = true) {
//    $dom = new simple_html_dom;
//    $dom->load($str, $lowercase);
//    return $dom;
//}
//error_reporting(0);
/*
include 'simple_html_dom.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1");
curl_setopt($ch, CURLOPT_URL, "http://www.wpexplorer.com/manage-wordpress-posts-php/");
$cookie_file_path = "cookie.txt"; // Please set your Cookie File path	
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

$response = curl_exec($ch);

$html = str_get_dom($response);
echo "<pre>";
print_r($html);
$items = array();
*/
//foreach ($html->find('table') as $table) {
//
//    foreach ($table->find('tr') as $tr) {
//
//        foreach ($tr->find('td') as $td) {
//
//
//            echo "asdf";
//
//            $text = $td->plaintext;
//            echo $text; // Array
//        }
//    }
//}


require('simple_html_dom.php');

$table = array();

$html = file_get_html($url);
foreach($html->find('tr') as $row) {
    $artist = $row->find('td',0)->plaintext;
//    $artist = $row->find('td',1)->plaintext;
//    $title = $row->find('td',2)->plaintext;

    $table[$artist] = true;
}

echo '<pre>';
print_r($table);
echo '</pre>';

?>