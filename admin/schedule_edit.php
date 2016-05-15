<?php
include("path.inc");
include("includes/header.inc");

$SCHEDULE_PATH =& $_REQUEST["SCHEDULE_PATH"];

$arg = "";
$id = $_REQUEST["id"];
$sql = "SELECT * FROM site_schedule_weeks " .
       "WHERE sw_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $date = $row->sw_date;
  $sw_date = date("F j", strtotime($date));
  $sw_description = $row->sw_description;
  $sw_image = $row->sw_image;
}
?>

<script language="JavaScript">
function updateSchedule() {
  var f = document.forms["data"];
  f.submit();
}

function clearInfo(id) {
  var f = document.forms["data"];
  if (id) {
    f.elements["info_" + id].value = "";
  }
}

function setInfo(fld, id) {
  var f = document.forms["data"];
  if (id) {
    var name = fld.options[fld.selectedIndex].text;
    if (f.elements["info_" + id].value.length == 0) {
      f.elements["info_" + id].value = name;
    }
    else {
      f.elements["info_" + id].value += ", " + name;
    }
  }
}

function initFocus() {
  document.forms["data"].description.focus();
}

onload=initFocus;
</script>
<form name="data" method="post" action="save_schedule.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="2" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<div align="center">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Schedule for <?php echo $sw_date; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Description</td>
    <td class="fg2">Image</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type="text" name="description" value="<?php echo $sw_description; ?>" size="50" /></td>
    <td class="fg5">
      <input type="file" name="upload_image" /><br />
<?php
if ($sw_image && file_exists($SCHEDULE_PATH . '/' . $sw_image)) {
?>
      <img src="<?php echo $SCHEDULE_PATH; ?>/<?php echo $sw_image; ?>" border="0" /><br />
      <?php echo $sw_image; ?><br />
      <input type="checkbox" name="del_image" value="1" /> Delete this graphic
<?php
}
?>
    </td>
  </tr>
<?php
$sql = "SELECT * FROM site_schedule_labels " .
       "LEFT JOIN site_schedule_information ON si_slid=sl_id AND si_date='$date' " .
       "ORDER BY sl_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $sl_id = $row->sl_id;
  $sl_label = $row->sl_label;
  $officeids = $row->sl_offices ? explode("|", $row->sl_offices) : array();
  $offices = implode(',', $officeids);
  $si_info = $row->si_info;
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2"><?php echo $sl_label; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <input type="text" name="info_<?php echo $sl_id; ?>" value="<?php echo $si_info; ?>" size="50" />
      <input type="button" value="Clear" onclick="clearInfo('<?php echo $sl_id; ?>')" />
      &nbsp; &nbsp; Assign 
      <select name="members_<?php echo $sl_id; ?>" onchange="setInfo(this, '<?php echo $sl_id; ?>')">
      <option value="">Select</option>
<?php
  if (sizeof($officeids)) {
    $sql2 = "SELECT * FROM site_members, site_staff " .
            "WHERE s_memberid=m_id " .
            "  AND s_officeid IN ($offices) ";
  }
  else {
    $sql2 = "SELECT * FROM site_members ";
  }
  $sql2 .=
          "ORDER BY m_last, m_first";
  $result2 = mysql_query($sql2, $connect);
  while ($row2 = mysql_fetch_object($result2)) {
    $selected = $row2->m_id == $id ? "selected" : "";
    $listitem = format_name($row2->m_first, $row2->m_last);
    echo "<option $selected value='$row2->m_id'>$listitem</option>\n";
  }
?>
      </select>
    </td>
  </tr>

<?php
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <input type="button" value="Update" onclick="updateSchedule()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <p>Return to <a href="schedule.php">Weekly Schedule</a>.</p>
    </td>
  </tr>
</table>
</div>
</form>
<?php
include("includes/footer.inc");
?>

