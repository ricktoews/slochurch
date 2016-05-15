<?php
include("path.inc");
include("includes/header.inc");
?>
  <script language="JavaScript">
  function addLink() {
    var f = document.forms[0];
    location = "link_edit.php";
  }

  function editLink(id) {
    var f = document.forms[0];
    location = "link_edit.php?linkid=" + id;
  }

  function updateLinks() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.submit();
  }
  </script>
<form method=post action="save_links.php">
<input type=hidden name="arg" value="1">
<div align=center>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th colspan="5" class="fg1"><?php echo $PAGETITLE; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Flag</td>
    <td class="fg2">Title</td>
    <td class="fg2">Category</td>
    <td class="fg2">URL</td>
    <td class="fg2"></td>
  </tr>
<?php
$sql = "SELECT * FROM site_links, site_link_categories " .
       "WHERE l_categoryid=lc_id " .
       "ORDER BY lc_seq, l_seq";
$result = mysql_query($sql, $connect);
for ($r = 0; $r < mysql_num_rows($result); $r++) {
  if ($row = mysql_fetch_object($result)) {
    $checked = $row->l_flag ? "checked" : "";
    $linkid = $row->l_id;
    $url = $row->l_url;
    $title = $row->l_title;
    $description = $row->l_description;
    $categoryid = $row->l_categoryid;
  }
?>
  <tr valign=top bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=checkbox name="flag_<?php echo $r; ?>" <?php echo $checked; ?> value="1"><input type="hidden" name="linkid_<?php echo $r; ?>" value="<?php echo $linkid; ?>"></td>
    <td class="fg5"><?php echo preg_replace('/"/', "&quot;", $title); ?></td>
    <td class="fg5">
<?php
  $sql2 = "SELECT lc_category FROM site_link_categories " .
          "WHERE lc_id='$categoryid'";
  $result2 = mysql_query($sql2, $connect);
  if ($row2 = mysql_fetch_object($result2)) {
    echo "$row2->lc_category";
  }
?>
    </td>
    <td class="fg5"><?php echo $url; ?></td>
    <td class="fg5">
      <input type="button" value="Edit" onclick="editLink(<?php echo $linkid; ?>)" />
    </td>
  </tr>
  <tr valign=top bgcolor="<?php echo $bg5; ?>">
    <td></td>
    <td class="fg5" width="600" colspan="4">
      <i>
<?php 
  if (strlen($description) > 0) {
    echo preg_replace('/"/', "&quot;", $description); 
  }
  else {
    echo "No Description Provided.";
  }
?>
      </i>
    </td>
  </tr>
<?php
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align=center colspan="5">
      <input type=button value="New" onclick="addLink()">
      <input type=button value="Update" onclick="updateLinks()">
    </td>
  </tr>
</table>
</div>
<input type="hidden" name="max_rows" value="<?php echo $r; ?>">
</form>
<?php
include("includes/footer.inc");
?>
