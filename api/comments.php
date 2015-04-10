<?php
include("../config/sys/config.php");
include("../config/sys/def.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
if(isset($_COOKIE['USER_IN'])) {
    if ($method == "POST") {
        $ideas_id = $_POST['id'];
        $text = addslashes($_POST['text']);
        $author = $_POST['user_id'];

        foreach ($bad_words_array as &$word) {
            $text = preg_replace("/$word/", "*",  $text);
        }

        $sql = "INSERT INTO ideas_comments (ideas_id, text, author) VALUES ($ideas_id, '$text', $author)";

        $is_added = "";
        if (mysql_query($sql, $con)) {
            $is_added = "true";
        } else {
            $is_added = "false";
        }

        $data = (object)array(
            'is_added' => $is_added
        );

        $json = json_encode($data);
        echo $json;
    } elseif ($method == "GET") {
        $ideas_id = $_GET['id'];
        $is_active = $_GET['active'];
        $last = $_GET['last'];

        if ($last == 1) {
            $sql = "SELECT * FROM ideas_comments WHERE ideas_id=" . $ideas_id . " AND is_active=" . $is_active . " ORDER BY date desc LIMIT 1";
        } else {
            $sql = "SELECT * FROM ideas_comments WHERE ideas_id=" . $ideas_id . " AND is_active=" . $is_active;
        }

        $result = mysql_query($sql, $con);

        $count_ideas = mysql_num_rows($result);

        $data = array(
            'count' => $count_ideas
        );

        $array_ideas = array();

        $month_array = array(
            1 => 'Січня',
            2 => 'Лютого',
            3 => 'Березня',
            4 => 'Квітня',
            5 => 'Травня',
            6 => 'Червня',
            7 => 'Липня',
            8 => 'Серпня',
            9 => 'Вересня',
            10 => 'Жовтня',
            11 => 'Листопада',
            12 => 'Грудня',
        );


        while ($row = mysql_fetch_array($result)) {

            $date_array = date_parse($row['date']);

            $array = (object)array(
                'ideas_id' => $ideas_id,
                'text' => $row['text'],
                'author' => $row['author'],
                'datetime' => $date_array['day'] . ' ' . $month_array[$date_array['month']] . ' ' . $date_array['year']
            );

            array_push($array_ideas, $array);
        }

        array_push($data, $array_ideas);

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo $json;
    } elseif ($method == "PUT") {
        $_SERVER['REQUEST_METHOD'] === "PUT" ? parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_PUT) : $_PUT = array();

        $ideas_id = $_PUT['id'];
        $text = addslashes($_PUT['text']);

        $condition = str_replace(" ", ",", trim($condition));

        foreach ($bad_words_array as &$word) {
            $text = preg_replace("/$word/", "*",  $text);
        }

        $sql = "UPDATE ideas_comments SET text='" . $text . "', date=Now() WHERE ideas_id=" . $ideas_id;

        $is_updated = "";
        if (mysql_query($sql, $con))
            $is_updated = "true";
        else {
            $is_updated = "false";
        }

        $data = array(
            "is_updated" => $is_updated
        );

        $json = json_encode($data);

        echo $json;
    }
}