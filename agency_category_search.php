<?php
include 'config.php';

$term=$_REQUEST['term'];
//$term = trim(strip_tags($_GET['agency_category']));
//echo '<script type="text/javascript">alert(1);</script>';
if (isset($term)){
        $return_arr = array();
        
        $query=  mysql_query("SELECT agency_category FROM organization WHERE agency_category LIKE '%".$term."%' ");
        while ($row=  mysql_fetch_array($query)){
             //$return_arr[] =  $row['agency_category'];
             $return_arr[] = array('label' => $row['agency_category']);
        }
        echo json_encode($return_arr);
        
}

