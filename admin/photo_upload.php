<?php
include("path.inc");
include("includes/header.inc");

?>
<script language="JavaScript">
galleries = new Array();
<?php
$i = 0;
$sql = "SELECT * FROM site_photos " .
       "ORDER BY p_galleryid, p_seq";
$result = mysql_query($sql, $connect);
$lastgalleryid = -1;
while ($row = mysql_fetch_object($result)) {
  $p_caption = addslashes($row->p_caption);
  if ($row->p_galleryid != $lastgalleryid) {
    $lastgalleryid = $row->p_galleryid;
    $i = 0;
    echo "galleries[$row->p_galleryid] = new Array();\n";
  }
  echo "galleries[$row->p_galleryid][$i] = '$row->p_id|$p_caption';\n";
  $i++;
}
?>

function upload() {
  var f = document.forms["data"];
  if (f.galleryid.selectedIndex == 0) {
    alert("Please select a gallery for the photo you want to upload.");
  }
  else {
    f.submit();
  }
}

function followList() {
  var f = document.forms["data"];
  var g_id = f.galleryid.options[f.galleryid.selectedIndex].value;
  f.followid.options.length = 1;
  if (galleries[g_id]) {
    for (i = 0; i < galleries[g_id].length; i++) {
      parts = galleries[g_id][i].split("|");
      value = parts[0];
      text = parts[1];
      f.followid.options[i+1] = new Option(text, value);
    } 
  } 
}
</script>
</head>
<div align="center">
<form name="data" method="post" action="save_photo_upload.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">Photo Upload</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Specify image to upload</td>
    <td class="fg2">Gallery</td>
    <td class="fg2">Caption</td>
    <td class="fg2">Photo this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="file" name="photo" /></td>
    <td class="fg5">
      <select name="galleryid" onchange="followList()">
<?php
echo pm_gallery_dropdown($galleryid);
?>
      </select>
    </td>
    <td class="fg5"><input type="text" name="caption" value="<?php echo $caption; ?>" size="30" /></td>
    <td class="fg5">
      <select name="followid">
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
    <td align="center" class="fg5" colspan="4">
      <input type="button" value="Upload" onclick="upload()" />
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
