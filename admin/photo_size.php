<?php
include("path.inc");
include("includes/header.inc");

$img = $_REQUEST["img"];
$img_file = "pics/$img";

$imgsize = filesize($img_file);
$im = ImageCreateFromJPEG($img_file);
$original_width = ImageSX($im);
$original_height = ImageSY($im);
if (!$new_width) {
  $new_width = $original_width;
  $new_height = $original_height;
}
else {
  $new_height = $new_width/$original_width * $original_height;
}
?>
<script language="JavaScript">
function resize() {
  var f = document.forms[0];
  var w = f.new_width;
  f.new_height.value = w / <?php echo $original_width; ?> * <?php echo $original_height; ?>;
  f.submit();
}

function save() {
  var f = document.forms[0];
  f.action = "photo_save.php";
  f.submit();
}
</script>
</head>
<div align="center">
<form method="post" action="photo_size.php">
<input type="hidden" name="img" value="<?php echo $img; ?>" />
<input type="hidden" name="new_height" value="<?php echo $new_height; ?>" />
<input type="hidden" name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Photo Resize</th>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3"><b>Important</b>: You must click the Save button to complete the upload process.</td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Caption</td>
    <td class="fg2">Gallery</td>
    <td class="fg2">Desired width</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="caption" value="<?php echo $caption; ?>" size="30" /></td>
    <td class="fg5">
      <select name="rid">
      <option value="">Select attraction</option>
<?php
$lastclassid = 0;
$sql = "SELECT * FROM site_rides, site_classifications " .
       "WHERE r_classid=class_id " .
       "ORDER BY class_seq, r_name";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($lastclassid != $row->class_id) {
    echo "<option value=''>- $row->class_name</option>\n";
    $lastclassid = $row->class_id;
  }
  echo "<option value='$row->r_id'>$row->r_name</option>\n"; 
}
?>
      </select>
    </td>
    <td class="fg5">
      <select name="new_width">
<?php
$minimum_width = $original_width > 100 ? 100 : $original_width;
for ($i = $minimum_width; $i <= $original_width; $i++) {
  $selected = $i == $original_width ? "selected" : "";
  echo "<option $selected value='$i'>$i</option>\n";
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3">
      <input type="button" value="Resize" onclick="resize()" />
      <input type="button" value="Save" onclick="save()" />
    </td>
  </tr>
</table>
</form>
<table>
  <tr>
    <td align=center class="cell">
      Original size: <?php echo $original_width; ?> x <?php echo $original_height; ?>
      <p>
<?php
if ($arg == "1") {
  $new_im = ImageCreate($new_width, $new_height);
  $new_image = ImageCopyResized($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
  ImageJPEG($new_im, "pics/new_$img", 60);
  $imgsize = filesize("pics/new_$img");
  echo "Width: $new_width x $new_height<br>";
  echo "Quality: 60, Size: $imgsize<br>";
  echo "<img src='pics/new_$img' width='$new_width' height='$new_height' border='1'><br>";
  ImageDestroy($new_im);
}
else {
  echo "<img src='$img_file' width='$original_width' height='$original_height' border='1'><br>";
}
ImageDestroy($im);
?>
      <?php echo $caption; ?>
    </td>
  </tr>
</table>
</div>
<?php
include("includes/footer.inc");
?>
