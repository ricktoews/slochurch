<?php
include("path.inc");
include("includes/header.inc");
$IMAGE_PATH =& $_REQUEST['IMAGE_PATH'];

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
if ($arg == "1") {
  $Member = new Members($id);
}
?>
<script language="JavaScript">
function getMember() {
  var f = document.forms["data"];
  f.arg.value = "1";
  f.action = "members.php";
  f.submit();
}

function newMember() {
  location = "members.php";
}

function updateMember() {
  var f = document.forms["data"];
  f.arg.value = "2";
  f.submit();
}

function deleteMember() {
  if (confirm("Are you sure you want to delete this?")) {
    var f = document.forms["data"];
    f.arg.value = "4";
    f.submit();
  }
}

function initFocus() {
  document.forms["data"].first.focus();
}

onload=initFocus;
</script>
<div align="center">
<form name="data" method="post" action="save_members.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Member List Maintenance</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Name</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2">
      <select name="id" onchange="getMember()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_members " .
       "ORDER BY m_last, m_first";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->m_id == $id ? "selected" : "";
  $listitem = format_name($row->m_first, $row->m_last);
  echo "<option $selected value='$row->m_id'>$listitem</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newMember()" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">First name</td>
    <td class="fg2">Last name</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="first" value="<?php echo $Member->first; ?>" size="20" /></td>
    <td class="fg5"><input type="text" name="last" value="<?php echo $Member->last; ?>" size="20" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Email</td>
    <td class="fg2">Email Format</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="email" value="<?php echo $Member->email; ?>" size="30" /></td>
    <td class="fg5">
<?php $checked = $Member->emailformat == "H" ? "checked" : ""; ?>
      <input type="radio" name="emailformat" <?php echo $checked; ?> value="H" /> HTML / 
<?php $checked = $Member->emailformat != "H" ? "checked" : ""; ?>
      <input type="radio" name="emailformat" <?php echo $checked; ?> value="T" /> Plain Text
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Information</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan=5>
      <textarea name="about" rows="5" cols="60" wrap="physical"><?php echo $Member->about; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Upload Picture</td>
    <td class="fg2">Existing Picture</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="file" name="upload_image"><input type="hidden" name="image" value="<?php echo $Member->image; ?>">
    </td>
    <td class="fg5">
<?php
if ($Member->image && file_exists($IMAGE_PATH . '/' . $Member->image)) {
?>
      <img src="<?php echo $IMAGE_PATH; ?>/<?php echo $Member->image; ?>" border="0" /><br />
      <?php echo $Member->image; ?><br />
      <input type="checkbox" name="del_image" value="1" /> Delete this picture
<?php
}
else {
?>
      None
<?php
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2">
<?php if (!$id) { ?>
      <input type="button" value="Add" onclick="updateMember()" />
<?php } else { ?>
      <input type="button" value="Update" onclick="updateMember()" />
      <input type="button" value="Delete" onclick="deleteMember()" />
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>

