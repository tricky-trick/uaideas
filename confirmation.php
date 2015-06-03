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
        $subject = "Вітаємо Вас на ресурсі \"SeeUA\"";
        $message = "<html>
                    <head>
                        <meta charset=\"UTF-8\">
                    </head>
                    <img src=\"img/logo_see.png\" width=\"50\" height=\"50\" style=\"border-radius: 5px; margin-left: 25%\">
                    <body style=\"background: #e7e9ec; font-family: tahoma, arial, verdana, sans-serif, 'Lucida Sans';\">
                        <div style=\"background: white; width: 50%; height: auto; margin-left: 25%; -webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.75);
                        -moz-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.75);
                        box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.75);\">
                            <table style=\"width:100%\">
                                <tbody style=\"width:100%\">
                                    <tr>
                                        <td align=\"center\" width=\"100\" height=\"10\" style=\"background: darkgray\">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" width=\"100\" style=\"text-align: left;\">
                                            <br>
                            Вітаємо, $user_name. <br> Реєстрація на ресурсі \"SeeUA\" пройшла успішно.<br><br> З повагою, <br> Команда \"SeeUA\".
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" width=\"100\" style=\"background: rgb(0, 169, 213); font-size: 12px; color: lightgoldenrodyellow\">
                                            Всі права захищені. SeeUA © 2015
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </body>
                    </html>";
        $headers .= 'From: no-reply@seeua.com' . "\r\n" .
                    'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail($to, $subject, $message, $headers);
    }
}
header("Location: http://$url");