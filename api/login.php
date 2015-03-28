<?php
include("config.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];

if($method == "POST") {
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);

    $password = hash("sha256", $password);

    $sql = "SELECT * FROM users WHERE mail='".$email."' AND password='".$password."'";

    $result = mysql_query($sql, $con);

    $is_logged_in = "";

    if(mysql_num_rows($result) == 1){
        $is_logged_in = "true";
    }

    else{
        $is_logged_in = "false";
    }

    $data = array(
        'is_logged_in' => $is_logged_in
    );

    echo json_encode($data);
}