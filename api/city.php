<?php
include("../config/sys/config.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];
if(isset($_COOKIE['USER_IN'])) {
    if ($method == "GET") {
        $region_id = $_GET['region_id'];
        $name = $_GET['city_name'];

        $sql = "SELECT * FROM city WHERE region_id=" . $region_id . " AND name='" . $name . "'";

        $result = mysql_query($sql, $con);

        $city_id;

        while ($row = mysql_fetch_array($result)) {
            $city_id = $row['id'];
        }

        $data = array(
            'city_id' => $city_id
        );

        $json = json_encode($data);

        echo $json;
    }
}