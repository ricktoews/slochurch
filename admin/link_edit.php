<?php
include("path.inc");
include("includes/header.inc");

$arg = "";
$id = $_REQUEST['linkid'];
$sql = "SELECT * FROM site_links " .
       "WHERE l_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $arg = 1;
  $url = $row->l_url;
  $title = $row->l_title;
  $categoryid = $row->l_categoryid;
  $description = $row->l_description;
  $seq = $row->l_seq;
}
$sql = "SELECT MAX(l_seq) AS follow FROM site_links " .
       "WHERE l_seq < '$seq' " .
       "  AND l_categoryid = '$categoryid'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $follow = $row->follow;
}
?>

  <script language="JavaScript">
  function updateLink() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteLink() {
    var f = document.forms[0];
    f.arg.value = "4";
    f.submit();
  }
  </script>
<form method=post action="save_links.php">
<input type=hidden name="arg" value="2">
<input type=hidden name="id" value="<?php echo $id; ?>">
<div align=center>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Link Administration</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>" valign="top">
    <td class="fg2"><?php echo help_tag("URL"); ?></td>
    <td class="fg2"><?php echo help_tag("Title"); ?></td>
    <td class="fg2"><?php echo help_tag("Category"); ?></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="url" size="40" value="<?php echo $url; ?>" /></td>
    <td class="fg5"><input type=text name="title" value="<?php echo preg_replace('/"/', "&quot;", $title); ?>" size="35" /></td>
    <td class="fg5">
      <select name="categoryid">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_link_categories " .
       "ORDER BY lc_category";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->lc_id == $categoryid ? "selected" : "";
  echo "<option $selected value='$row->lc_id'>$row->lc_category</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>" valign="top">
    <td class="fg2" colspan="3"><?php echo help_tag("Link this one should follow"); ?></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="followid" class="cell">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_links " .
       "WHERE l_categoryid='$categoryid' " .
       "ORDER BY l_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->l_id == $id) {
    continue;
  }
  $selected = $row->l_seq == $follow ? "selected" : "";
  $label = $row->l_title;
  echo "<option $selected value='$row->l_id'>$label</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3"><?php echo help_tag("Description"); ?></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="3"><textarea name="description" rows="4" cols="100" wrap="physical"><?php echo $description; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align=center colspan="3">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateLink()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateLink()">
      <input type=button value="Delete" onclick="deleteLink()">
<?php } ?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3" class="fg5">
      <p>Return to <a href="links.php">Link Setup</a>.</p>
    </td>
  </tr>
</table>
</div>
</form>
<?php
include("includes/footer.inc");
?>
