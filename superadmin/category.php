<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$inuse = $_REQUEST["inuse"];

// Retrieve a category.
if ($arg == "1") {
  $sql = "SELECT * FROM site_admin_area_categories " .
         "WHERE ac_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $category = $row->ac_category;
    $seq = $row->ac_seq;
  }
  $sql = "SELECT MAX(ac_seq) AS follow FROM site_admin_area_categories " .
         "WHERE ac_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getCategory() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "category.php";
    f.submit();
  }

  function newCategory() {
    location = "category.php";
  }

  function updateCategory() {
    var f = document.forms[0];
    if (f.category.value.length == 0) {
      alert("The category cannot be left blank.\nIf you want to delete a category, use the Delete button.");
      return;
    }
    f.arg.value = "2";
    f.submit();
  }

  function deleteCategory() {
    var f = document.forms[0];
    if (f.id.selectedIndex == 0) {
      alert("You must select a category to update.");
      return;
    }
    f.arg.value = "4";
    f.submit();
  }

  function initFocus() {
    var f = document.forms[0];
    f.category.focus();
  }

  onload=initFocus;
  </script>
<form method="post" action="save_category.php">
<input type="hidden" name="arg" value="">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Administration Area Categories</th>
  </tr>
<?php if ($inuse) { ?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      The category you selected is in use and so cannot be deleted.
    </td>
  </tr>
<?php } ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Administration Area Categories</td>
    <td class="fg2">Category</td>
    <td class="fg2">Category this should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="id" onchange="getCategory()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_admin_area_categories " .
       "ORDER BY ac_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->ac_id == $id ? "selected" : "";
  echo "<option $selected value='$row->ac_id'>$row->ac_category</option>\n";
}
?>
      </select>
      <input type=button value="New" onclick="newCategory()">
    </td>
    <td class="fg5">
      <input type="text" name="category" value="<?php echo $category; ?>" size=20>
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list
<?php
$sql = "SELECT * FROM site_admin_area_categories " .
       "ORDER BY ac_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->ac_id == $id) {
    continue;
  }
  $selected = $row->ac_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->ac_id'>$row->ac_category\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateCategory()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateCategory()">
      <input type=button value="Delete" onclick="deleteCategory()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>

