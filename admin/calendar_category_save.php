<font face="arial" size="-1">
<?php
include("lib.php");


function preset($set, $categoryid) {
  global $connect;
  global $each_ordinal, $each_weekday;
  global $rangetype, $monthrange1, $monthrange2;
  global $time;

  if ($each_ordinal[0] == "0") {
    $selected = array(1, 1, 1, 1, 1);
  }
  else {
    $selected = array(false, false, false, false, false);
  }

  if (sizeof($each_ordinal) > 0) {
    while (list($n, $v) = each($each_ordinal)) {
      $selected[$v-1] = true;    
    }
  }
  else {
    $selected[$each_ordinal] = true;    
  }

  if ($rangetype == "1") {
    $month = date("n");
    $year = date("Y");
    $endmonth = 12;
  }
  elseif ($rangetype == "2") {
    $month = 1;
    $year = date("Y") + 1;
    $endmonth = 12;
  }
  elseif ($rangetype == "3") {
    $month = $monthrange1 + date("m");
    $year = date("Y");
    $endmonth = $monthrange2 + date("m");

  }
  while ($month <= $endmonth) {
    $blanks = date("w", mktime(0,0,0,$month,1,$year)) - 1;
    $lastday = date("t", mktime(0,0,0,$month,1,$year));
    $mo = date("m", mktime(0,0,0,$month,1,$year));
    $yr = date("Y", mktime(0,0,0,$month,1,$year));
    $date= $each_weekday - $blanks;
    if ($date < 1) {
      $date += 7;
    }
    $ord = 0;
    while ($date <= $lastday) {
      $skip = (date("Ymd", mktime(0,0,0,$month,$date,$year)) < date("Ymd"));
      
      if ($selected[$ord] == true && !$skip) {
        $da = strlen($date) == 1 ? "0" . $date : $date;
        if ($set == "1") {
          $sql = "SELECT * FROM church_calendar " .
                 "WHERE categoryid='$categoryid' " .
                 "  AND year='$yr' " .
                 "  AND month='$mo' " .
                 "  AND day='$da' " .
                 "  AND time='$time' ";
          $result = mysql_query($sql, $connect);
          if (mysql_numrows($result) == 0) {
            $sql = "INSERT INTO church_calendar (year, month, day, time, categoryid, status, bulletin) " .
                   "VALUES ('$yr', '$mo', '$da', '$time', '$categoryid', 'A', '1')";
            mysql_query($sql, $connect);
          }
        }
        elseif ($set == "2") {
          $sql = "DELETE FROM church_calendar " .
                 "WHERE categoryid='$categoryid' " .
                 "  AND year='$yr' " .
                 "  AND month='$mo' " .
                 "  AND day='$da' " .
                 "  AND time='$time' ";
          mysql_query($sql, $connect);
        }
      } 
      $ord++;
      $date += 7;
    }
    $month++; 
  }
}


if ($ampm == "pm" && $hour < 12) {
  $hour += 12;
}
if ($ampm == "am" && $hour == 12) {
  $hour = 0;
}
$time = !$unscheduled ? sprintf("%02d:%02d", $hour, $minute) : "";

?>
<?php
$tbl_category = fix_addslashes($category);
// Add a category.
if ($arg == "1") {
  $sql = "INSERT INTO church_calendar_categories " .
         "SET category='$tbl_category'";
  mysql_query($sql, $connect);
  $categoryid = mysql_insert_id($connect);
  if ($preset) {
    preset($preset, $categoryid);
  }
  if ($new) {
    $args .= "&new=2&newid=$categoryid";
  }
}
// Update a category.
elseif ($arg == "2") {
  $sql = "UPDATE church_calendar_categories " .
         "SET category='$tbl_category' " .
         "WHERE categoryid='$categoryid'";
  mysql_query($sql, $connect);
  if ($preset) {
    preset($preset, $categoryid);
  }
}
// Delete a category.
elseif ($arg == "4") {
  $sql = "SELECT * FROM church_calendar " .
         "WHERE categoryid='$categoryid'";
  $result = mysql_query($sql, $connect);
  if (mysql_num_rows($result) == 0) {
    $sql = "DELETE FROM church_calendar_categories " .
           "WHERE categoryid='$categoryid'";
    mysql_query($sql, $connect);
  }
  else {
    $args .= "&inuse=1";
  }
}

header("Location: category.php" . $args);
?>
</font>
