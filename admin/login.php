<?php
include("../cgi-bin/wardrobe.inc");
$loggedin = 1;
if (!$cookie_login) {
  $loggedin = 0;
}
else {
  $sql = "SELECT * FROM church_admin " .
         "WHERE adminid='$cookie_login'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $loggedin = 0;
  }
}
if (!$loggedin) {
  header("Location: index.php");
  exit();
}
?>
