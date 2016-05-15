<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$tag = $_REQUEST["tag"];
$title = $_REQUEST["title"];

// Retrieve a navigation item.
if ($arg == "1") {
  $sql = "SELECT * FROM site_content " .
         "WHERE cont_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $tag = $row->cont_tag;
    $title = $row->cont_metatitle;
  }
}
?>
  <script language="JavaScript">
  function getPage() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "site_content.php";
    f.submit();
  }

  function newPage() {
    location = "site_content.php";
  }

  function updatePage() {
    var f = document.forms[0];
    f.arg.value = "2";
    if (f.tag.value.length == 0) {
      alert("Please assign a tag (e.g., 'about' or 'contact') to the Web page.");
      return;
    }
    f.submit();
  }

  function deletePage() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      if (f.id.selectedIndex == 0) {
        alert("You must select a page to delete from customization.");
        return;
      }
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms[0];
    f.tag.focus();
  }

  onload=initFocus;
  </script>
<form method="post" action="save_site_content.php">
<input type="hidden" name="arg" value="">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Site Pages</th>
  </tr>

  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Site Page</td>
    <td class="fg2">Page Tag (used in code)</td>
    <td class="fg2">Page Label</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="id" onchange="getPage()">
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
      <input type="button" value="New" onclick="newPage()">
    </td>
    <td class="fg5"><input type="text" name="tag" value="<?php echo $tag; ?>" size="40"></td>
    <td class="fg5"><input type="text" name="title" value="<?php echo $title; ?>" size="40"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updatePage()">
<?php } else { ?>
      <input type=button value="Update" onclick="updatePage()">
      <input type=button value="Delete" onclick="deletePage()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
