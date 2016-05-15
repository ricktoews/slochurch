<?php
$connect = mysql_connect("localhost", "slochurc", "191215");
$db = mysql_select_db("dev_slochurch", $connect);
$_REQUEST["connect"] = $connect;
?>
