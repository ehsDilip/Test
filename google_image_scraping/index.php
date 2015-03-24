<?php
ob_start();
//include class file
include("./GoogleImages.php");

$gimage = new GoogleImages();

$a=$gimage->get_images("Android");
//echo "<pre>";
//print_r($a);
//foreach ($a as $value) {
//    echo $value['unescapedUrl'].'<br>';
//}

?>