<?php
include("path.inc");
include("includes/header.inc");

$SCHEDULE_PATH =& $_REQUEST["SCHEDULE_PATH"];

$startdate = date("Y-m-d", mktime() + 86400 * (6 - date("w")));
$weeksahead = 4;
?>
<script language="JavaScript">
function editSchedule(id) {
  if (id) {
    location = "schedule_edit.php?id=" + id;
  }
}
</script>
<div align=center>
<form method=post action="save_schedule.php">
<input type=hidden name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Weekly Schedule</th>
  </tr>
<?php
$r = 0;
$date = $startdate;
list($y,$m,$d) = explode("-", $date);
for ($i = 0; $i < $weeksahead; $i++) {
  $date = date("Y-m-d", mktime(0,0,0,$m,$d,$y)); 
  $sql = "SELECT * FROM site_schedule_weeks " .
         "WHERE sw_date='$date'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $id = $row->sw_id;
    $sw_image = $row->sw_image;
  }
  else {
    $sql = "INSERT INTO site_schedule_weeks " .
           "SET sw_date='$date'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  $sw_date = date("F j", strtotime($date));
  $sw_description = $row->sw_description;
  $sql = "SELECT * FROM site_schedule_information, site_schedule_labels " .
         "WHERE si_slid=sl_id " .
         "  AND si_date='$date' " .
         "ORDER BY sl_seq";
  $result = mysql_query($sql, $connect);
  $infoArray = array();
  while ($row = mysql_fetch_object($result)) {
    $sl_label = $row->sl_label;
    $si_info = $row->si_info ? $row->si_info : "<i>Unassigned</i>";
    $infoArray[] = "<b>$sl_label</b>: $si_info";
  }
  $info = sizeof($infoArray) > 0 ? $info = implode("<br />", $infoArray) : "Nothing scheduled";
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">
      <input type="hidden" name="id[]" value="<?php echo $id; ?>">
      <b><?php echo $sw_date; ?></b> - <?php echo $sw_description; ?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
<?php
if ($sw_image && file_exists($SCHEDULE_PATH . '/' . $sw_image)) {
?>
      <img src="<?php echo $SCHEDULE_PATH; ?>/<?php echo $sw_image; ?>" border="0" /><br />
      <?php echo $sw_image; ?><br />
<?php
}
?>
    </td>
    <td class="fg5" width="99%">
      <?php echo $info; ?>
    </td>
    <td class="fg5">
      <input type="button" value="Edit" onclick="editSchedule('<?php echo $id; ?>')" />
    </td>
  </tr>
<?php
  $r++;
  $d += 7;
}
?>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
