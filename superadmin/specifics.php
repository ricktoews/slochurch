<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$name = $_REQUEST["name"];
$email = $_REQUEST["email"];
$address = $_REQUEST["address"];
$base = $_REQUEST["base"];
$adminbase = $_REQUEST["adminbase"];
$signin = $_REQUEST["signin"];
$sadminid = $_REQUEST["sadminid"];
$sadminpw = $_REQUEST["sadminpw"];

if ($arg == "1") {
  $sql = "UPDATE site_admin_misc " .
         "SET am_name='$name', " .
         "    am_email='$email', " .
         "    am_address='$address', " .
         "    am_base='$base', " .
         "    am_adminbase='$adminbase', " .
         "    am_signin='$signin', " .
         "    am_sadminid='$sadminid', " .
         "    am_sadminpw='$sadminpw' ";
  mysql_query($sql, $connect);
  $refresh = 1;
}

$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $am_name = $row->am_name;
  $am_email = $row->am_email;
  $am_address = $row->am_address;
  $am_base = $row->am_base;
  $am_adminbase = $row->am_adminbase;
  $am_signin = $row->am_signin;
  $am_sadminid = $row->am_sadminid;
  $am_sadminpw = $row->am_sadminpw;
  if (!$am_signin) {
    $am_signin = "1";
  }
}
?>
<script language="JavaScript">
function checkPW() {
  var f = document.forms["data"];
  if (f.sadminpw2.value != f.sadminpw.value) {
    alert("Password entries do not match.");
    f.sadminpw2.value = "";
    f.sadminpw2.focus();
  } 
}
</script>
<form name="data" method="post" action="specifics.php">
<input type="hidden" name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Site Specifications</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Name</td>
    <td class="fg2">Email Address</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="name" value="<?php echo $am_name; ?>" size="40" /></td>
    <td class="fg5"><input type=text name="email" value="<?php echo $am_email; ?>" size="40" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Address, Phone#</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><textarea name="address" rows=5 cols=70 wrap=physical><?php echo $am_address; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Superadmin ID</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><input type=text name="sadminid" value="<?php echo $am_sadminid; ?>" size=40></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Superadmin Password</td>
    <td class="fg2">Retype</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="password" name="sadminpw" value="<?php echo $am_sadminpw; ?>" size="40" /></td>
    <td class="fg5"><input type="password" name="sadminpw2" value="<?php echo $am_sadminpw2; ?>" size="40" onblur="checkPW()" /></td>
  </tr>
<!--
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Member-level Administration Base URL</td>
    <td class="fg2">Standard Administration Base URL</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="base" value="<?php echo $am_base; ?>" size=40></td>
    <td class="fg5"><input type=text name="adminbase" value="<?php echo $am_adminbase; ?>" size=40></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Member-level Administration Sign-in</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="cell" class="fg5" colspan="2">
<?php $checked = $am_signin == "1" ? "checked" : ""; ?>
      <input type=radio name="signin" <?php echo $checked; ?> value="1"> by Email
<?php $checked = $am_signin == "2" ? "checked" : ""; ?>
      <input type=radio name="signin" <?php echo $checked; ?> value="2"> by User ID / Password
    </td>
  </tr>
-->
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align=center class="fg5" colspan="2">
      <input type="submit" value="Update">
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
