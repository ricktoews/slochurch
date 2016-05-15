<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$category = $_REQUEST["category"];
$followid = $_REQUEST["followid"];

$args = "";
// Add a category.
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_link_categories " .
           "SET lc_category='$category' " .
           "WHERE lc_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_link_categories " .
           "SET lc_category='$category'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_link_categories " .
         "WHERE lc_id <> '$id' " .
         "ORDER BY lc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->lc_id;
    if ($followid == $row->lc_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_link_categories " .
           "SET lc_seq='$s' " .
           "WHERE lc_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete a category.
elseif ($arg == "4") {
  $sql = "SELECT * FROM site_links " .
         "WHERE l_categoryid='$id'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM site_link_categories " .
           "WHERE lc_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "?inuse=1";
  }
}

header("Location: link_categories.php" . $args);
?>
