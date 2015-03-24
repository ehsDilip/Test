<?php

if (isset($_GET['lic']) && $_GET['lic'] != '') {
    $lic = $_GET['lic'];

    $host = 'eheuristic.com';
    $user = 'eheurist_world';
    $password = 'l-ED=p!6?DiW';
    $db = 'eheurist_world';

    define('CITY_TABLE', 'cities');
    define('REGION_TABLE', 'region');
    define('COUNTRY_TABLE', 'country');
    define('LICENSE_TABLE', 'ehs_license');

    $connect = mysql_connect($host, $user, $password) or die('Connection not established.' . mysql_error());
    mysql_select_db($db) or die('The database not establieshed.' . mysql_error());

    $lic_qur = "SELECT *
    FROM " . LICENSE_TABLE . " WHERE lic='" . $lic . "'";

    $_lic = mysql_query($lic_qur);

    if (mysql_num_rows($_lic) > 0) {
        $lic_row = mysql_fetch_array($_lic);
        $reg_lic = $lic_row['lic'];
        if ($reg_lic != $lic) {
            $result_array[] = array("result" => 'no', "auth" => "License key not match.");
            echo json_encode($result_array);
            mysql_close($connect);
            exit();
        }
    } else {
        $result_array[] = array("result" => 'no', "auth" => "License key not available.");
        echo json_encode($result_array);
        mysql_close($connect);
        exit();
    }
} else {
    $result_array[] = array("result" => 'no', "auth" => "License key not available.");
    echo json_encode($result_array);
    mysql_close($connect);
    exit();
}

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
        return json_encode($result_country);
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

if (isset($_GET['data'])) {
    $data = $_GET['data'];
    if ($data == 1) {
        echo get_all_ehs_country();
    }
    if ($data == 2) {
        $countries = $_GET['countries'];
        if ($countries != '') {
            $countries_id = explode(',', $countries);
            echo fetch_ehs_region($countries_id);
        }
    }
    if ($data == 3) {
        $region = $_GET['region'];
        if ($region != '') {
            $region_id = explode(',', $region);
            echo fetch_ehs_city($region_id);
        }
    }
}
mysql_close($connect);
