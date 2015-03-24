<?php
ob_start();
error_reporting(E_ALL ^ E_NOTICE);
session_start();

date_default_timezone_set('Asia/Calcutta');

define("SITE_URL", "http://localhost/PFP/");
define("SITE_URL_ADMIN", SITE_URL . "admin/");
define("SITE_URL_IMAGES", SITE_URL_ADMIN . "img/");

define("SITE_PATH", "/home/eheuristic7/php/PFP/");
define("SITE_PATH_ADMIN", SITE_PATH . "admin/");
define("SITE_PATH_IMAGES", SITE_PATH_ADMIN . "img/");

$url = basename($_SERVER["SCRIPT_NAME"]);

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'pfp';

$connect = mysql_connect($host, $user, $password) or die('Connection not established.' . mysql_error());
mysql_select_db($db) or die('The database not establieshed.' . mysql_error());
?>
