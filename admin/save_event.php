<?php
include("path.inc");
include($PATH . "/includes/lib.inc");

$arg = $_REQUEST["arg"];
$new = $_REQUEST["new"];
$ccid = $_REQUEST["ccid"];
$id = $_REQUEST["id"];
$event = $_REQUEST["event"];
$each_ordinal = $_REQUEST["each_ordinal"];
$each_weekday = $_REQUEST["each_weekday"];
$monqtr = $_REQUEST["monqtr"];
$starthour = $_REQUEST["starthour"];
$startminute = $_REQUEST["startminute"];
$startampm = $_REQUEST["startampm"];
$endhour = $_REQUEST["endhour"];
$endminute = $_REQUEST["endminute"];
$endampm = $_REQUEST["endampm"];
$unscheduled = $_REQUEST["unscheduled"];
$time = $_REQUEST["time"];
$daterange1 = $_REQUEST["daterange1"];
$startdate = date("Y-m-d", strtotime($daterange1));
$daterange2 = $_REQUEST["daterange2"];
$enddate = date("Y-m-d", strtotime($daterange2));


function preset($set) {
  global $connect;
  global $event;
  global $ccid, $id;
  global $seqday, $wkdays;
  global $monqtr;
  global $starttime, $endtime;
  global $startdate, $enddate;

  if ($set == 1) {
// This will mark all records that use the selected calendar event id.
    $sql = "UPDATE site_calendar " .
           "SET cal_status='X' " .
           "WHERE cal_ceid='$id'";
    check_mysql($sql, $connect);
  }

  if ($monqtr == "M") {
    if ($seqday == 31) {
      $selected = array(1, 1, 1, 1, 1);
    }
    else {
      for ($i = 0; $i < 5; $i++) {
        $exp = pow(2, $i);
        $selected[$i] = $seqday & $exp ? 1 : 0;
      }
    }
  }
  elseif ($monqtr == "Q") {
    if ($seqday == 8191) {
      $selected = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
    }
    else {
      for ($i = 0; $i < 13; $i++) {
        $exp = pow(2, $i);
        $selected[$i] = $seqday & $exp ? 1 : 0;
      }
    }
  }
  if ($wkdays == 127) {
    $dayselected = array(1, 1, 1, 1, 1, 1, 1);
  }
  else {
    for ($i = 0; $i < 7; $i++) {
      $exp = pow(2, $i);
      $dayselected[$i] = $wkdays & $exp ? 1 : 0;
    }
  }

  $eachday = 0;
/*
  if (!$eachday) {
    list($y, $m, $d) = explode("-", $startdate);
    $blanks = date("w", mktime(0,0,0,$m,1,$y)) - 1;
    $d = $day - $blanks;
    if ($d < 1) {
      $d += 7;
    }
    $startdate = date("Y-m-d", mktime(0,0,0,$m,$d,$y));
  }
*/
  $orig_startdate = $startdate;
  for ($i = 0; $i < 7; $i++) {
    $startdate = $orig_startdate; 
    if ($dayselected[$i]) {
      $day = $i;
      list($y, $m, $d) = explode("-", $startdate);
      $blanks = date("w", mktime(0,0,0,$m,1,$y)) - 1;
      $d = $day - $blanks;
      if ($d < 1) {
        $d += 7;
      }
      $startdate = date("Y-m-d", mktime(0,0,0,$m,$d,$y));
    
      $lastmo = -1;
      $lastqtr = -1;
      while ($startdate <= $enddate) {
        list($y, $m, $d) = explode("-", $startdate);
        $qtr = floor(($m - 1) / 3);
        if ($monqtr == "Q") {
          if ($lastqtr != $qtr) {
            $ord = 0;
            $lastqtr = $qtr;
          }
        }
        else {
          if ($lastmo != $m) {
            $ord = 0;
            $lastmo = $m;
          }
        }

        $skip = (date("Ymd", mktime(0,0,0,$m,$d,$y)) < date("Ymd"));

        if ($eachday || $selected[$ord] == true && !$skip) {
          $da = $d;
          $mo = date("m", mktime(0,0,0,$m,$d,$y));
          $yr = $y;
          $ymd = date("Y-m-d", mktime(0,0,0,$mo,$da,$yr));
          if ($set == "1") {
            $sql = "SELECT * FROM site_calendar " .
                   "WHERE cal_ceid='$id' " .
                   "  AND cal_status='X' " .
                   "ORDER BY cal_id";
            $result = check_mysql($sql, $connect);
            if ($row = mysql_fetch_object($result)) {
              $cal_id = $row->cal_id;
              $sql = "UPDATE site_calendar " .
                     "SET cal_item='$event', " .
                     "    cal_ymd='$ymd', " .
                     "    cal_starttime='$starttime', " .
                     "    cal_endtime='$endtime', " .
                     "    cal_ccid='$ccid', " . 
                     "    cal_status='A' " .
                     "WHERE cal_id='$cal_id'";
              check_mysql($sql, $connect); 
            }
            else {
              $sql = "INSERT INTO site_calendar (cal_item, cal_ymd, cal_starttime, cal_endtime, cal_ccid, cal_ceid, cal_status) " .
                     "VALUES ('$event', '$ymd', '$starttime', '$endtime', '$ccid', '$id', 'A')";
              check_mysql($sql, $connect);
            }
          }
          elseif ($set == "2") {
            $sql = "DELETE FROM site_calendar " .
                   "WHERE cal_ccid='$ccid' " .
                   "  AND cal_ymd='$ymd' " .
                   "  AND cal_starttime='$starttime' " .
                   "  AND cal_endtime='$endtime' " .
                   "  AND cal_ceid='$ceid' ";
            check_mysql($sql, $connect);
          }
        }
        $ord++;
        $d += ($eachday ? 1 : 7);
        $startdate = date("Y-m-d", mktime(0,0,0,$m,$d,$y));
      }
    }
  }
  $sql = "DELETE FROM site_calendar " .
         "WHERE cal_ceid='$id' " .
         "  AND cal_status='X'";
  check_mysql($sql, $connect);
}


