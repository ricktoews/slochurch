<?php
include("path.inc");
include("includes/header.inc");

$id = $_REQUEST["id"];
$arg = $_REQUEST["arg"];

$inuse = 0;
// Retrieve administrator information.
if ($arg == "1") {
  $sql = "SELECT * FROM site_admin " .
         "WHERE a_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $a_first = $row->a_first;
    $a_last = $row->a_last;
    $a_adminid = $row->a_adminid;
    $a_adminpw = $row->a_adminpw;
    $a_email = $row->a_email;
  }
}
?>
<script language="JavaScript">
function getAdmin() {
  var f = document.forms[0];
  f.action = "admin_setup.php";
  f.arg.value = "1";
  f.submit();
}

function newAdmin() {
  location = "admin_setup.php";
}

function getAccess() {
  var f = document.forms[0];
  var access = "|";
  for (var i = 0; i < f.elements.length ; i++) {
    if (f[i].type == "checkbox") {
      if (f[i].name.substr(0, 6) == "access" && f[i].checked) {
        access += f[i].value + "|";
      } 
    }
  }
  f.newaccess.value = access;
}

function updateAdmin() {
  var f = document.forms[0];
  getAccess();
  f.arg.value = "2";
  f.submit();
}

function deleteAdmin() {
  var f = document.forms[0];
  f.arg.value = "4";
  f.submit();
}
</script>
<form name="data" method="post" action="save_admin_setup.php" onsubmit="return validate()">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Administrator Setup</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Administrator</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="id" onchange="getAdmin()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_admin " .
       "ORDER BY a_first";
$result = check_mysql($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->a_id == $id ? "selected" : "";
  echo "<option $selected value='$row->a_id'>$row->a_adminid: $row->a_first $row->a_last</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newAdmin()" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">First Name</td>
    <td class="fg2">Last Name</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="first" value="<?php echo $a_first; ?>" size="20" /></td>
    <td class="fg5"><input type="text" name="last" value="<?php echo $a_last; ?>" size="20" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Admin ID</td>
    <td class="fg2">Password</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>"> 
    <td class="fg5"><input type="text" name="adminid" value="<?php echo $a_adminid; ?>" size="20" /></td>
    <td class="fg5"><input type="password" name="adminpw" value="<?php echo $a_adminpw; ?>" size="20" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Email</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>"> 
    <td class="fg5" colspan="2"><input type=text name="email" value="<?php echo $a_email; ?>" size="40" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Administrative Functions</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <script language="JavaScript">
      function toggleCheck(ndx) {
        var f = document.forms["data"];
        if (f.elements["access[]"][ndx].checked) {
          f.elements["access[]"][ndx].checked = false;
        }
        else {
          f.elements["access[]"][ndx].checked = true;
        }
      }
      </script>
      <table width="100%">
<?php
function complete_row($cols) {
  global $perrow;
  global $rowbegin, $delim, $rowend;

  if (sizeof($cols) > 0) {
    while (sizeof($cols) < $perrow) {
      $cols[] = "&nbsp;";
    }
    $r = $rowbegin . implode($delim, $cols) . $rowend;
    echo $r;
  }
}


$perrow = 3;
$categoryBegin = "<tr bgcolor='$bg3'><td colspan='$perrow' class='fg3'><b>";
$categoryEnd = "</b></td></tr>";
/*
$header = "<tr>";
for ($i = 0; $i < $perrow; $i++) {
  $header .= "<td class='fg5'>Restricted</td>";
}
$header .= "</tr>";
*/
$itemBegin = "<div style='background-color:#ffffff' onmouseover='this.style.backgroundColor=\"#dddddd\"' onmouseout='this.style.backgroundColor=\"#ffffff\"' onclick='toggleCheck(__ID__)'>";
$itemEnd = "</div>";
$rowbegin = "<tr bgcolor='#ffffff'><td class='fg5'>";
$rowend = "</td></tr>";
$delim = "</td><td class='fg5'>";
$cols = array();
$sql = "SELECT * FROM site_admin_funcs, site_admin_area_categories " .
       "LEFT JOIN site_admin_access ON aa_funcid=af_id AND aa_adminid='$id' " .
       "WHERE af_categoryid=ac_id " .
       "ORDER BY ac_seq, af_sequence";
$result = mysql_query($sql, $connect);
$accesslist = array();
$lastcatid = -1;
$ndx = 0;
while ($row = mysql_fetch_object($result)) {
  if ($row->ac_id != $lastcatid) {
    complete_row($cols);
    $cols = array();
    $lastcatid = $row->ac_id;
    $r = $categoryBegin . $row->ac_category . $categoryEnd;
    echo $r; 
  }
  $checked = $row->aa_funcid ? "checked" : "";
  $restricted = $row->aa_restricted ? "checked" : "";
  $af_id = $row->af_id;
  $af_label = $row->af_label;
  $string = "<input type='checkbox' $checked value='$af_id' name='access[]' /> $af_label";
  if ($row->af_restrict) {
    $string .= " &nbsp; (<input type='checkbox' $restricted value='1' name='restricted_$af_id'> Restrict)";
  }
  $cols[] = $string;
  $ndx++;
  if (sizeof($cols) == $perrow) {
    $r = $rowbegin . implode($delim, $cols) . $rowend;
    echo $r;
    $cols = array();
  }
  if ($checked == "checked") {
    $accesslist[] = $af_id;
  }
}
complete_row($cols);
?>
      </table>
      <input type="hidden" name="oldaccess" value="<?php echo $access; ?>" />
      <input type="hidden" name="newaccess" value="" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateAdmin()" />
<?php } else { ?>
      <input type=button value="Update" onclick="updateAdmin()" />
      <input type=button value="Delete" onclick="deleteAdmin()" />
<?php } ?>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>

