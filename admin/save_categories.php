<?php
include("path.inc");
include("connect.inc");

$classid = $_REQUEST["class"];
$id = $_REQUEST["id"];
$category = $_REQUEST["category"];
$followid = $_REQUEST["followid"];

if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_categories " .
           "SET cat_category='$category' " .
           "WHERE cat_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_categories " .
           "SET cat_category='$category' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_categories " .
         "WHERE cat_id <> '$id' " .
         "  AND cat_classid='$classid' " .
         "ORDER BY cat_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->cat_id;
    if ($followid == $row->cat_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_categories " .
           "SET cat_seq='$s' " .
           "WHERE cat_id='$i'";
    mysql_query($sql, $connect);
  }

}
elseif ($arg == "4") {
  $sql = "SELECT * FROM site_rides " .
         "WHERE r_categoryid='$id'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM site_categories " .
           "WHERE cat_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "&inuse=1";
  }
}

header("Location: categories.php?class=$classid" . $args);
?>
