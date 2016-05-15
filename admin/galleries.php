<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_photo_galleries " .
         "WHERE pg_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $gallery = $row->pg_gallery;
    $description = $row->pg_description;
    $seq = $row->pg_seq;
  }
  $sql = "SELECT MAX(pg_seq) AS follow FROM site_photo_galleries " .
         "WHERE pg_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getGallery() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "galleries.php";
    f.submit();
  }

  function newGallery() {
    location = "galleries.php";
  }

  function updateGallery() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteGallery() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms[0];
    f.gallery.focus();
  }

  onload=initFocus;
  </script>
<div align=center>
<form name="data" method="post" action="save_galleries.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">Gallery</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Gallery list</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="id" onchange="getGallery()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_photo_galleries " .
       "ORDER BY pg_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->pg_id == $id ? "selected" : "";
  $listitem = $row->pg_gallery;
  echo "<option $selected value='$row->pg_id'>$listitem</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newGallery()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Gallery</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <input type=text name="gallery" value="<?php echo $gallery; ?>" size="50" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Description</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <textarea name="description" rows="5" cols="80" wrap="physical"><?php echo $description; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Gallery this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_photo_galleries " .
       "ORDER BY pg_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->pg_id == $id) {
    continue;
  }
  $selected = $row->pg_seq == $follow ? "selected" : "";
  $listitem = $row->pg_gallery;
  echo "<option $selected value='$row->pg_id'>$listitem</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateGallery()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateGallery()">
      <input type=button value="Delete" onclick="deleteGallery()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
