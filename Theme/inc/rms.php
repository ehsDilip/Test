<?php    
ob_start();
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

date_default_timezone_set ("Asia/Calcutta");
define("SCRIPTNAME", basename($_SERVER['PHP_SELF']));
define("ADMINTITLE", "");
define("ADMIN_MAIL", "example@gmail.com");
session_start();

// Site URL
define("SITE_URL","http://localhost/test/Theme/");
define("SITE_URL_JS",SITE_URL."js/");
define("SITE_URL_CSS",SITE_URL."css/");
define("SITE_URL_INCLUDE",SITE_URL."inc/");
define("SITE_URL_IMG",SITE_URL."img/");
define("SITE_URL_IMG_SLIDER",SITE_URL_IMG."slider-img/");

define("SITE_URL_ADMIN",SITE_URL."admin/");
define("SITE_URL_ADMIN_JS",SITE_URL_ADMIN."js/");
define("SITE_URL_ADMIN_CSS",SITE_URL_ADMIN."css/");
define("SITE_URL_ADMIN_IMG",SITE_URL_ADMIN."img/");
/*
define("SITE_URL_TEMPLATE",SITE_URL."template/");
define("SITE_URL_TEMPLATE_ADMIN",SITE_URL_TEMPLATE."admin/");
define("SITE_URL_TEMPLATE_ADMIN_CONTENT",SITE_URL_TEMPLATE_ADMIN."content/");
define("SITE_URL_TEMPLATE_CONTENT",SITE_URL_TEMPLATE."content/");
*/

// Site PATH
define("SITE_PATH","/home/eheuristic7/php/test/Theme/");
define("SITE_PATH_JS",SITE_PATH."js/");
define("SITE_PATH_CSS",SITE_PATH."css/");
define("SITE_PATH_INCLUDE",SITE_PATH."inc/");
define("SITE_PATH_IMG",SITE_PATH."img/");
define("SITE_PATH_IMG_SLIDER",SITE_PATH_IMG."slider-img/");

define("SITE_PATH_ADMIN",SITE_PATH."admin/");
define("SITE_PATH_ADMIN_JS",SITE_PATH_ADMIN."js/");
define("SITE_PATH_ADMIN_CSS",SITE_PATH_ADMIN."css/");
define("SITE_PATH_ADMIN_IMG",SITE_PATH_ADMIN."img/");
/*
define("SITE_PATH_TEMPLATE",SITE_PATH."template/");
define("SITE_PATH_TEMPLATE_ADMIN",SITE_PATH_TEMPLATE."admin/");
define("SITE_PATH_TEMPLATE_ADMIN_CONTENT",SITE_PATH_TEMPLATE_ADMIN."content/");
define("SITE_PATH_TEMPLATE_CONTENT",SITE_PATH_TEMPLATE."content/");
*/
include SITE_PATH_INCLUDE."tables.php"; 
include SITE_PATH_INCLUDE."messages.php";
include SITE_PATH_INCLUDE."class.pdo.php"; 
include SITE_PATH_INCLUDE."class.extra.php"; 
include SITE_PATH_INCLUDE."pwd.class.php";

function pre($param) {
  echo "<pre>";
  print_r($param);
  echo "</pre>";
}
?>