$seqday = 0;
$wkdays = 0;
foreach ($each_ordinal as $n => $v) {
  $seqday += $v;
}
if ($monqtr == "M" && $seqday > 31) {
  $seqday = 31;
}
elseif ($monqtr == "Q" && $seqday > 8191) {
  $seqday = 8191;
}
foreach ($each_weekday as $n => $v) {
  $wkdays += $v;
}
if ($wkdays > 127) {
  $wkdays = 127;
}

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

// Update a category.
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_calendar_events " .
           "SET ce_event='$event', " .
           "    ce_ccid='$ccid', " .
           "    ce_seqday='$seqday', " .
           "    ce_day='$wkdays', " .
           "    ce_monqtr='$monqtr', " .
           "    ce_starttime='$starttime', " .
           "    ce_endtime='$endtime', " .
           "    ce_startdate='$startdate', " .
           "    ce_enddate='$enddate' " .
           "WHERE ce_id='$id'";
    check_mysql($sql, $connect);
    preset(1);
  }
  else {
    $sql = "INSERT site_calendar_events " .
           "SET ce_event='$event', " .
           "    ce_ccid='$ccid', " .
           "    ce_seqday='$seqday', " .
           "    ce_day='$wkdays', " .
           "    ce_monqtr='$monqtr', " .
           "    ce_starttime='$starttime', " .
           "    ce_endtime='$endtime', " .
           "    ce_startdate='$startdate', " .
           "    ce_enddate='$enddate' ";
    check_mysql($sql, $connect);
    $id = mysql_insert_id($connect);
    preset(1);
  }
}
// Delete a category.
elseif ($arg == "4") {
  $sql = "DELETE FROM site_calendar " .
         "WHERE cal_ceid='$id'";
  check_mysql($sql, $connect);
  $sql = "DELETE FROM site_calendar_events " .
         "WHERE ce_id='$id'";
  check_mysql($sql, $connect);
}

header("Location: event.php?ccid=$ccid&id=$id&arg=1");
?>
