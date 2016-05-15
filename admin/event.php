<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$ccid = $_REQUEST["ccid"];
$inuse = $_REQUEST["inuse"];

$monqtr = "M";
if (date("m") == 12) {
  $daterange1 = "1/1/" . (date("Y") - 1);
  $daterange2 = "12/31/" . (date("Y") - 1);
}
else {
  $daterange1 = "1/1/" . date("Y");
  $daterange2 = "12/31/" . date("Y");
}

if ($arg == "1" && $id) {
  $sql = "SELECT * FROM site_calendar_events " .
         "WHERE ce_id='$id'";
  $result = check_mysql($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $event = preg_replace('/"/', "&quot;", $row->ce_event);
    $seqday = $row->ce_seqday;
    $day = $row->ce_day;
    $monqtr = $row->ce_monqtr;
    $starttime = $row->ce_starttime;
    $endtime = $row->ce_endtime;
    $startdate = $row->ce_startdate;
    list($y,$m,$d) = explode("-", $startdate);
    $daterange1 = "$m/$d/$y";
    $enddate = $row->ce_enddate;
    list($y,$m,$d) = explode("-", $enddate);
    $daterange2 = "$m/$d/$y";
  }
}

function parsetime($str) {
  list($h, $m) = explode(":", $str);
  $ampm = "am";
  if ($h >= 12) {
    $ampm = "pm";
    if ($h > 12) {
      $h -= 12;
    }
  }
  $timeparts = array($h, $m, $ampm);
  return $timeparts;
}
?>
<script language="JavaScript">
function getCategory() {
  var f = document.forms[0];
  f.arg.value = "1";
  f.action = "event.php";
  f.submit();
}

function getEvent() {
  var f = document.forms[0];
  f.arg.value = "1";
  f.action = "event.php";
  f.submit();
}

function newCategory() {
  location = "event.php";
}

function newEvent() {
  location = "event.php?ccid=<?php echo $ccid; ?>";
}

function updateEvent() {
  var f = document.forms[0];
  if (f.event.value.length == 0) {
    alert("You must select a event to update.");
    return;
  }
  if (f.event.value.length == 0) {
    alert("The event cannot be left blank.\nIf you want to delete a event, use the Delete button.");
    return;
  }
  f.arg.value = "2";
  f.submit();
}

function deleteEvent() {
  if (confirm("Are you sure?  This will delete any calendar items associated with this event.")) {
    var f = document.forms[0];
    if (f.id.selectedIndex == 0) {
      alert("You must select a event to update.");
      return;
    }
    f.arg.value = "4";
    f.submit();
  }
}

function initFocus() {
  var f = document.forms[0];
  f.ccid.focus();
}

function schedule(which, on) {
  var f = document.forms[0];
  if (on) {
    if (f.unscheduled.checked) {
      if (f.elements[which + "hour"].selectedIndex < 8) {
        f.elements[which + "ampm"][1].checked = true;
      }
      else {
        f.elements[which + "ampm"][0].checked = true;
      }
    }
    f.unscheduled.checked = false;
  }
  else {
    f.elements[which + "hour"].selectedIndex = 0;
    f.elements[which + "minute"].selectedIndex = 0;
    f.elements[which + "ampm"][0].checked = false;
    f.elements[which + "ampm"][1].checked = false;
  }
}

onload=initFocus;
</script>
<script language="JavaScript">
months = new Array(
<?php
  $m = 1 * date("n");
  $y = 1 * date("Y");
  for ($i = 0; $i < 36; $i++) {
    $moyr = date("F, Y", mktime(0,0,0,$m+$i,1,$y));
    echo "'$moyr',\n";
  }
?>
  ''
);
      months.length--;
      months_v = new Array(
<?php
  $m = 1 * date("n");
  $y = 1 * date("Y");
  for ($i = 0; $i < 36; $i++) {
    $moyr = date("n/t/Y", mktime(0,0,0,$m+$i,1,$y));
    echo "'$moyr',\n";
  }
?>
  ''
);
months_v.length--;



function setMonthRangeOptions(fld) {
  var f = document.forms[0];
  var fld2 = f.monthrange2;
  var temp2 = f.monthrange2.options[f.monthrange2.selectedIndex].value;
  fld2.options.length = 0;
  var m1 = fld.options[fld.selectedIndex].value;
  var parts = m1.split("/");
  var m = parts[0];
  var d = parts[1];
  var y = parts[2];
  var start = 1 + 1 * m;
  for (var i = 0; i < 12; i++) {
    fld2.options[i] = new Option(months[i+start], months_v[i+start]); 
    if (months_v[i+start] == temp2) {
      fld2.selectedIndex = i;
    }
  } 
}

