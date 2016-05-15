<?php
include("path.inc");
include("includes/header.inc");

$details = $_REQUEST["details"];
$ccid = $_REQUEST["ccid"];
$edit = $ccid > 0;
$catlist = get_categories($ccid);
if ($ccid) {
  array_unshift($catlist, $ccid);
}
$startmonth = $_REQUEST["startmonth"];
$format = $_REQUEST["format"];
if (strlen($format) == 0) {
  $format = 1;
  $details = 1;
}
?>
<?php
$calendar_popup_width=500;
$calendar_popup_height=300;

function truncate($str) {
  $cutoff = 17;
  if (strlen($str) > $cutoff) {
    $append = "...";
    $i = $cutoff;
    while (substr($str, $i, 1) != " " && $i > 0) {
      $i--;
    } 
    $trunc = trim(substr($str, 0, $i)) . "...";
  }
  else {
    $trunc = $str;
  } 
  return $trunc;
}

function drawMonth($month, $year, $datewidth, $dateheight) {
  global $connect;
  global $details, $edit;
  global $catlist;
  global $ccid;
  global $borcol, $bg2, $bg1, $bg4, $bg3, $bg5;
  global $today;

  if ($datewidth <= 0) {
    $datewidth = 100;
  }
  if ($dateheight <= 0) {
    $dateheight = 30;
  }

  $days = date("t", mktime(0,0,0,$month,1,$year));
  $blanks = date("w", mktime(0, 0, 0, $month, 1, $year));
  $rows = ceil(($days + $blanks) / 7);

  $d = 1;
  $mname = date("F", mktime(0,0,0,$month,1,2000));
  echo "
    <tr bgcolor='$bg1'>
      <th colspan='7' class='fg1'>$mname, $year</th>
    </tr>
    <tr bgcolor='$bg2'>
      <th class='fg2'>Sun<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Mon<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Tue<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Wed<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Thu<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Fri<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='fg2'>Sat<br><img src='images/spacer.gif' width='$datewidth' height='1'></th>
    </tr>
  ";
  for ($i = 0; $i < $rows; $i++) {
    echo "<tr valign=top>";
    for ($j = 0; $j < 7; $j++) {
      $eventCell = 0;
      $dateCell = 0;
      if ($i * 7 + $j >= $blanks && $d <= $days) {
        $thisdate = date("Ymd", mktime(0,0,0,$month,$d,$year));
        if (1 || $thisdate >= $today) {
          $obsolete = 0;
          $day = "00$d";
          $day = substr($day, strlen($day)-2, 2);
          $ymd = date("Y-m-d", mktime(0,0,0,$month,$d,$year));
          $sql = "SELECT * FROM site_calendar, site_calendar_categories " .
                 " WHERE cal_ccid=cc_id " .
                 "   AND cal_ccid IN (" . implode(",", $catlist) . ") " .
                 "   AND cal_status='A' " .
                 "   AND cal_ymd='$ymd' " .
                 "ORDER BY cal_starttime, cal_item";
          $result = check_mysql($sql, $connect);
        }
        else {
          $obsolete = 1;
          $row = null;
        }
        if ($result && mysql_num_rows($result) > 0) {
          $eventCell = 1;
// Write a cell with an event date in it.
          echo "  <td bgcolor='$bg4' class='fg4' align='right' width='$datewidth'>";
          if ($details == "1") {
            echo "<p align='right'>$d</p>";
            echo "<p>";
            $legends = "";
            $items = "";
            while ($row = mysql_fetch_object($result)) {
              list($starthour, $startminute) = explode(":", $row->cal_starttime);
              list($endhour, $endminute) = explode(":", $row->cal_endtime);
              $starttime = $row->cal_starttime > "" ? date("g:ia", mktime($starthour, $startminute, 0, $month, $day, $year)) : "";
              $endtime = $row->cal_endtime > "" ? date("g:ia", mktime($endhour, $endminute, 0, $month, $day, $year)) : "";
              $seq = $row->cal_seq;
              $description = preg_replace('/"/', '&quot;', $row->cal_description);
              $shortdesc = truncate($description);
              $background_color = $row->cc_background_color;
              $color = $row->cc_color;
              $item = "<br /><div style=\"background-color:$background_color\"><a href=\"javascript:showItem('$ymd', '$seq')\" style=\"color:$color; text-decoration:none\" title=\"$starttime-$endtime $description\">$starttime - $endtime " . ($row->cal_item > "" ? $row->cal_item : $row->cc_category) . ". $shortdesc</a></div>";
              $items .= $item;
            }
            $items = substr($items, 6, strlen($items) - 6);
            echo "<div align='left'>$items</div>";
          }
          else {
            echo "<a href='javascript:showItem(\"$ymd\")' class='date'>$d</a>";
          }
        }

// Write a cell with no event date in it.
        else {
          $cell = $obsolete ? "pastdate" : "fg5";
          $dateCell = 1;
          echo "
            <td bgcolor='$bg3' class='$cell' align='right' width='$datewidth'><img src='$PATH/images/spacer.gif' width='1' height='$dateheight' align='left'>$d
          ";
        }
        if ($edit == "1") {
          echo "<p><a href='javascript:openEdit(\"$year-$month-$d\")'>Edit</a>";
        }
        echo "</td>";
        $d++;
      }

// Write a blank cell.
      else {
        echo "
          <td bgcolor='$bg5'><img src='images/spacer.gif' width='1' height='1' align='left'></td>
        ";
      }
    }
    echo "
      </tr>
    ";
  }
}

