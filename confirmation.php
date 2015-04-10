<?php
header('Content-Type: text/html; charset=utf-8');
include("config/sys/config.php");
include("config/sys/def.php");
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $code=$_GET["code"];
    $email=$_GET["email"];
    $sql="SELECT * FROM users WHERE mail='$email' and code=$code";
    $result=mysql_query($sql, $con);
    $count=mysql_num_rows($result);
    if($count==1){
        $user_name = mysql_result($result, 0, "name");


        $sql_confirm = "UPDATE users SET is_confirmed = 1 where mail = '$email'";
        mysql_query($sql_confirm,$con);
        $to = $email;
        $subject = "Вітаємо Вас на ресурсі \"Побачити Україну\"";
        $message = "Вітаємо, $user_name. <br> Реєстрація на ресурсі \"Побачити Україну\" пройшла успішно.<br><br> З повагою, <br> Команда \"Побачити Україну\".";
        $headers .= 'From: no-reply@seeua.com' . "\r\n" .
                    'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail($to, $subject, $message, $headers);
    }
}
header("Location: http://$url");