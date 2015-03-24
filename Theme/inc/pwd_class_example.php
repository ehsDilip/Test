<?php 
require_once "pwd.class.php";

echo $passGen->generate() . "<br>";

// Just using lowercase and numeric chars
$passGen->configure(false, true, true, false, false);

echo $passGen->generate() . "<br>";
?>