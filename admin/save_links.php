<?php
include("path.inc");
include("includes/lib_admin.inc");

$id = $_REQUEST['id'];
$url = $_REQUEST['url'];
$title = $_REQUEST['title'];
$description = $_REQUEST['description'];
$categoryid = $_REQUEST['categoryid'];
$followid = $_REQUEST['followid'];

if ($arg == "1") {
  $r = 0;
  while ($r < $max_rows) {
    eval("\$f = \$flag_$r;");
    eval("\$id = \$linkid_$r;");
    $sql = "UPDATE site_links " .
           "SET l_flag='$f' " .
           "WHERE l_id='$id'";
    mysql_query($sql, $connect);
    $r++;
  }
}
elseif ($arg == "2") {
  $sql = "SELECT * FROM site_links " .
         "WHERE l_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $sql2 = "UPDATE site_links " .
            "SET l_url='$url', " .
            "    l_title='$title', " .
            "    l_description='$description', " .
            "    l_categoryid='$categoryid' " .
            "WHERE l_id='$id'";
    mysql_query($sql2, $connect);
  }
  else {
    $sql2 = "INSERT INTO site_links " .
            "SET l_url='$url', " .
            "    l_title='$title', " .
            "    l_description='$description', " .
            "    l_categoryid='$categoryid' ";
    mysql_query($sql2, $connect);
  }
// Get the sequence in which items are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_links " .
         "WHERE l_id <> '$id' " .
         "  AND l_categoryid='$categoryid' " .
         "ORDER BY l_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->l_id;
    if ($followid == $row->l_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_links " .
           "SET l_seq='$i' " .
           "WHERE l_id='$idlist[$i]'";
    mysql_query($sql, $connect);
  }
}
elseif ($arg == "4") {
  $sql = "DELETE FROM site_links " .
         "WHERE l_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: links.php");
?>
