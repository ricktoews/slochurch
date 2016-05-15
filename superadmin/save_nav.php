<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$label = $_REQUEST["label"];
$tag = $_REQUEST["tag"];
$command = $_REQUEST["command"];
$mouseover = $_REQUEST["mouseover"];
$mouseout = $_REQUEST["mouseout"];
$popup = $_REQUEST["popup"];
$followid = $_REQUEST["followid"];

// Add a navigation item.
if ($arg == "2") {
  if (!$id) {
    $sql = "INSERT INTO site_nav " .
           "SET nav_label='$label', " .
           "    nav_tag='$tag', " .
           "    nav_command='$command', " .
           "    nav_mouseover='$mouseover', " .
           "    nav_mouseout='$mouseout', " .
           "    nav_popup='$popup'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Update a navigation item.
  else {
    $sql = "UPDATE site_nav " .
           "SET nav_label='$label', " .
           "    nav_tag='$tag', " .
           "    nav_command='$command', " .
           "    nav_mouseover='$mouseover', " .
           "    nav_mouseout='$mouseout', " .
           "    nav_popup='$popup' " .
           "WHERE nav_id='$id'";
    mysql_query($sql, $connect);
  }
// Get the sequence in which the items are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_nav " .
         "WHERE nav_id <> '$id' " .
         "ORDER BY nav_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->nav_id;
    if ($followid == $row->nav_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_nav " .
           "SET nav_seq='$i' " .
           "WHERE nav_id='$idlist[$i]'";
    mysql_query($sql, $connect);
  }
}
// Delete a category.
elseif ($arg == "4") {
  $sql = "DELETE FROM site_nav " .
         "WHERE nav_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: nav.php");
?>
