<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$category = $_REQUEST["category"];
$followid = $_REQUEST["followid"];

$args = "";
// Add
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_bibletrivia_categories " .
           "SET bc_category='$category' " .
           "WHERE bc_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_bibletrivia_categories " .
           "SET bc_category='$category' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_bibletrivia_categories " .
         "WHERE bc_id <> '$id' " .
         "ORDER BY bc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->bc_id;
    if ($followid == $row->bc_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_bibletrivia_categories " .
           "SET bc_seq='$s' " .
           "WHERE bc_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete a category.
elseif ($arg == "4") {
  $sql = "SELECT * FROM site_bibletrivia_questions " .
         "WHERE bq_bcid='$id'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM site_bibletrivia_categories " .
           "WHERE bc_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "?inuse=1";
  }
}

header("Location: trivia_categories.php" . $args);
?>
