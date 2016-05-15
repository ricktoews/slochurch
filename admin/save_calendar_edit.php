<?php
include("path.inc");
include("$PATH/includes/lib.inc");

$arg = $_REQUEST["arg"];
$calid = $_REQUEST["calid"];
$ccid = $_REQUEST["ccid"];
$ymd = $_REQUEST["ymd"];
$seq = $_REQUEST["seq"];
$selectItem = $_REQUEST["selectItem"];
$item = $_REQUEST["item"];
$starthour = $_REQUEST["starthour"];
$startminute = $_REQUEST["startminute"];
$startampm = $_REQUEST["startampm"];
$starttime = $_REQUEST["starttime"];
$endhour = $_REQUEST["endhour"];
$endminute = $_REQUEST["endminute"];
$endampm = $_REQUEST["endampm"];
$endtime = $_REQUEST["endtime"];
$description = $_REQUEST["description"];
$items = $_REQUEST["items"];

$sender = "";
$eventdate = date("F j, Y", strtotime($ymd));

$refresh = 0;
if ($startampm == "pm" && $starthour < 12) {
  $starthour += 12;
}
if ($startampm == "am" && $starthour == 12) {
  $starthour = 0;
}
$starttime = !$unscheduled ? sprintf("%02d:%02d", $starthour, $startminute) : "";
if ($endampm == "pm" && $endhour < 12) {
  $endhour += 12;
}
if ($endampm == "am" && $endhour == 12) {
  $endhour = 0;
}
$endtime = !$unscheduled ? sprintf("%02d:%02d", $endhour, $endminute) : "";


if ($arg == "2") {
  if ($calid) {
    $sql = "UPDATE site_calendar " .
           "SET cal_item='$item', " .
           "    cal_starttime='$starttime', " .
           "    cal_endtime='$endtime', " .
           "    cal_description='$description', " .
           "    cal_ccid='$ccid' " .
           "WHERE cal_id='$calid'";
    check_mysql($sql, $connect);
    $refresh = 1;
  }
  else {
    $sql = "SELECT * FROM site_calendar " .
           " WHERE cal_ymd='$ymd' " .
           "ORDER BY cal_seq DESC";
    $result = check_mysql($sql, $connect);
    $seq = 1;
    if ($row = mysql_fetch_object($result)) {
      $seq = $row->cal_seq + 1;
    }
    $sql = "INSERT INTO site_calendar " .
           "SET cal_ymd='$ymd', " .
           "    cal_seq=$seq, " .
           "    cal_item='$item', " .
           "    cal_starttime='$starttime', " .
           "    cal_endtime='$endtime', " .
           "    cal_description='$description', " .
           "    cal_ccid='$ccid', " .
           "    cal_status='A' ";
    check_mysql($sql, $connect);
    $calid = mysql_insert_id($connect);
    $refresh = 1;
  }
}
elseif ($arg == "4") {
// Delete an item from the calendar.
  $sql = "DELETE FROM site_calendar " .
         "WHERE cal_id='$calid' ";
  check_mysql($sql, $connect);
  $seq = 0;
  $item = "";
  $description = "";
  $refresh = 1;
}

header("Location: calendar_edit.php?ccid=$ccid&ymd=$ymd&refresh=$refresh");
?>
