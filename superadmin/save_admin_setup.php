<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$adminid = $_REQUEST["adminid"];
$adminpw = $_REQUEST["adminpw"];
$first = $_REQUEST["first"];
$last = $_REQUEST["last"];
$email = $_REQUEST["email"];
$access = $_REQUEST["access"];
$oldaccess = $_REQUEST["oldaccess"];
$newaccess = $_REQUEST["newaccess"];

$accesschange = $oldaccess != $newaccess;
if ($arg == "2") {
  if ($id) {
// Update administrator information.
    $sql = "UPDATE site_admin " .
           "SET a_adminid='$adminid', " .
           "    a_adminpw='$adminpw', " .
           "    a_first='$first', " .
           "    a_last='$last', " .
           "    a_email='$email' " .
           "WHERE a_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
// Add an administrator.
    $sql = "INSERT INTO site_admin " .
           "SET a_adminid='$adminid', " .
           "    a_adminpw='$adminpw', " .
           "    a_first='$first', " .
           "    a_last='$last', " .
           "    a_email='$email'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
    $accesschange = true;
  }
  if ($accesschange) {
    if (sizeof($access) > 0) {
      $sql = "DELETE FROM site_admin_access " .
             "WHERE aa_adminid='$id'";
      mysql_query($sql, $connect); 
      while (list($n, $f) = each($access)) {
        $sql = "INSERT INTO site_admin_access " .
               "SET aa_adminid='$id', " .
               "    aa_funcid='$f' ";
        mysql_query($sql, $connect);
      }
    }
  }
}
// Delete an administrator.
else if ($arg == "4") {
  $sql = "DELETE FROM site_admin " .
         "WHERE a_id='$id'";
  mysql_query($sql, $connect);
  $sql = "DELETE FROM site_admin_access " .
         "WHERE aa_adminid='$id'";
  mysql_query($sql, $connect);
}
header("Location: admin_setup.php");
?>
