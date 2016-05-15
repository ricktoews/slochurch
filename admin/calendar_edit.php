<?php
include("path.inc");
include("includes/lib_admin.inc");
include("includes/colors.inc");

$arg = $_REQUEST["arg"];
$ccid = $_REQUEST["ccid"];
$catlist = get_categories($ccid);
if ($ccid) {
  array_unshift($catlist, $ccid);
}
$ymd = $_REQUEST["ymd"];
$calid = $_REQUEST["calid"];
$seq = $_REQUEST["seq"];
$item = $_REQUEST["item"];
$description = $_REQUEST["description"];
$refresh = $_REQUEST["refresh"];

$sql = "SELECT cc_category FROM site_calendar_categories " .
       "WHERE cc_id='$ccid'";
$result = check_mysql($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $cc_category = $row->cc_category;
}

// If a particular item has been selected, look it up.
$eventdate = date("F j, Y", strtotime($ymd)); 
if ($arg == "1") {
  $sql = "SELECT * FROM site_calendar " .
         "WHERE cal_id='$calid'";
  $result = check_mysql($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $eventdate = date("F j, Y", strtotime($row->cal_ymd)); 
    $item = $row->cal_item;
    $ccid = $row->cal_ccid;
    if ($row->cal_starttime) {
      list($starthour, $startminute) = explode(":", $row->cal_starttime);
      if ($starthour > 12) {
        $startampm = "pm";
        $starthour -= 12;
      }
      elseif ($starthour == 12) {
        $startampm = "pm";
      }
      else {
        $startampm = "am";
        $starthour = $starthour < 1 ? "12" : $starthour;
      }
      $unscheduled = "";
    }
    else {
      $unscheduled = "checked"; 
    }
    if ($row->cal_endtime) {
      list($endhour, $endminute) = explode(":", $row->cal_endtime);
      if ($endhour > 12) {
        $endampm = "pm";
        $endhour -= 12;
      }
      elseif ($endhour == 12) {
        $endampm = "pm";
      }
      else {
        $endampm = "am";
        $endhour = $endhour < 1 ? "12" : $endhour;
      }
      $unscheduled = "";
    }
    $description = $row->cal_description;
  }
}
?>
<html>
<head>
  <title><?php echo $am_name; ?> - Calendar Edit</title>
  <style type="text/css">
<?php include("style.css"); ?>
  </style>
  <script language="JavaScript">
  seq = "<?php echo $seq; ?>";
  </script>
  <script language="JavaScript">
  function newItem() {
    location = "calendar_edit.php?ccid=<?php echo $ccid; ?>&ymd=<?php echo $ymd; ?>";
  }

  function changeItem(seq) {
    if (!seq) return;
    var f = document.forms[0];
    f.seq.value = f.calid.options[seq].value;
    f.action = "calendar_edit.php";
    f.arg.value = "1";
    f.submit();
  }  

  function update() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function del() {
    var f = document.forms[0];
    f.arg.value = "4";
    f.submit();
  }

  function schedule(on) {
    var f = document.forms[0];
    if (on) {
      f.unscheduled.checked = false;
    }
    else {
      f.starthour.selectedIndex = 0;
      f.startminute.selectedIndex = 0;
      f.startampm[0].checked = false;
      f.startampm[1].checked = false;
      f.endhour.selectedIndex = 0;
      f.endminute.selectedIndex = 0;
      f.endampm[0].checked = false;
      f.endampm[1].checked = false;
    }
  }
  </script>
<?php
if ($refresh) {
?>
  <script language="JavaScript">
  opener.location.reload();
  self.close();
  </script>
<?php
}
?>
</head>
<body bgcolor="<?php echo $bgcolor; ?>" link="<?php echo $linkCol; ?>" alink="<?php echo $alinkCol; ?>" vlink="<?php echo $vlinkCol; ?>">
<form method=post action="save_calendar_edit.php">
<input type="hidden" name="arg" value="">
<input type="hidden" name="ccid" value="<?php echo $ccid; ?>">
<input type="hidden" name="ymd" value="<?php echo $ymd; ?>">
<input type="hidden" name="seq" value="<?php echo $seq; ?>">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1"><?php echo "$cc_category - $eventdate"; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Previously Entered Item</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="calid" onchange="changeItem(this.selectedIndex)">
      <option value="">Select</option>
<?php
  $sql = "SELECT * FROM site_calendar " .
         "WHERE cal_ccid IN (" . implode(",", $catlist) . ") " .
         "  AND cal_ymd='$ymd' " .
         "ORDER BY cal_starttime, cal_endtime";
  $result = check_mysql($sql, $connect);
  $items = mysql_num_rows($result);
  while ($row = mysql_fetch_object($result)) {
    $event = $row->cal_item;
    $selected = $row->cal_id == $calid ? "selected" : "";
    echo "<option $selected value='$row->cal_id'>$event</option>\n";
  }
?>
      </select>
      <input type="button" value="New" onclick="newItem()">
    </td>
  </tr>
</table>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Calendar Item</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="item" value="<?php echo $item; ?>" size="40"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Start Time</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="starthour" onchange="schedule(1)">
<?php
for ($i = 1; $i <= 12; $i++) {
  $hr = sprintf("%02d", $i);
  $selected = $i == $starthour ? "selected" : "";
  echo "<option $selected value='$hr'>$i</option>\n";
}
?>
      </select>
      :
      <select name="startminute" onchange="schedule(1)">
<?php
for ($i = 0; $i <= 59; $i++) {
  $min = sprintf("%02d", $i);
  $selected = $i == $startminute ? "selected" : "";
  echo "<option $selected value='$min'>$min</option>\n";
}
?>
      </select>
<?php $checked = $startampm == "am" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="startampm" value="am" onclick="schedule(1)"> am /
<?php $checked = $startampm == "pm" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="startampm" value="pm" onclick="schedule(1)"> pm
      , OR 
      <input type="radio" name="unscheduled" <?php echo $unscheduled; ?> value="1" onclick="schedule(0)"> Unscheduled
      <input type="hidden" name="starttime" value="<?php echo $starttime; ?>">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">End Time</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="endhour" onchange="schedule(1)">
<?php
for ($i = 1; $i <= 12; $i++) {
  $hr = sprintf("%02d", $i);
  $selected = $i == $endhour ? "selected" : "";
  echo "<option $selected value='$hr'>$i</option>\n";
}
?>
      </select>
      :
      <select name="endminute" onchange="schedule(1)">
<?php
for ($i = 0; $i <= 59; $i++) {
  $min = sprintf("%02d", $i);
  $selected = $i == $endminute ? "selected" : "";
  echo "<option $selected value='$min'>$min</option>\n";
}
?>
      </select>
<?php $checked = $endampm == "am" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="endampm" value="am" onclick="schedule(1)"> am /
<?php $checked = $endampm == "pm" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="endampm" value="pm" onclick="schedule(1)"> pm
      <input type="hidden" name="endtime" value="<?php echo $endtime; ?>">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" width="99%">Description</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><textarea name="description" rows="5" cols="40" wrap="physical"><?php echo $description; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5">
<?php if (!$arg) { ?>
      <input type="button" value="Add" onclick="update()">
<?php } else { ?>
      <input type="button" value="Update" onclick="update()">
      <input type="button" value="Delete" onclick="del()">
<?php } ?>
    </td>
  </tr>
</table>
<input type="hidden" name="items" value="<?php echo $items; ?>">
</form>
</body>
</html>

