<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$label = $_REQUEST["label"];
$command = $_REQUEST["command"];
$querystring = $_REQUEST["querystring"];
$restrict = $_REQUEST["restrict"];
$followid = $_REQUEST["followid"];
$categoryid = $_REQUEST["categoryid"];

if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_admin_funcs " .
           "SET af_label='$label', " .
           "    af_categoryid='$categoryid', " .
           "    af_command='$command', " .
           "    af_querystring='$querystring', " .
           "    af_restrict='$restrict', " .
           "    af_admincontact='$admincontact' " .
           "WHERE af_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_admin_funcs " .
           "SET af_label='$label', " .
           "    af_categoryid='$categoryid', " .
           "    af_command='$command', " .
           "    af_querystring='$querystring', " .
           "    af_restrict='$restrict', " .
           "    af_admincontact='$admincontact' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the menu items are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_admin_funcs " .
         "WHERE af_categoryid='$categoryid' " . 
         "  AND af_id <> '$id' " .
         "ORDER BY af_sequence";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->af_id;
    if ($followid == $row->af_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_admin_funcs " .
           "SET af_sequence='$i' " .
           "WHERE af_categoryid='$categoryid' " .
           "  AND af_id='$idlist[$i]'";
    mysql_query($sql, $connect);
  }

}
// Delete an admin area.
else if ($arg == "4") {
  $sql = "DELETE FROM site_admin_access " .
         "WHERE aa_funcid='$id'";
  mysql_query($sql, $connect);
  $sql = "DELETE FROM site_admin_funcs " .
         "WHERE af_id='$id'";
  mysql_query($sql, $connect);
}
header("Location: admin_funcs.php");
?>
