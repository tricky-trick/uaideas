<?php
include("../config/sys/config.php");
include("../config/sys/def.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
if(isset($_COOKIE['USER_IN']) || isset($_COOKIE['USER_OFF'])) {
    if ($method == "POST") {
        $email = $_POST['email'];
        $password = addslashes($_POST['password']);
        $name = addslashes($_POST['name']);
        $mail = addslashes($_POST['mail']);

        if ($password == "temp"){
            $temp = rand(1000000, 9999999);
            $password = "temp$temp";
        }

        $password = hash("sha256", $password);

        $user_code = rand(1000000, 9999999);
        $fake_code = rand(1000000000000, 99999999999999);

        $sql = "INSERT INTO users (mail, password, name, code) VALUES ('$email', '$password', '$name', $user_code)";

        $is_created = "";
        if (mysql_query($sql, $con)) {
            if($mail !== "no") {
                $to = $email;
                $subject = "Реєстрація нового користувача";
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
                                                Вітаємо Вас з реєстрацією на ресурсі SeeUA.
                                                        <br>
                                                Будь-ласка, <a href=\"http://$url/confirmation.php?email=$email&code=$user_code&scode=$fake_code\">активуйте</a> Ваш обліковий запис<br><br> З повагою,<br>Команда \"SeeUA\".
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

            $is_created = "true";
        } else {
            $is_created = "false";
        }

        $data = (object)array(
            'is_created' => $is_created
        );

        $json = json_encode($data);
        echo $json;
    }

    if ($method == "GET") {
        $id = $_GET['user_id'];
        $mail = $_GET['email'];
        $name = $_GET['name'];
        $is_banned = $_GET['ban'];
        $is_confirmed = $_GET['confirm'];

        if ($id != "")
            $condition .= " and id=" . $id;
        if ($mail != "")
            $condition .= " and mail='" . $mail . "'";
        if ($name != "")
            $condition .= " and name='" . $name . "'";
        if ($is_banned != "")
            $condition .= " and is_banned=" . $is_banned;
        if ($is_confirmed != "")
            $condition .= " and is_confirmed=" . $is_confirmed;

        $sql = "SELECT * FROM users WHERE 1 " . $condition;

        $result = mysql_query($sql, $con);
        $array_users = array();
        $data = array(
            'count' => mysql_num_rows($result)
        );
        while ($row = mysql_fetch_array($result)) {
            $array = array(
                'id' => $row['id'],
                'email' => $row['mail'],
                'name' => $row['name'],
                'created_ideas' => $row['created_ideas'],
                'responsible_ideas' => $row['responsible_ideas'],
                'liked_ideas' => $row['liked_ideas'],
                'date' => $row['date'],
                'is_confirmed' => $row['is_confirmed'],
                'is_banned' => $row['is_banned']

            );

            array_push($array_users, $array);
        }

        array_push($data, $array_users);


        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        echo $json;
    }

    if ($method == "PUT") {
        $_SERVER['REQUEST_METHOD'] === "PUT" ? parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_PUT) : $_PUT = array();

        $id = $_PUT['id'];
        $email = $_PUT['mail'];
        $name = addslashes($_PUT['name']);
        $created_ideas = $_PUT['created_ideas'];
        $responsible_ideas = $_PUT['responsible_ideas'];
        $liked_ideas = $_PUT['liked_ideas'];
        $confirm = $_PUT['confirm'];
        $password = $_PUT['password'];
        $ban = $_PUT['ban'];

        $condition = "";

        if ($name != "")
            $condition .= ", name='" . $name . "'";
        if ($created_ideas != "")
            $condition .= ", created_ideas='" . $created_ideas . "'";
        if ($responsible_ideas != "")
            $condition .= ", responsible_ideas='" . $responsible_ideas . "'";
        if ($liked_ideas != "no")
            $condition .= ", liked_ideas='" . $liked_ideas . "'";
        if ($confirm != 0)
            $condition .= ", is_confirmed=" . $confirm;
        if ($password != "")
            $condition .= ", password='" . hash("sha256", $password) . "'";
        if ($ban != "")
            $condition .= ", is_banned=" . $ban;

        if($email != "")
        {
            $sql = "UPDATE users SET mail='". $email. "'" . $condition . ", date=Now() WHERE mail='". $email ."'";
        }

        elseif($id != ""){
            $sql = "UPDATE users SET id=". $id . $condition . ", date=Now() WHERE id=". $id;
        }


        //echo $sql;

        $is_updated = "";
        if (mysql_query($sql, $con))
            $is_updated = "true";
        else {
            $is_updated = "false";
        }

        $data = array(
            "is_updated" => $is_updated
        );

        if($password != ""){
            $to = $email;
            $subject = "Зміна/відновлення паролю";
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
                                                        Ваш пароль щойно був поміняний. Новий пароль $password <br><br> З повагою,<br>Команда \"SeeUA\".
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

        $json = json_encode($data);

        echo $json;

    }

}