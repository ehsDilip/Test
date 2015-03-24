<?php
error_reporting(0);
/**
 * Database config variables
 */
//define("DB_HOST", "localhost");
//define("DB_USER", "root");
//define("DB_PASSWORD", "");
//define("DB_DATABASE", "pfp");

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'pfp';

$connect = mysql_connect($host, $user, $password) or die('Connection not established.' . mysql_error());
mysql_select_db($db) or die('The database not establieshed.' . mysql_error());
/*
 * Google API Key
 */
//define("GOOGLE_API_KEY", "AIzaSyC4iGdPQMAInkDHU3irxhxBaZtYLe2WWBU"); // Place your Google API Key
?>

<?php
extract($_POST);
    //echo "<pre>";
    
include_once 'GCM.php';
$gcm = new GCM();
$result = mysql_query("select gcm_id FROM user_master");

while ($row = mysql_fetch_array($result)) {
//    echo "<pre>";
//    print_r($row);
    
    $regId = $row['gcm_id'];
    if ($regId != '' ) {
            echo "<pre>";
    print_r($row);
    echo $regId;
    
        $registatoin_ids = array($regId);
        $message = array("message" => "afdfd");

        $result = $gcm->send_notification($registatoin_ids, $message);
    }
}
?>
