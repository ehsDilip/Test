<?php
ob_start();
session_start();
//error_reporting (E_ALL ^ E_NOTICE);
error_reporting (1);
date_default_timezone_set('Asia/Calcutta');

define("RECORDS_PER_PAGE",20);
define("SITE_EMAIL", "administrator@gmail.com");
define("SITE_EMAIL_ADMIN", "Administrator");
define("SITE_NAME", "example.com");
define("FILE_NAME", basename($_SERVER['PHP_SELF']));

define("SITE_URL","http://localhost/test/Theme/");
//define("SITE_URL","http://eheuristic.com/newehs/");
define("SITE_URL_ADMIN",SITE_URL."admin/");
define("SITE_URL_CSS",SITE_URL."css/");
define("SITE_URL_IMAGES",SITE_URL."img/");
define("SITE_URL_IMAGES_SLIDER",SITE_URL_IMAGES."slider-img/");
define("SITE_URL_IMAGES_FLAGES",SITE_URL_IMAGES."flags/");
define("SITE_URL_IMAGES_PORTFOLIO",SITE_URL_IMAGES."portfolio/");
define("SITE_URL_IMAGES_BG",SITE_URL_IMAGES."bg/");
define("SITE_URL_IMAGES_SCREEN",SITE_URL_IMAGES."screen/");
define("SITE_URL_IMAGES_WEB",SITE_URL_IMAGES."web/");
define("SITE_URL_IMAGES_MOBILE",SITE_URL_IMAGES."mobile/");
define("SITE_URL_IMAGES_RIBBON",SITE_URL_IMAGES."ribbon/");
define("SITE_URL_IMAGES_LOGO",SITE_URL_IMAGES."logo/");
define("SITE_URL_IMAGES_TABBOX",SITE_URL_IMAGES."tabbox/");
define("SITE_URL_INCLUDE",SITE_URL."include/");
define("SITE_URL_FUNCTION",SITE_URL_INCLUDE."function/");
define("SITE_URL_JS",SITE_URL."js/");
define("SITE_URL_TEMPLATE",SITE_URL."template/");
define("SITE_URL_TEM_ADMIN",SITE_URL_TEMPLATE."admin/");
define("SITE_URL_TEM_ADM_CONTENT",SITE_URL_TEM_ADMIN."content/");
define("SITE_URL_TEM_CONTENT",SITE_URL_TEMPLATE."content/");

// define SITE PATH
define("SITE_PATH","/home/eheuristic7/php/test/Theme/");
//define("SITE_PATH","/home/eheurist/public_html/newehs/");
define("SITE_PATH_ADMIN",SITE_PATH."admin/");
define("SITE_PATH_CSS",SITE_PATH."css/");
define("SITE_PATH_IMAGES",SITE_PATH."img/");
define("SITE_PATH_IMAGES_SLIDER",SITE_PATH_IMAGES."slider-img/");
define("SITE_PATH_IMAGES_FLAGES",SITE_PATH_IMAGES."flags/");
define("SITE_PATH_IMAGES_PORTFOLIO",SITE_PATH_IMAGES."portfolio/");
define("SITE_PATH_IMAGES_BG",SITE_PATH_IMAGES."bg/");
define("SITE_PATH_IMAGES_SCREEN",SITE_PATH_IMAGES."screen/");
define("SITE_PATH_IMAGES_RIBBON",SITE_PATH_IMAGES."ribbon/");
define("SITE_PATH_IMAGES_LOGO",SITE_PATH_IMAGES."logo/");
define("SITE_PATH_IMAGES_WEB",SITE_PATH_IMAGES."web/");
define("SITE_PATH_IMAGES_MOBILE",SITE_PATH_IMAGES."mobile/");
define("SITE_PATH_IMAGES_TABBOX",SITE_PATH_IMAGES."tabbox/");
define("SITE_PATH_INCLUDE",SITE_PATH."include/");
define("DIR_FS_INCLUDES",SITE_PATH."include/");
define("SITE_PATH_INC_FUNCTION",SITE_PATH_INCLUDE."function/");
define("SITE_PATH_JS",SITE_PATH."js/");
define("SITE_PATH_TEMPLATE",SITE_PATH."template/");
define("SITE_PATH_TEM_ADMIN",SITE_PATH_TEMPLATE."admin/");
define("SITE_PATH_TEM_ADM_CONTENT",SITE_PATH_TEM_ADMIN."content/");
define("SITE_PATH_TEM_CONTENT",SITE_PATH_TEMPLATE."content/");

include SITE_PATH_INCLUDE."tables.php";
include SITE_PATH_INCLUDE."messages.php";

include SITE_PATH_INC_FUNCTION."function.php";
include SITE_PATH_INC_FUNCTION."sitefunction.php";
//include SITE_PATH_INC_FUNCTION."sitefunction.php";

define("HOSTNAME","localhost");
define("USERNAME","root");
define("PASSWORD","");
define("DATABASE","eheurist_newehs");

//Update DB Connection in ds.pdo.php file also

//define("HOSTNAME","localhost");
//define("USERNAME","eheurist_nehs");
//define("PASSWORD","ehs1984");
//define("DATABASE","eheurist_eheuristic");

//db_connection(HOSTNAME, DATABASE, USERNAME, PASSWORD);



//include_once 'db.pdo.php';



?>
