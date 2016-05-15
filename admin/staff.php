<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
if ($arg == "1") {
  $Office = new Offices($id);
}
?>
<script language="JavaScript">
function getOffice() {
  var f = document.forms[0];
  f.action = "staff.php";
  f.arg.value = "1";
  f.submit();
}

function newOffice() {
  location = "staff.php";
}

function updateOffice() {
  var f = document.forms[0];
  f.arg.value = "2";
<?php if ($arg) { ?>
  var stafflist = "";
  for (var i = 0; i < f.staff.options.length; ++i) {
    stafflist += f.staff.options[i].value + "|";
  }
  stafflist = stafflist.substr(0, stafflist.length - 1);
  f.staffids.value = stafflist;
<?php } ?>
  f.submit();
}

function deleteOffice() {
  var f = document.forms[0];
  if (confirm("Are you sure?")) {
    f.arg.value = "4";
    f.submit();
  }
}

function upload() {
  var f = document.forms[0];
  f.submit();
}


function insertOption(fld, text, value) {
  var l = fld.options.length;
  var done = false;
  fld.options[l] = new Option();
  for (var i = l; !done && i > 0; --i) {
    if (fld.options[i-1].text < text) {
      fld.options[i].text = text;
      fld.options[i].value = value;
      done = true;
    }
    else {
      fld.options[i].text = fld.options[i-1].text;
      fld.options[i].value = fld.options[i-1].value;
    }
  }
  if (!done) {
    fld.options[0].text = text;
    fld.options[0].value = value;
    done = true;
  }
}

function addStaff() {
  var f = document.forms[0];
  var i = 0;
  while (i < f.members.options.length) {
    if (f.members.options[i].selected) {
      insertOption(f.staff, f.members.options[i].text, f.members.options[i].value);
      f.members.options[i] = null;
    }
    else {
      i++;
    }
  }
}

function delStaff() {
  var f = document.forms[0];
  var i = 0;
  while (i < f.staff.options.length) {
    if (f.staff.options[i].selected) {
      insertOption(f.members, f.staff.options[i].text, f.staff.options[i].value);
      f.staff.options[i] = null;
    }
    else {
      i++;
    }
  }
}

function initFocus() {
  document.forms["data"].office.focus();
}

onload=initFocus;
</script>
<div align="center">
<form name="data" method="post" action="save_staff.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Staff</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Office</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2">
      <select name="id" onchange="getOffice()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_offices " .
       "ORDER BY o_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->o_id == $id ? "selected" : "";
  echo "<option $selected value='$row->o_id'>$row->o_office</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newOffice()" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Office</td>
    <td class="fg2">Display</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="office" value="<?php echo $Office->office; ?>" size="20" /></td>
    <td class="fg5">
<?php $checked = $Office->display ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="display" value="1" /> Yes
<?php $checked = !$Office->display ? "checked" : ""; ?>
      <input type="radio" <?php echo $checked; ?> name="display" value="0" /> No
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Office this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2">
      <select name="followid">
      <option value="0">Top</option>
<?php
$sql = "SELECT * FROM site_offices " .
       "ORDER BY o_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->o_id == $Office->id) {
    continue;
  }
  $selected = $row->o_seq == $Office->follow ? "selected" : "";
  echo "<option $selected value='$row->o_id'>$row->o_office</option>\n";
}
?>
      </select>
    </td>
  </tr>
</table>
<?php
if ($arg) {
// Do this part if a church office has been selected, so members can be added to the staff.
?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Member List</td>
    <td class="fg2"></td>
    <td class="fg2">Serving in this office</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="members" size="5" multiple>
<?php
  $sql = "SELECT * FROM site_members " .
         "ORDER BY m_last, m_first";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $sql2 = "SELECT * FROM site_staff " .
            "WHERE s_officeid='$Office->id' " .
            "  AND s_memberid='$row->m_id'";
    $result2 = mysql_query($sql2, $connect);
    if (mysql_num_rows($result2) == 0) {
      $listitem = format_name($row->m_first, $row->m_last);
      echo "<option value='$row->m_id'>$listitem</option>\n";
    }
  }
?>
      </select> 
    </td>
    <td align=center>
      <p><input type="button" value="-&gt;" onclick="addStaff()" /></p>
      <p><input type="button" value="&lt;-" onclick="delStaff()" /></p>
    </td>
    <td>
      <select name="staff" size="5" multiple>
<?php
  $sql = "SELECT * FROM site_staff, site_members, site_offices " .
         "WHERE s_officeid=o_id " .
         "  AND s_memberid=m_id " .
         "  AND s_officeid='$Office->id' " .
         "ORDER BY m_last, m_first ";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $m_id = $row->m_id;
    $listitem = format_name($row->m_first, $row->m_last);
    echo "<option value='$m_id'>$listitem</option>\n";
  }
?>
      </select> 
      <input type="hidden" name="staffids" value="" />
    </td>
  </tr>
</table>
<?php
// This concludes the section for adding staff to a church office.
}
?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center">
<?php
if (!$arg) {
?>
      <input type="button" value="Add" onclick="updateOffice()" />
<?php
}
else {
?>
      <input type="button" value="Update" onclick="updateOffice()" />
      <input type="button" value="Delete" onclick="deleteOffice()" />
<?php
}
?>
    </td>
  </tr>
</table>
<!--
<p>&nbsp;</p>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Alternatively, you may upload a tab-delimited file of the office information</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <input type="file" name="upload_officelist" />
      <input type="checkbox" name="del_officelist" value="1" /> Delete existing
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5">
      <input type="button" value="Upload" onclick="upload()" />
    </td>
  </tr>
</table>
-->
</form>
</div>
<?php
include("includes/footer.inc");
?>
