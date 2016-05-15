<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_bibletrivia_categories " .
         "WHERE bc_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $category = $row->bc_category;
    $seq = $row->bc_seq;
  }
  $sql = "SELECT MAX(bc_seq) AS follow FROM site_bibletrivia_categories " .
         "WHERE bc_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getCategory() {
    var f = document.forms["data"];
    f.arg.value = "1";
    f.action = "trivia_categories.php";
    f.submit();
  }

  function newCategory() {
    location = "trivia_categories.php";
  }

  function updateCategory() {
    var f = document.forms["data"];
    f.arg.value = "2";
    f.submit();
  }

  function deleteCategory() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms["data"];
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms["data"];
    f.category.focus();
  }

  onload=initFocus;
  </script>
<div align="center">
<form name="data" method="post" action="save_trivia_categories.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">Bible Trivia Categories</th>
  </tr>
<?php if ($inuse) { ?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="4">
      The category you selected is in use and so cannot be deleted.
    </td>
  </tr>
<?php } ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Bible Trivia Categories</td>
    <td class="fg2">Category</td>
    <td class="fg2">Category this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <select name="id" onchange="getCategory()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_bibletrivia_categories " .
       "ORDER BY bc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->bc_id == $id ? "selected" : "";
  echo "<option $selected value='$row->bc_id'>$row->bc_category</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newCategory()">
    </td>
    <td class="fg5">
      <input type="text" name="category" value="<?php echo $category; ?>" size="20" />
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_bibletrivia_categories " .
       "ORDER BY bc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->bc_id == $id) {
    continue;
  }
  $selected = $row->bc_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->bc_id'>$row->bc_category</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="4">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateCategory()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateCategory()">
      <input type=button value="Delete" onclick="deleteCategory()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