function clearMonthRange() {
  var f = document.forms[0];
  f.monthrange1.selectedIndex = 0;
  f.monthrange2.options.length = 2;
  f.monthrange2.options[0] = new Option("", "");
  f.monthrange2.options[1] = new Option("List of months", "");
}

function setDateRangeOptions(fld) {
  var f = document.forms[0];
  clearMonthRange();
}

function clearDateRange() {
  var f = document.forms[0];
  f.daterange1.value = "";
  f.daterange2.value = "";
}

function setRange(n) {
  var f = document.forms[0];
  var today = new Date();
  var y = today.getYear(); 
  var nexty = y + 1;
  if (n == 1) {
    var start = "1/1/" + y;
    var end = "12/31/" + y;
  }
  else if (n == 2) {
    var start = "1/1/" + nexty;
    var end = "12/31/" + nexty;
  }
  else if (n == 3) {
    var start = f.monthrange1.options[f.monthrange1.selectedIndex].value;
    var end = f.monthrange2.options[f.monthrange2.selectedIndex].value;
    initMonthRange();
  }
  f.daterange1.value = start;
  f.daterange2.value = end;
}

function initMonthRange() {
  var f = document.forms[0];
//  f.monthrange1.selectedIndex = 1;
  setMonthRangeOptions(f.monthrange1);
//  f.monthrange2.selectedIndex = 0;
}


function selectMonthQuarter(mq) {
  var f = document.forms[0];
  el = document.getElementById("qtrfreq");
  if (mq == "M") {
    el.style.display = "none";
  }
  else if (mq == "Q") {
    el.style.display = "";
  }
}


function checkEach(ea) {
  var f = document.forms[0];
  if (ea) {
    if (f.elements["each_ordinal[]"][0].checked) {
      for (var i = 1; i < f.elements["each_ordinal[]"].length; i++) {
        f.elements["each_ordinal[]"][i].checked = false;
      }
    }
  }
  else {
    f.elements["each_ordinal[]"][0].checked = false;
  }
}
</script>
<div align="center">
<form method="post" action="save_event.php">
<input type="hidden" name="arg" value="" />
<?php if ($new) { ?>
<input type="hidden" name="new" value="1" />
<?php } ?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Calendar Events</th>
  </tr>
<?php if ($inuse) { ?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      The event you selected is in use and so cannot be deleted.
    </td>
  </tr>
<?php } ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Choose a Calendar Category</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="ccid" onchange="getCategory()">
      <option value="">Select</option>
<?php
$match = array($ccid => 1);
show_subs(0, "", 1);
?>
      </select>
      <input type=button value="New" onclick="newCategory()">
    </td>
    <td></td>
  </tr>
