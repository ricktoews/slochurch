<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$category = $_REQUEST["category"];
$followid = $_REQUEST["followid"];

// Add/Update a category.
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_admin_area_categories " .
           "SET ac_category='$category' " .
           "WHERE ac_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_admin_area_categories " .
           "SET ac_category='$category'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the menu items are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_admin_area_categories " .
         "WHERE ac_id <> '$id' " .
         "ORDER BY ac_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->ac_id;
    if ($followid == $row->ac_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_admin_area_categories " .
           "SET ac_seq='$i' " .
           "WHERE ac_id='$idlist[$i]'";
    mysql_query($sql, $connect);
  }
}
// Delete a category.
elseif ($arg == "4") {
  $sql = "SELECT * FROM site_admin_funcs " .
         "WHERE af_categoryid='$id'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM site_admin_area_categories " .
           "WHERE ac_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "?inuse=1";
  }
}

header("Location: category.php" . $args);
?>
