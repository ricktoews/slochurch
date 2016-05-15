<?php
include("path.inc");
include("includes/lib_admin.inc");

$date = date("Y-m-d");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$followid = $_REQUEST["followid"];
$tagline = stripslashes(addslashes($_REQUEST["tagline"]));
$description = stripslashes(addslashes($_REQUEST["description"]));
$expm = $_REQUEST["month"];
$expd = $_REQUEST["day"];
$expy = $_REQUEST["year"];
$expdate = date("Y-m-d", mktime(0,0,0,$expm,$expd,$expy));

// If this is an insert or update, process that first.
if ($arg == "1") {
// If this is a new news item, first insert it into the table, and get the record ID.
  if (!$id) {
    $sql = "INSERT INTO site_news " .
           "SET news_tagline='$tagline', " .
           "    news_description='$description', " .
           "    news_postdate='$date', " .
           "    news_expdate='$expdate' ";
    check_mysql($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  else {
    $sql = "UPDATE site_news " .
           "SET news_tagline='$tagline', " .
           "    news_description='$description', " .
           "    news_expdate='$expdate' " .
           "WHERE news_id='$id'";
    check_mysql($sql, $connect);
  }
// Get the sequence in which the news items are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_news " .
         "WHERE news_id <> '$id' " .
         "ORDER BY news_seq";
  $result = check_mysql($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->news_id;
    if ($followid == $row->news_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_news " .
           "SET news_seq='$i' " .
           "WHERE news_id='$idlist[$i]'";
    check_mysql($sql, $connect);
  }
}
elseif ($arg == "2") {
  while (list($n, $i) = each($id)) {
    $del = $_REQUEST["delete" . $n];
    if ($del) {
      $sql = "DELETE FROM site_news " .
             "WHERE news_id='$i' ";
      check_mysql($sql, $connect);
    }
  }
}
elseif ($arg == "4") {
  $sql = "DELETE FROM site_news " .
         "WHERE news_id='$id' ";
  check_mysql($sql, $connect);
}
header("Location: news.php");
?>