<?php
if ($ccid) {
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Choose a Calendar Event</td>
    <td class="fg2">Enter or Edit the Calendar Event Name</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="id" onchange="getEvent()">
      <option value="">Select</option>
<?php
  $sql = "SELECT * FROM site_calendar_events " .
         "WHERE ce_ccid='$ccid' " .
         "ORDER BY ce_event";
  $result = check_mysql($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $selected = $row->ce_id == $id ? "selected" : "";
    echo "<option $selected value='$row->ce_id'>$row->ce_event</option>\n";
  }
?>
      </select>
      <input type=button value="New" onclick="newEvent()">
    </td>
    <td class="fg5"><input type=text name="event" value="<?php echo $event; ?>" size="40"></td>
    <td></td>
  </tr>
  <tr bgcolor="<?php echo $bg3; ?>">
    <th class="fg3" colspan="3">Schedule This Event</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">From</td>
    <td class="fg2">To</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="starthour" onchange="schedule('start', 1)">
<?php
  $timeparts = parsetime($starttime);
  $hour = $timeparts[0];
  $minute = $timeparts[1];
  $ampm = $timeparts[2];
  for ($i = 1; $i <= 12; $i++) {
    $hr = sprintf("%02d", $i);
    $selected = $i == $hour ? "selected" : "";
    echo "<option $selected value='$hr'>$i</option>\n";
  }
?>
      </select>
      :
      <select name="startminute" onchange="schedule('start', 1)">
<?php
  for ($i = 0; $i <= 59; $i++) {
    $min = sprintf("%02d", $i);
    $selected = $i == $minute ? "selected" : "";
    echo "<option $selected value='$min'>$min\n";
  }
?>
      </select>
<?php $checked = $ampm == "am" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="startampm" value="am" onclick="schedule('start', 1)"> am /
<?php $checked = $ampm == "pm" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="startampm" value="pm" onclick="schedule('start', 1)"> pm
    </td>
    <td class="fg5">
      <select name="endhour" onchange="schedule('end', 1)">
<?php
  $timeparts = parsetime($endtime);
  $hour = $timeparts[0];
  $minute = $timeparts[1];
  $ampm = $timeparts[2];
  for ($i = 1; $i <= 12; $i++) {
    $hr = sprintf("%02d", $i);
    $selected = $i == $hour ? "selected" : "";
    echo "<option $selected value='$hr'>$i\n";
  }
?>
      </select>
      :
      <select name="endminute" onchange="schedule('end', 1)">
<?php
  for ($i = 0; $i <= 59; $i++) {
    $min = sprintf("%02d", $i);
    $selected = $i == $minute ? "selected" : "";
    echo "<option $selected value='$min'>$min\n";
  }
?>
      </select>
<?php $checked = $ampm == "am" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="endampm" value="am" onclick="schedule('end', 1)"> am /
<?php $checked = $ampm == "pm" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="endampm" value="pm" onclick="schedule('end', 1)"> pm
    </td>
    <td class="fg5">
<?php $checked = ""; ?>
      <input type="radio" name="unscheduled" <?php echo $checked; ?> value="1" onclick="schedule('start', 0); schedule('end', 0)"> Unscheduled
      <input type="hidden" name="time" value="<?php echo $time; ?>">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Weekday</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
<?php
for ($i = 0; $i < 7; $i++) {
  $exp = pow(2, $i);
  $checked = $day & $exp ? "checked" : "";
  $wkday = date("l", mktime(0,0,0,1,$i+1,1978));
  echo "<input $checked type=\"checkbox\" name=\"each_weekday[]\" value=\"$exp\" /> $wkday ";
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Schedule This Event By</td>
    <td class="fg2" colspan="2">Frequency</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
<?php $checked = $monqtr == "M" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="monqtr" value="M" onclick="selectMonthQuarter('M')"> Month /
<?php $checked = $monqtr == "Q" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="monqtr" value="Q" onclick="selectMonthQuarter('Q')"> Quarter
    </td>
    <td class="fg5" colspan="2">
<?php 
$all = 31;
if ($monqtr == "Q") {
  $all = 8191;
  $display = "";
  $checked = $seqday == $all ? "checked" : "";
}
else {
  $all = 31;
  $display = "none";
  $checked = $seqday == $all ? "checked" : ""; 
}
?>
      <input <?php echo $checked; ?> type="checkbox" name="each_ordinal[]" onclick="checkEach(1)" value="<?php echo $all; ?>" /> Each
<?php
for ($i = 0; $i < 5; $i++) {
  $ord = $i + 1 . date("S", mktime(0,0,0,1,$i + 1,1978));
  $exp = pow(2, $i);
  $checked = $seqday < $all && $seqday & $exp ? "checked" : "";
  echo "<input $checked type=\"checkbox\" name=\"each_ordinal[]\" onclick=\"checkEach(0)\" value=\"$exp\" /> $ord";
}
?>
      <span id="qtrfreq" style="display:<?php echo $display; ?>">
<?php
for ($i = 5; $i < 13; $i++) {
  $ord = $i + 1 . date("S", mktime(0,0,0,1,$i + 1,1978));
  $exp = pow(2, $i);
  $checked = $seqday < $all && $seqday & $exp ? "checked" : "";
  echo "<input $checked type=\"checkbox\" name=\"each_ordinal[]\" onclick=\"checkEach(0)\" value=\"$exp\" /> $ord";
}
?>
      </span>
    </td>
  </tr>
</table>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Preset for</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="radio" name="rangetype" value="1" onclick="setRange(1)"> <span class="cell" />Current year</span></td>
    <td class="fg5"><input type="radio" name="rangetype" value="2" onclick="setRange(2)"> <span class="cell" />Next year</span></td>
    <td class="fg5"><span class="cell">Month range:</span>
      <select name="monthrange1" onchange="setMonthRangeOptions(this); setRange(3)">
      <option value=""></option>
<?php
  $m = 1 * date("n");
  $y = 1 * date("Y");
  for ($i = 0; $i < 12; $i++) {
    $moyr = date("F, Y", mktime(0,0,0,$m+$i,1,$y));
    $v = date("n/1/Y", mktime(0,0,0,$m+$i, 1, $y));
    echo "<option value='$v'>$moyr</option>\n";
  }
?>
      </select>
      
      <select name="monthrange2" onchange="setRange(3)">
      <option value=""></option>
      <option value="">List of months</option>
      </select>
      
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <span class="cell">Date range:</span>
      <input type="text" name="daterange1" value="<?php echo $daterange1; ?>" size="10" onclick="setDateRangeOptions(this)" onblur="formatDate(this)"> - 
      <input type="text" name="daterange2" value="<?php echo $daterange2; ?>" size="10" onblur="formatDate(this)">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateEvent()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateEvent()">
      <input type=button value="Delete" onclick="deleteEvent()">
<?php } ?>
    </td>
  </tr>
<?php
} // if ($id)
?>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
