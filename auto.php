<?php

if ( !isset($_REQUEST['term']) )
    exit;

include("config/sys/config.php");
header ("Content-Type: text/html; charset=utf-8");
mysql_set_charset('utf8',$con);

$rs = mysql_query('select * from city where name like "'. mysql_real_escape_string($_REQUEST['term']) .'%" AND region_id='.$_REQUEST['region_id'].' order by name asc limit 0,10', $con);

$data = array();

if ($rs && mysql_num_rows($rs))
{
    while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
    {
        $data[] = array('value' => $row['name']);
    }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
flush();