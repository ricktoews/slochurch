<?php
include("path.inc");
include("includes/header.inc");

$inuse = 0;
$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

// Retrieve an admin area.
if ($arg == "1") {
  $sql = "SELECT * FROM site_admin_funcs " .
         "WHERE af_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $af_id = $row->af_id;
    $af_label = $row->af_label;
    $af_categoryid = $row->af_categoryid;
    $af_command = $row->af_command;
    $af_querystring = $row->af_querystring;
    $af_restrict = $row->af_restrict;
    $af_admincontact = $row->af_admincontact;
    $af_sequence = $row->af_sequence;
  }
  $sql = "SELECT MAX(af_sequence) AS follow FROM site_admin_funcs " .
         "WHERE af_categoryid='$af_categoryid' " .
         "  AND af_sequence < '$af_sequence'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  funcs = new Array();
<?php
$sql = "SELECT * FROM site_admin_funcs " .
       "ORDER BY af_categoryid, af_sequence";
$result = mysql_query($sql, $connect);
$lastcat = -1;
while ($row = mysql_fetch_object($result)) {
  if ($row->af_categoryid != $lastcat) {
    $lastcat = $row->af_categoryid;
    $i = 0;
    echo "funcs[$row->af_categoryid] = new Array();\n";
  }
  $label = preg_replace("/'/", "\'", $row->af_label);
  echo "funcs[$row->af_categoryid][$i] = '$row->af_id|$label|$row->af_command';\n"; 
  $i++;
}
?>

  function getArea() {
    var f = document.forms["data"];
    if (f.id.options[f.id.selectedIndex].value == "") return;
    f.action = "admin_funcs.php";
    f.arg.value = "1";
    f.submit();
  }

  function newArea() {
    location = "admin_funcs.php";
  }

  function updateArea() {
    var f = document.forms["data"];
    f.arg.value = "2";
    f.submit();
  }

  function deleteArea() {
    var f = document.forms["data"];
    f.arg.value = "4";
    f.submit();
  }

  function followList() {
    var f = document.forms["data"];
    var cat_id = f.categoryid.options[f.categoryid.selectedIndex].value;
    f.followid.options.length = 1;
    for (i = 0; i < funcs[cat_id].length; i++) {
      parts = funcs[cat_id][i].split("|");
      value = parts[0];
      text = parts[1];
      f.followid.options[i+1] = new Option(text, value);
    } 
  }
  </script>
<form name="data" method="post" action="save_admin_funcs.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Administrative Functions</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Function</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="id" onchange="getArea()">
      <option value="">Select</option>
<?php
$lastcat = -1;
$sql = "SELECT * FROM site_admin_funcs, site_admin_area_categories " .
       "WHERE af_categoryid=ac_id " .
       "ORDER BY ac_seq, af_sequence";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($lastcat != $row->ac_id) {
    $lastcat = $row->ac_id;
    echo "<option value=''>--- $row->ac_category</option>\n";
  }
  $selected = $row->af_id == $af_id ? "selected" : "";
  echo "<option $selected value='$row->af_id'>$row->af_label</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newArea()" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Menu Label</td>
    <td class="fg2">Command</td>
    <td class="fg2">Parameters</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="label" value="<?php echo $af_label; ?>" size="20" /></td>
    <td class="fg5">
      <select name="command">
      <option value="">Select</option>
<?php
$adminfiles = array();
$excludefiles = array('admin.php', 'blank.php', 'heading.php', 'index.php', 'login.php', 'logoff.php', 'menu.php');
$ADMIN_DIR = "$PATH/admin/";
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
    $selected = $af_command == $af ? "selected" : "";
    echo "<option $selected value='$af'>$af</option>\n";
  }
}
?>
      </select>
    </td>
    <td class="fg5"><input type="text" name="querystring" value="<?php echo $af_querystring; ?>" size="20" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Area Category</td>
    <td class="fg2">Label this should follow</td>
    <td class="fg2">Restrict Access Capability?</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="categoryid" onchange="followList()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_admin_area_categories " .
       "ORDER BY ac_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->ac_id == $af_categoryid ? "selected" : "";
  echo "<option $selected value='$row->ac_id'>$row->ac_category</option>\n";
}
?>
      </select>
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_admin_funcs " .
       "WHERE af_categoryid='$af_categoryid' " .
       "ORDER BY af_sequence";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->af_id == $af_id) {
    continue;
  }
  $selected = $row->af_sequence == $follow ? "selected" : "";
  echo "<option $selected value='$row->af_id'>$row->af_label</option>\n";
}
?>
      </select>
    </td>
    <td class="fg5">
<?php $checked = $af_restrict ? "checked" : ""; ?>
      <input <?php echo $checked; ?> type="radio" name="restrict" value="1" /> Yes
<?php $checked = !$af_restrict ? "checked" : ""; ?>
      <input <?php echo $checked; ?> type="radio" name="restrict" value="0" /> No
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateArea()" />
<?php } else { ?>
      <input type=button value="Update" onclick="updateArea()" />
      <input type=button value="Delete" onclick="deleteArea()" />
<?php } ?>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