$today = date("Y-m-d");

$currentm = date("m");
$currenty = date("Y");
list($startm, $starty) = explode("|", $startmonth);
$y = $starty ? $starty : $currenty;
$m = $startm ? $startm : $currentm;

$maxmonths = $format == "1" ? 3 : 12;

if ($details == "1") {
  $cellWidth=92;
  $cellHeight=75;
}
else {
  $cellWidth = 20;
  $cellHeight = 15;
}
?>
  <style type="text/css">
  .pastdate {
  font-family:arial;
  font-size:8pt;
  color:#888888;
  }
  a.date {
  text-decoration:none;
  font-weight:bold;
  }
  </style>
  <script language="JavaScript">
  function getCategory() {
    var f = document.forms["calendardata"];
    f.submit();
  }


  function displayFormat(fmt) {
    var f = document.forms["calendardata"];
    f.format.value = fmt;
    f.submit(); 
  }
  </script>
  <script language="JavaScript">
  function openEdit(ymd) {
    open("calendar_edit.php?ccid=<?php echo $ccid; ?>&ymd="+ymd, "edit", "top=50,left=50,scrollbars=yes,width=400,height=350,status=yes");
  }

  function showItem(ymd, seq) {
    if (seq) {
      open("show_calendar_item.php?ccid=<?php echo $ccid; ?>&ymd="+ymd+"&seq="+seq, "show", "scrollbars=yes,width=400,height=400");
    }
    else {
      open("show_calendar_item.php?ccid=<?php echo $ccid; ?>&ymd="+ymd, "show", "scrollbars=yes,width=400,height=400");
    }
  }
  </script>
<form name="calendardata" method="post" action="calendar.php">
<input type=hidden name="details" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Calendar</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Calendar</td>
    <td class="fg2">Starting Month</td>
    <td class="fg2">Format</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select class="smallfield" name="ccid" onchange="getCategory()">
      <option value="">Select a Calendar</option>
<?php
$match = array($ccid => 1);
show_subs(0, "", true);
?>
      </select>
    </td>
    <td class="fg5">
      <select class="smallfield" name="startmonth" onchange="document.forms['calendardata'].submit()">
<?php
  $startm = $m;
  $starty = $y;
  for ($i = 0; $i < 12; ++$i) {
    $currentm = sprintf("%02d", $currentm);
    $selected = "$currentm|$currenty" == $startmonth ? "selected" : "";
    echo "<option $selected value='$currentm|$currenty'>" . date("F, Y", mktime(0,0,0,$currentm, 1, $currenty)) . "</option>\n";
    $currentm++;
    if ($currentm > 12) {
      $currentm = 1;
      $currenty++;
    }
  }
?>
      </select>
    </td>
    <td class="fg5">
      <select name="format" onchange="displayFormat(this.options[this.selectedIndex].value)">
<?php $selected = $format == 1 ? "selected" : ""; ?>
      <option <?php echo $selected; ?> value="1" />Chart</option>
<?php $selected = $format === 0 ? "selected" : ""; ?>
      <option <?php echo $selected; ?> value="0" />Text</option>
      </select>
    </td>
  </tr>
<?php
if (!$ccid) {
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      To edit an individual date, first select a calendar, then click Edit in the appropriate date.
    </td>
  </tr>
<?php
}
?>
</table>

<br />

