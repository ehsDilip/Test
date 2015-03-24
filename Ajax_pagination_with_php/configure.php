<?php
/*
@author: Shahrukh Khan
@website: http://www.thesoftwareguy.in
@date published: 1st December, 2013
*/
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "demos");

//connect with the server
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Sorry! could not connect <br><b>". mysql_error()."</b>");
if ($conn) {
	mysql_select_db(DB_DATABASE) or die("Sorry! could not connect with database <br><b>". mysql_error()."</b>");
}
?>