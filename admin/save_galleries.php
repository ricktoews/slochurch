<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$gallery = $_REQUEST["gallery"];
$description = $_REQUEST["description"];
$followid = $_REQUEST["followid"];

// Add
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_photo_galleries " .
           "SET pg_gallery='$gallery', " .
           "    pg_description='$description' " .
           "WHERE pg_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_photo_galleries " .
           "SET pg_gallery='$gallery', " .
           "    pg_description='$description'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_photo_galleries " .
         "WHERE pg_id <> '$id' " .
         "ORDER BY pg_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->pg_id;
    if ($followid == $row->pg_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_photo_galleries " .
           "SET pg_seq='$s' " .
           "WHERE pg_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete 
elseif ($arg == "4") {
  $sql = "DELETE FROM site_photo_galleries " .
         "WHERE pg_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: galleries.php");
?>
