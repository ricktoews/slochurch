<?php
include($PATH . "/includes/lib.inc");

$LOGIN_ID = $_COOKIE["cookie_adminid"];

$loggedin = 1;
if (!$LOGIN_ID) {
  $loggedin = 0;
}
else {
  $sql = "SELECT * FROM site_admin " .
         "WHERE a_id='$LOGIN_ID'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $adminemail = $row->a_email;
  }
  else {
    $loggedin = 0;
  }
}
if (!$loggedin) {
  header("Location: index.php");
  exit();
}
?>
