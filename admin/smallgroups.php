<?php
include("path.inc");
include("includes/header.inc");

class SmallGroup {
  function SmallGroup($id)
  {
    $connect = $_REQUEST["connect"];

    $sql = "SELECT * FROM site_smallgroups " .
           "WHERE sg_id='$id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->id = $row->sg_id;
      $this->name = $row->sg_name;
      $this->description = $row->sg_description;
      $this->contact = $row->sg_contact;
      $this->email = $row->sg_email;
      $this->phone = $row->sg_phone;
      $this->fmtphone = format_phone($row->sg_phone);
      $this->location = $row->sg_location;
      $this->time = $row->sg_time;
      $this->seq = $row->sg_seq;
    }
    $sql = "SELECT MAX(sg_seq) AS follow FROM site_smallgroups " .
           "WHERE sg_seq < '$row->sg_seq'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->follow = $row->follow;
    }
  }
}


$arg = $_REQUEST['arg'];
$id = $_REQUEST['id'];
if ($arg == "1") {
  $SmallGroup = new SmallGroup($id);
}

?>
<script language="JavaScript">
function getGroup() {
  var f = document.forms["data"];
  f.action = "smallgroups.php";
  f.arg.value = "1";
  f.submit();
}

function newGroup() {
  location = "smallgroups.php";
}

function updateGroup() {
  var f = document.forms["data"];
  f.arg.value = "2";
  f.submit();
}

function deleteGroup() {
  var f = document.forms["data"];
  if (confirm("Are you sure?")) {
    f.arg.value = "4";
    f.submit();
  }
}
function initFocus() {
  document.forms["data"].name.focus();
}

onload=initFocus;
</script>
<form name="data" method="post" action="save_smallgroups.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Small Groups</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Small Groups</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="id" onchange="getGroup()">
      <option value="">Select</option>
<?php
$sql = "SELECT sg_id, sg_name FROM site_smallgroups " .
       "ORDER BY sg_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->sg_id == $id ? "selected" : "";
  echo "<option $selected value='$row->sg_id'>$row->sg_name</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newGroup()" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Group Name</td>
    <td class="fg2">Location</td>
    <td class="fg2">Time</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type="text" name="name" value="<?php echo $SmallGroup->name; ?>" size="20" /></td>
    <td class="fg5"><input type="text" name="location" value="<?php echo $SmallGroup->location; ?>" size="20" /></td>
    <td class="fg5"><input type="text" name="time" value="<?php echo $SmallGroup->time; ?>" size="20" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Description</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5" colspan="3"><textarea name="description" rows="2" cols="80" wrap="physical"><?php echo $SmallGroup->description; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Contact Name</td>
    <td class="fg2">Phone</td>
    <td class="fg2">Email</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type="text" name="contact" value="<?php echo $SmallGroup->contact; ?>" size="20" /></td>
    <td class="fg5"><input type="text" name="phone" value="<?php echo $SmallGroup->fmtphone; ?>" size="20" onblur="formatPhone(this)" /></td>
    <td class="fg5"><input type="text" name="email" value="<?php echo $SmallGroup->email; ?>" size="20" onblur="valEmail(this)" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Small group this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="3">
      <select name="followid">
      <option value="0">Top</option>
<?php
$sql = "SELECT * FROM site_smallgroups " .
       "ORDER BY sg_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->sg_id == $SmallGroup->id) {
    continue;
  }
  $selected = $row->sg_seq == $SmallGroup->follow ? "selected" : "";
  echo "<option $selected value='$row->sg_id'>$row->sg_name</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php
if (!$arg) {
?>
      <input type="button" value="Add" onclick="updateGroup()" />
<?php
}
else {
?>
      <input type="button" value="Update" onclick="updateGroup()" />
      <input type="button" value="Delete" onclick="deleteGroup()" />
<?php
}
?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
