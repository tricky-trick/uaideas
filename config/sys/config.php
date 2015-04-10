<?php
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "se3512xdm";
$mysql_database = "uaideas";

$con = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Opps some thing went wrong");
mysql_set_charset('utf8',$con);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8', SET NAMES 'utf8'", $con);
mysql_select_db($mysql_database, $con) or die("Cannot select DB");

?>