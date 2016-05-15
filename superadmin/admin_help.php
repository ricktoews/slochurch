<?php
include("path.inc");
include("includes/header.inc");

$ADMIN_DIR = "$PATH/admin/";

$arg = $_REQUEST["arg"];
$filename = $_REQUEST["filename"];

// Retrieve an admin area.
if ($arg == "1") {
  $tags = array();
  $lines = file("$ADMIN_DIR/$filename");
  foreach ($lines as $n => $l) {
    $l = trim($l);
    if (preg_match("/help_tag\(\"([^\"]+)\"\)/", $l, $match)) {
      $tags[] = $match[1];
    }
  }
}
?>
<script language="JavaScript">
function getFile() {
  var f = document.forms["data"];
  if (f.filename.options[f.filename.selectedIndex].value == "") return;
  f.action = "admin_help.php";
  f.arg.value = "1";
  f.submit();
}


function updateHelp() {
  var f = document.forms["data"];
  f.action = "save_admin_help.php";
  f.submit();
}
</script>
<form name="data" method="post" action="save_admin_help.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Administrative Help Setup</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Function</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="filename" onchange="getFile()">
      <option value="">Select</option>
<?php
$adminfiles = array();
$excludefiles = array('admin.php', 'blank.php', 'heading.php', 'index.php', 'login.php', 'logoff.php', 'menu.php');
if (is_dir($ADMIN_DIR)) {
  if ($dh = opendir($ADMIN_DIR)) {
    while (($file = readdir($dh)) !== false) {
      if (array_search($file, $excludefiles) !== false) {
        continue;
      }
      if (preg_match("/^save_/", $file)) {
        continue;
      }
      if (preg_match("/\.php$/", $file)) {
        $adminfiles[] = $file;
      }
    }
    sort($adminfiles);
  }
}
if (sizeof($adminfiles) > 0) {
  foreach ($adminfiles as $n => $af) {
    $selected = $filename == $af ? "selected" : "";
    echo "<option $selected value='$af'>$af</option>\n";
  }
}
?>
      </select>
    </td>
  </tr>
<?php
if (sizeof($tags) > 0) {
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Help Tag</td>
    <td class="fg2">Help Text</td>
  </tr>
<?php
  foreach ($tags as $n => $t) {
    $id = "";
    $helptext = "";
    $sql = "SELECT * FROM site_admin_help " .
           "WHERE ah_filename='$filename' " .
           "  AND ah_tag='$t'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $id = $row->ah_id;
      $helptext = $row->ah_helptext;
    }
?>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <?php echo "$t"; ?>
      <input type="hidden" name="id[]" value="<?php echo $id; ?>" />
      <input type="hidden" name="tag[]" value="<?php echo $t; ?>" />
    </td>
    <td class="fg5">
      <textarea name="helptext[]" rows="2" cols="50" wrap="physical"><?php echo $helptext; ?></textarea>
    </td>
  </tr>
<?php
  }
}
if (sizeof($tags) > 0) {
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2">
      <input type=button value="Update" onclick="updateHelp()" />
    </td>
  </tr>
<?php 
} 
elseif ($filename) {
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      No help tags were found in this function.
    </td>
  </tr>
<?php 
} 
?>
</table>
</form>
<?php
include("includes/footer.inc");
?>
