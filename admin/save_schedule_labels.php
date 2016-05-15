<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$label = $_REQUEST["label"];
$officeid = $_REQUEST["officeid"];
$offices = sizeof($officeid) > 0 ? implode("|", $officeid) : "";
$followid = $_REQUEST["followid"];

$args = "";
// Add
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_schedule_labels " .
           "SET sl_label='$label', " .
           "    sl_offices='$offices' " .
           "WHERE sl_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_schedule_labels " .
           "SET sl_label='$label', " .
           "    sl_offices='$offices'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_schedule_labels " .
         "WHERE sl_id <> '$id' " .
         "ORDER BY sl_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->sl_id;
    if ($followid == $row->sl_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_schedule_labels " .
           "SET sl_seq='$s' " .
           "WHERE sl_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete a label.
elseif ($arg == "4") {
  $sql = "SELECT * FROM site_schedule_information " .
         "WHERE si_slid='$id'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM site_schedule_labels " .
           "WHERE sl_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "?inuse=1";
  }
}

header("Location: schedule_labels.php" . $args);
?>