<table border="0" cellpadding="1" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg5; ?>">
<?php if ($format == "1") { ?>
<!-- BLOCK STYLE CALENDAR -->
    <td align="center" width="100%">
      <table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="1" cellspacing="1">
<?php
  for ($i = 0; $i < $maxmonths; $i++) {
    drawMonth($m, $y, $cellWidth, $cellHeight);
    if ($i < $maxmonths - 1) {
      echo "<tr bgcolor='$bg5'><td colspan='7'><img src='images/spacer.gif' width='1' height='20'></td></tr>";
    }
    if ($m >= 12) {
      $m = 1;
      $y++;
    }
    else {
      $m++;
    }
    $m = sprintf("%02d", $m);
  }
?>
      </table>
      </div>
    </td>
<!-- END BLOCK STYLE CALENDAR CODE -->
<?php } ?>
<?php if ($format == "0") { ?>
<!-- TEXT STYLE CALENDAR CODE -->
    <td>
      <table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
<?php
  for ($i = 0; $i < $maxmonths; $i++) {
    $sql = "SELECT * FROM site_calendar, site_calendar_categories " .
           "WHERE cal_ccid=cc_id " .
           "  AND cal_ccid IN (" . implode(",", $catlist) . ") " .
           "  AND cal_ymd >= '$today' " .
           "  AND 1*MID(cal_ymd,1,4) = '$y' " .
           "  AND 1*MID(cal_ymd,6,2) = '$m' " .
           "  AND cal_status='A' ";
    $sql .= "ORDER BY cal_ymd, cal_starttime, cal_seq";
    $result = check_mysql($sql, $connect);
?>
        <tr bgcolor="<?php echo $bg4; ?>">
          <th colspan="5" class="fg4">
            <img src="images/spacer.gif" width=1 height=3><br>
            <b><span class="fg4">&nbsp;&nbsp;<?php echo date("F, Y", mktime(0,0,0,$m,1,$y)); ?></span></b><br>
            <img src="images/spacer.gif" width=1 height=3><br>
          </th>
        </tr>
<?php
    $lastday = "";
    if (mysql_num_rows($result) == 0) {
?>
        <tr bgcolor="<?php echo $bg3; ?>">
          <td colspan="5" class="fg3">No calendar entries have been posted for this month.</td>
        </tr>
<?php
    }
    else {
?>
        <tr bgcolor="<?php echo $bg3; ?>">
          <th class="fg3">Date</th>
          <td></td>
          <th class="fg3">Time</th>
          <td></td>
          <td class="fg3"><b>Event</b></td>
        </tr>
<?php
    }

    while ($row = mysql_fetch_object($result)) {
      $background_color = $row->cc_background_color;
      $color = $row->cc_color;
// Do not display obsolete calendar items.
      $thisdate = date("Y-m-d", strtotime($row->cal_ymd));
      if (0 && $thisdate < $today) {
        continue;
      }
// End of code to prevent display of obsolete calendar items.
      if ($row->cal_starttime) {
        list($starthour, $startminute) = explode(":", $row->cal_starttime);
        $time = date("g:i a ", mktime($starthour, $startminute, 0, 1, 1, 2000));
        if ($row->cal_endtime) {
          list($endhour, $endminute) = explode(":", $row->cal_endtime);
          $time .= "-<br />" . date("g:i a ", mktime($endhour, $endminute, 0, 1, 1, 2000));
        } 
      }
      else {
        $time = "";
      }
?>
        <tr bgcolor="<?php echo $background_color; ?>" valign="top">
          <td class="fg5" align="right" width="105">
            <span style="color:<?php echo $color; ?>"> 
<?php
      if ($row->cal_ymd != $lastday) {
        $lastday = $row->cal_ymd;
        list($wkdy, $dt) = explode("|", date("l|jS", strtotime($row->cal_ymd)));
?>
            <?php echo "$wkdy, the $dt"; ?>
<?php
      }
?>
            </span>
          </td>
          <td><img src="images/spacer.gif" width="10" height="1"></td>
          <td class="fg5" width="50"><span style="color:<?php echo $color; ?>"><?php echo "$time "; ?></span></td>
          <td><img src="images/spacer.gif" width="10" height="1"></td>
          <td class="fg5">
            <span style="color:<?php echo $color; ?>"> 
<?php 
      if ($row->cal_ccid) { 
?>
            <b><?php echo $row->cc_category; ?></b>.  
<?php 
      } 
?>
<?php 
      if ($row->cal_item) { 
?>
            <b><?php echo $row->cal_item; ?></b>.
<?php 
      } 
?>
            <?php echo preg_replace("/\r\n/", "<br>", $row->cal_description); ?><br>
            <img src="images/spacer.gif" width="1" height="6"><br>
            </span>
          </td>
        </tr>
<?php
    }
?>
        <tr bgcolor="<?php echo $borcol; ?>">
          <td colspan="5"><img src="images/spacer.gif" width="1" height="1"></td>
        </tr>
<?php
    if ($m >= 12) {
      $m = 1;
      $y++;
    }
    else {
      $m++;
    }
    $m = sprintf("%02d", $m);
  }
?>
      </table>
    </td>
<!-- END TEXT STYLE CALENDAR CODE -->
<?php } ?>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
