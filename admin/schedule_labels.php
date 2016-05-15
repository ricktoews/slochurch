<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_schedule_labels " .
         "WHERE sl_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $label = $row->sl_label;
    $officeids = explode("|", $row->sl_offices);
    $seq = $row->sl_seq;
  }
  $sql = "SELECT MAX(sl_seq) AS follow FROM site_schedule_labels " .
         "WHERE sl_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getLabel() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "schedule_labels.php";
    f.submit();
  }

  function newLabel() {
    location = "schedule_labels.php";
  }

  function updateLabel() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteLabel() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms[0];
    f.label.focus();
  }

  onload=initFocus;
  </script>
<div align=center>
<form name="data" method="post" action="save_schedule_labels.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">Schedule Labels</th>
  </tr>
<?php if ($inuse) { ?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="4">
      The label you selected is in use and so cannot be deleted.
    </td>
  </tr>
<?php } ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Schedule Labels</td>
    <td class="fg2">Label</td>
    <td class="fg2">Staff Position</td>
    <td class="fg2">Label this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <select name="id" onchange="getLabel()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_schedule_labels " .
       "ORDER BY sl_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->sl_id == $id ? "selected" : "";
  echo "<option $selected value='$row->sl_id'>$row->sl_label</option>\n";
}
?>
      </select>
      <input type=button value="New" onclick="newLabel()">
    </td>
    <td class="fg5">
      <input type=text name="label" value="<?php echo $label; ?>" size="20" />
    </td>
    <td class="fg5">
      <select name="officeid[]" size="5" multiple>
      <option value="">Any</option>
<?php
$sql = "SELECT * FROM site_offices " .
       "ORDER BY o_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = array_search($row->o_id, $officeids) !== FALSE ? "selected" : "";
  echo "<option $selected value='$row->o_id'>$row->o_office</option>\n";
}
?>
      </select>
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_schedule_labels " .
       "ORDER BY sl_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->sl_id == $id) {
    continue;
  }
  $selected = $row->sl_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->sl_id'>$row->sl_label</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="4">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateLabel()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateLabel()">
      <input type=button value="Delete" onclick="deleteLabel()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
