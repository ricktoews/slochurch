<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$label = $_REQUEST["label"];
$tag = $_REQUEST["tag"];
$command = $_REQUEST["command"];
$popup = $_REQUEST["popup"];

// Retrieve a navigation item.
if ($arg == "1") {
  $sql = "SELECT * FROM site_nav " .
         "WHERE nav_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $tag = $row->nav_tag;
    $label = $row->nav_label;
    $command = $row->nav_command;
    $mouseover = $row->nav_mouseover;
    $mouseout = $row->nav_mouseout;
    $popup = $row->nav_popup;
    $seq = $row->nav_seq;
  }
  $sql = "SELECT MAX(nav_seq) AS follow FROM site_nav " .
         "WHERE nav_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getNav() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "nav.php";
    f.submit();
  }

  function newNav() {
    location = "nav.php";
  }

  function updateNav() {
    var f = document.forms[0];
    f.arg.value = "2";
    if (f.tag.value.length == 0) {
      alert("Please assign a tag (e.g., 'about' or 'contact') to the navigation item.");
      return;
    }
    f.submit();
  }

  function deleteNav() {
    var f = document.forms[0];
    if (f.id.selectedIndex == 0) {
      alert("You must select a navigation item to delete.");
      return;
    }
    f.arg.value = "4";
    f.submit();
  }

  function initFocus() {
    var f = document.forms[0];
    f.label.focus();
  }

  onload=initFocus;
  </script>
<form name="data" method="post" action="save_nav.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Navigation</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Navigation</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="id" onchange="getNav()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_nav " .
       "ORDER BY nav_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->nav_id == $id ? "selected" : "";
  echo "<option $selected value='$row->nav_id'>$row->nav_label</option>\n";
}
?>
      </select>
      <input type=button value="New" onclick="newNav()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Administrative Label</td>
    <td class="fg2">Navigation Tag (used as array index in code)</td>
    <td class="fg2">Navigation Command</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="label" value="<?php echo $label; ?>" size="20"></td>
    <td class="fg5"><input type="text" name="tag" value="<?php echo $tag; ?>" size="20"></td>
    <td class="fg5"><input type="text" name="command" value="<?php echo $command; ?>" size="20"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Mouseover</td>
    <td class="fg2">Mouseout</td>
    <td class="fg2">Navigation Item this should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="mouseover" value="<?php echo $mouseover; ?>" size="20"></td>
    <td class="fg5"><input type="text" name="mouseout" value="<?php echo $mouseout; ?>" size="20"></td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_nav " .
       "ORDER BY nav_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->nav_id == $id) {
    continue;
  }
  $selected = $row->nav_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->nav_id'>$row->nav_label</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateNav()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateNav()">
      <input type=button value="Delete" onclick="deleteNav()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
