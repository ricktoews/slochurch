<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$datetime = date("Y-m-d h:i:s");

if ($arg == "1") {
  $sql = "SELECT * FROM site_content " .
         "WHERE cont_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $tag = $row->cont_tag;
    $metatitle = $row->cont_metatitle;
    $metadescription = $row->cont_metadescription;
    $metakeywords = $row->cont_metakeywords;
    $text = $row->cont_text;
    $layout = $row->cont_layout;
    $date = $row->cont_date;
  }
}
?>
  <script language="JavaScript">
  function getContent() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "content.php";
    f.submit();
  }

  function updateContent() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function newContent() {
    location = "content.php";
  }

  function initFocus() {
    var f = document.forms[0];
    f.metatitle.focus();
  }
  </script>
<form method=post action="save_content.php">
<input type=hidden name="arg" value="1">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr valign=top bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Content Setup</th>
  </tr>
  <tr valign=top bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Select the page whose content you want to modify</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="id" onchange="getContent()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_content " .
       "ORDER BY cont_metatitle";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->cont_id == $id ? "selected" : "";
  echo "<option $selected value='$row->cont_id'>$row->cont_metatitle</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newContent()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Title</td>
    <td class="fg2">Page Layout</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="metatitle" size="50" value="<?php echo $metatitle; ?>"></td>
    <td class="fg5">
<?php $checked = $layout == "1" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="layout" value="1"> Content with side bar
<?php $checked = $layout == "2" ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="layout" value="2"> Wide content (no side bar)
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Meta Keywords</td>
    <td class="fg2">Meta Description</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><textarea name="metakeywords" rows="5" cols="40" wrap="physical"><?php echo $metakeywords; ?></textarea></td>
    <td class="fg5"><textarea name="metadescription" rows="5" cols="40" wrap="physical"><?php echo $metadescription; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Content</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><textarea name="text" rows="10" cols="80" wrap="physical"><?php echo $text; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2"><input type=button value="Update" onclick="updateContent()"></td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
