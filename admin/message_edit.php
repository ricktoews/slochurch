<?php
include("path.inc");
include("includes/header.inc");

$arg = "";
$id = $_REQUEST["id"];
$sql = "SELECT * FROM site_schedule_weeks " .
       "WHERE sw_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $date = $row->sw_date;
  $sw_date = date("F j", strtotime($date));
  $sw_description = $row->sw_description;
  $sw_audio = $row->sw_audio;
}
?>

<script language="JavaScript">
function updateMessage() {
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
<form name="data" method="post" action="save_message.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="2" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<div align="center">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Message for <?php echo $sw_date; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Description</td>
    <td class="fg2">Audio</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><?php echo $sw_description; ?></td>
    <td class="fg5">
      <input type="file" name="upload_audio" /><br />
<?php
if ($sw_audio && file_exists($MESSAGE_PATH . '/' . $sw_audio)) {
?>
      <?php echo $sw_audio; ?><br />
      <input type="checkbox" name="del_audio" value="1" /> Delete this audio file
<?php
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <input type="button" value="Update" onclick="updateMessage()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <p>Return to <a href="messages.php">Past Weekly Messages</a>.</p>
    </td>
  </tr>
</table>
</div>
</form>
<?php
include("includes/footer.inc");
?>

