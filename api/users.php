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
                $message = "Будь-ласка, підтвердіть Вашу реєстрацію на сайті seeua.com, перейшовши за вказаною адресою http://$url/confirmation.php?email=$email&code=$user_code&scode=$fake_code<br><br> З повагою,<br>Команда \"Побачити Україну\".";
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
            $message = "Ваш пароль щойно був поміняний. Новий пароль $password <br><br> З повагою, <br> Команда \"Побачити Україну\".";
            $headers .= 'From: no-reply@seeua.com' . "\r\n" .
                        'Content-type: text/html; charset=UTF-8' . "\r\n";
            mail($to, $subject, $message, $headers);
        }

        $json = json_encode($data);

        echo $json;

    }

}