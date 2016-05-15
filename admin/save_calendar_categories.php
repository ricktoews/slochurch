<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$new = $_REQUEST["new"];
$id = $_REQUEST["id"];
$category = $_REQUEST["category"];
$oldsubid = $_REQUEST["oldsubid"];
$subid = $_REQUEST["subid"];
$followid = $_REQUEST["followid"];
$background_color = $_REQUEST["cc_background_color"];
$color = $_REQUEST["cc_color"];

// Add a category.
if ($arg == "2") {
  if (!$id) {
    $sql = "INSERT INTO site_calendar_categories " .
           "SET cc_category='$category', " .
           "    cc_background_color='$background_color', " .
           "    cc_color='$color', " .
           "    cc_subid='$subid' ";
    check_mysql($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  else {
    $sql = "UPDATE site_calendar_categories " .
           "SET cc_category='$category', " .
           "    cc_background_color='$background_color', " .
           "    cc_color='$color', " .
           "    cc_subid='$subid' " .
           "WHERE cc_id='$id'";
    check_mysql($sql, $connect);
  }
// Get the sequence in which the categories are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_calendar_categories " .
         "WHERE cc_id <> '$id' " .
         "  AND cc_subid='$subid' " .
         "ORDER BY cc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->cc_id;
    if ($followid == $row->cc_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_calendar_categories " .
           "SET cc_seq='$i' " .
           "WHERE cc_id='$idlist[$i]'";
    check_mysql($sql, $connect);
  }
// If the subcategory was changed, fix the hole in the numbering sequence of the old category.
  if ($subid != $oldsubid) {
    $s = 0;
    $idlist = array();
    $sql = "SELECT * FROM site_calendar_categories " .
           "WHERE cc_subid='$oldsubid' " .
           "ORDER BY cc_seq";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $idlist[$s++] = $row->cc_id;
    }
    for ($i = 0; $i < $s; $i++) {
      $sql = "UPDATE site_calendar_categories " .
             "SET cc_seq='$i' " .
             "WHERE cc_id='$idlist[$i]'";
      check_mysql($sql, $connect);
    }
  }
}
// Delete a category.
else if ($arg == "4") {
  $sql = "DELETE FROM site_calendar " .
         "WHERE cal_ccid='$id' ";
  check_mysql($sql, $connect);
  $sql = "DELETE FROM site_calendar_events " .
         "WHERE ce_ccid='$id' ";
  check_mysql($sql, $connect);
  $sql = "DELETE FROM site_calendar_categories " .
         "WHERE cc_id='$id'";
  check_mysql($sql, $connect);
}

header("Location: calendar_categories.php" . $args);
?>
