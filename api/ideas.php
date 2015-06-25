<?php
include("../config/sys/config.php");
include("../config/sys/def.php");
header ("Content-Type: text/html; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
if(isset($_COOKIE['USER_IN'])) {
    if ($method == "POST") {
        $region = $_POST['region_id'];
        $city = $_POST['city_id'];
        $coord = $_POST['coordinates'];
        $category = $_POST['category'];
        $author = $_POST['user_id'];
        $subject = addslashes($_POST['subject_text']);
        $description = addslashes($_POST['description_text']);
        $files = addslashes($_POST['files']);

        foreach ($bad_words_array as &$word) {
            $description = preg_replace("/$word/", "*",  $description);
            $subject = preg_replace("/$word/", '*',  $subject);
        }

        $sql = "INSERT INTO ideas (region, city, coord, category, author, subject, description, files) VALUES ($region, $city, '$coord', $category, $author, '$subject', '$description', '$files')";

        $is_created = "";
        if (mysql_query($sql, $con)) {
            $is_created = "true";
        } else {
            $is_created = "false";
        }

        $data = (object)array(
            'is_created' => $is_created
        );

        $json = json_encode($data);
        echo $json;
    } elseif ($method == "GET") {
        $condition = "";

        $id = $_GET['id'];
        $region = $_GET['region_id'];
        $city = $_GET['city_id'];
        $author = $_GET['user_id'];
        $keyword = $_GET['keyword'];
        $is_implemented = $_GET['is_ready'];
        $category_id = $_GET['category_id'];
        $sort_by = $_GET['sort_by'];
        $limit = $_GET['limit'];
        $show_all = $_GET['all'];
        $deleted = $_GET['deleted'];

        $is_deleted = "is_deleted=0";

        if ($sort_by == 0) {
            $sort_by = "date";
        }
        if ($sort_by == 1) {
            $sort_by = "rating";
        }

        $cityJoin = "";
        if($city != ""){
            $cityJoin = "INNER JOIN city ON city.id= ideas.city and city.name='$city'";
        }

        if ($id != "")
            $condition .= " and id=" . $id;
        if ($region != 0)
            $condition .= " and region=" . $region;
        if ($author != "")
            $condition .= " and author=" . $author;
        if ($is_implemented != "")
            $condition .= " and is_implemented=" . $is_implemented;
        if ($keyword != "")
            $condition .= " and (subject like '%".$keyword."%' or description like '%".$keyword."%')";
        if ($category_id != 0)
            $condition .= " and category=" . $category_id;
        if ($deleted != null)
            $condition .= " and is_deleted=" . $deleted;
        if($show_all != null){
            $is_deleted = "1=1";
        }

        if ($id != "") {
            $sql = "SELECT * FROM ideas WHERE $is_deleted" . $condition . " ORDER BY " . $sort_by;
        }
        elseif($limit == ""){
            $sql = "SELECT * FROM ideas $cityJoin WHERE $is_deleted" . $condition;
        }
        else {
            $sql = "SELECT * FROM ideas $cityJoin WHERE $is_deleted" . $condition . " ORDER BY " . $sort_by . " DESC LIMIT " . $limit;
        }

        //echo $sql;

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

            $id = $row['id'];
            $region = $row['region'];
            $city = $row['city.id'];
            $coord = $row['coord'];
            $category = $row['category'];
            $author = $row['author'];
            $subject = $row['subject'];
            $description = $row['description'];
            $responsible = $row['responsible'];
            $rating = $row['rating'];
            $date = $row['date'];
            $is_implemented = $row['is_implemented'];
            $is_deleted = $row['is_deleted'];
            $files = $row['files'];

            $date_array = date_parse($date);

            $array = (object)array(
                'id' => $id,
                'region' => $region,
                'city' => $city,
                'coord' => $coord,
                'category' => $category,
                'author' => $author,
                'rating' => $rating,
                'is_implemented' => $is_implemented,
                'date' => $date_array['day'] . ' ' . $month_array[$date_array['month']] . ' ' . $date_array['year'],
                'subject' => $subject,
                'files' => $files,
                'description' => $description,
                'responsible' => $responsible,
                'is_deleted' => $is_deleted
            );

            array_push($array_ideas, $array);
        }

        array_push($data, $array_ideas);

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo $json;
    } elseif ($method == "PUT") {
        $_SERVER['REQUEST_METHOD'] === "PUT" ? parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $_PUT) : $_PUT = array();

        $condition = "";

        $id = $_PUT['id'];
        $rating = $_PUT['rating_val'];
        $responsible = $_PUT['responsible_val'];
        $subject = addslashes($_PUT['subject_text']);
        $description = addslashes($_PUT['description_text']);
        $category = $_PUT['category_id'];
        $is_deleted = $_PUT['delete'];
        $files = $_PUT['files'];
        $coord = $_PUT['coord'];

        foreach ($bad_words_array as &$word) {
            $description = preg_replace("/$word/", "*",  $description);
        }

        if ($rating != "")
            $condition .= ", rating=" . $rating;
        if ($responsible != "")
            $condition .= ", responsible=" . $responsible;
        if ($subject != "")
            $condition .= ", subject='" . $subject. "'";
        if ($description != "")
            $condition .= ", description='" . $description. "'";
        if ($category != "")
            $condition .= ", category=" . $category;
        if ($is_deleted != "")
            $condition .= ", is_deleted=" . $is_deleted;
        if ($files != "")
            $condition .= ", files='" . $files . "'";
        if ($coord != "")
            $condition .= ", coord='" . $coord . "'";

        $sql = "UPDATE ideas SET id=".$id . $condition . " WHERE id=" . $id;

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

        $json = json_encode($data);

        echo $json;
    }
}