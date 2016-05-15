<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_homepage " .
         "WHERE hp_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $heading = $row->hp_heading;
    $image = $row->hp_image;
    $text = $row->hp_text;
    $seq = $row->hp_seq;
  }
  $sql = "SELECT MAX(hp_seq) AS follow FROM site_homepage " .
         "WHERE hp_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getHomePage() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "homepage.php";
    f.submit();
  }

  function newHomePage() {
    location = "homepage.php";
  }

  function updateHomePage() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteHomePage() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms[0];
    f.heading.focus();
  }

  onload=initFocus;
  </script>
<div align=center>
<form name="data" method="post" action="save_homepage.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Home Page Content</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Home Page Items</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="id" onchange="getHomePage()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_homepage " .
       "ORDER BY hp_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->hp_id == $id ? "selected" : "";
  $listitem = substr($row->hp_heading, 0, 50);
  echo "<option $selected value='$row->hp_id'>$listitem</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newHomePage()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Heading</td>
    <td class="fg2">Image</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type=text name="heading" value="<?php echo $heading; ?>" size="50" /></td>
    <td class="fg5">
      <input type="file" name="upload_image" /><br />
<?php
if ($image && file_exists($HOME_PATH . '/' . $image)) {
?>
      <img src="<?php echo $HOME_PATH; ?>/<?php echo $image; ?>" border="0" /><br />
      <?php echo $image; ?><br />
      <input type="checkbox" name="del_image" value="1" /> Delete this image
<?php
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Text</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <textarea name="text" rows="5" cols="80" wrap="physical"><?php echo $text; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Item this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_homepage " .
       "ORDER BY hp_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->hp_id == $id) {
    continue;
  }
  $selected = $row->hp_seq == $follow ? "selected" : "";
  $listitem = substr($row->hp_heading, 0, 50);
  echo "<option $selected value='$row->hp_id'>$listitem</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateHomePage()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateHomePage()">
      <input type=button value="Delete" onclick="deleteHomePage()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
