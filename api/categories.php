<?php
include("config.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];

if(isset($_COOKIE['USER_IN'])) {
    if ($method == "GET") {
        $condition = "";

        $name = $_GET['category_name'];
        if ($name != "")
            $condition = " AND name='" . $name . "'";

        $sql = "SELECT * FROM categories WHERE 1" . $condition;

        $result = mysql_query($sql, $con);

        $region_id;

        $data = array();

        while ($row = mysql_fetch_array($result)) {
            $category_id = $row['id'];
            $name = $row['name'];

            $array = array(
                'category_id' => $category_id,
                'name' => $name
            );

            array_push($data, $array);
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        echo $json;
    }
}