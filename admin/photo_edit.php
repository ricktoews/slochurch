<?php
include("path.inc");
include("includes/header.inc");

$arg = "";
$id = $_REQUEST["id"];
$sql = "SELECT * FROM site_photos " .
       "LEFT JOIN site_photo_galleries ON pg_id=p_galleryid " .
       "WHERE p_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $arg = 1;
  $id = $row->p_id;
  $caption = $row->p_caption;
  $photo = $row->p_photo;
  $width = $row->p_width;
  $height = $row->p_height;
  $galleryid = $row->p_galleryid;
  $gallery = $row->pg_gallery;
  $seq = $row->p_seq;
  $display = $row->p_display;
}
$sql = "SELECT MAX(p_seq) AS follow FROM site_photos " .
       "WHERE p_seq < '$seq' " .
       "  AND p_galleryid = '$galleryid'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $follow = $row->follow;
}
?>
<script language="JavaScript">
function updatePhoto() {
  var f = document.forms[0];
  f.submit();
}

function deletePhoto() {
  if (confirm("Are you sure you want to delete this?")) {
    var f = document.forms[0];
    f.arg.value = "4";
    f.submit();
  }
}
</script>
<form method="post" action="save_photo_edit.php">
<input type="hidden" name="arg" value="2" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="orig_galleryid" value="<?php echo $galleryid; ?>" />
<input type="hidden" name="photo" value="<?php echo $photo; ?>" />
<div align="center">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Photo Manager - <?php echo "$caption ($attraction)"; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Photo</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="3">
      <img src="<?php echo "$GALLERY_PATH/$galleryid/$photo"; ?>" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Caption</td>
    <td class="fg2">Attraction</td>
    <td class="fg2">Photo this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5;?>">
    <td class="fg5"><input type="text" name="caption" value="<?php echo preg_replace('/"/', "&quot;", $caption); ?>" size="35" /></td>
    <td class="fg5">
      <select name="galleryid">
<?php
echo pm_gallery_dropdown($galleryid);
?>
      </select>
    </td>
    <td class="fg5">
      <select name="followid" class="cell">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_photos " .
       "WHERE p_galleryid='$galleryid' " .
       "ORDER BY p_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->p_id == $id) {
    continue;
  }
  $selected = $row->p_seq == $follow ? "selected" : "";
  $label = $row->p_caption ? $row->p_caption : $row->p_photo;
  echo "<option $selected value='$row->p_id'>$label</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3">
<?php if (!$arg) { ?>
      <input type="button" value="Add" onclick="updatePhoto()">
<?php } else { ?>
      <input type="button" value="Update" onclick="updatePhoto()">
      <input type="button" value="Delete" onclick="deletePhoto()">
<?php } ?>
    </td>
  </tr>
</table>
<p class="cell"><a href="photo_manager.php?galleryid=<?php echo $galleryid; ?>">Photo Manager</a></p>
</div>
</form>
<?php
include("includes/footer.inc");
?>
