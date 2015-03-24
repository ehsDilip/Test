<?php
//if (isset($_GET['lic'])) {
    //$lic = $_GET['lic'];

    $host = 'localhost';
    $user = 'eheurist_world';
    $password = 'l-ED=p!6?DiW';
    $db = 'eheurist_world';

    define('CITY_TABLE', 'dow_cities');
    define('REGION_TABLE', 'dow_region');
    define('COUNTRY_TABLE', 'dow_country');

    $connect = mysql_connect($host, $user, $password) or die('Connection not established.' . mysql_error());
    mysql_select_db($db) or die('The database not establieshed.' . mysql_error());
//}

function get_all_ehs_country() {

    $country_qur = "SELECT countryId,name
    FROM " . COUNTRY_TABLE . "
    ORDER BY name
    ";

    $country = mysql_query($country_qur);

    if (mysql_num_rows($country) > 0) {
        while ($row = mysql_fetch_array($country)) {
            $result_country[] = array("id" => $row['countryId'], "name" => $row['name']);
        }
        return $result_country;
    } else {
        return FALSE;
    }
}

function fetch_ehs_region($country_id_array) {

    if (count($country_id_array) > 0) {
        $countries_id = implode(',', $country_id_array);

        $region_qur = "SELECT regionId,name
        FROM " . REGION_TABLE . "
        WHERE countryId IN (" . $countries_id . ")
        ORDER BY name";

        $region = mysql_query($region_qur);

        if (mysql_num_rows($region) > 0) {
            while ($row = mysql_fetch_array($region)) {
                $result_region[] = array("id" => $row['regionId'], "name" => $row['name']);
            }
            return json_encode(array("result" => $result_region));
        } else {
            return FALSE;
        }
    }
}

function fetch_ehs_city($region_id_array) {
    if (count($region_id_array) > 0) {
        foreach ($region_id_array as $region) {

            $city_qur = "SELECT r.name as region_name , c.name as city_name
                    FROM " . CITY_TABLE . " as c ," . REGION_TABLE . " as r
                    WHERE c.regionId =" . $region . " and c.regionId = r.regionId";

            $city = mysql_query($city_qur);
            if (mysql_num_rows($city) > 0) {
                while ($row = mysql_fetch_array($city)) {
                    $result_city[] = array("region_name" => $row['region_name'], "city_name" => $row['city_name']);
                }
            }
        }
        echo json_encode(array("result" => $result_city));
    }
}

$all_country = get_all_ehs_country();
echo "<pre>";
print_r($all_country);

//$region= fetch_ehs_region(array(1,2));
//echo "<pre>";
//print_r($region);

//$city= fetch_ehs_city(array(1,2));
//echo "<pre>";
//print_r($city);