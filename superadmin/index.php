<?php
include("path.inc");
include("$PATH/includes/lib.inc");
include("includes/colors.inc");

$arg = $_REQUEST["arg"];
$adminid = $_REQUEST["adminid"];
$adminpw = $_REQUEST["adminpw"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_admin_misc " .
         "WHERE am_sadminid='$adminid' " .
         "  AND am_sadminpw='$adminpw' ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    setcookie("cookie_superadmin", "1");
    header("Location: admin.php");
  }
  else {
    $msg = "Sorry, but we don't recognize you.  Want to try again?";
  }
}
?>
<html>
<head>
  <title>Super-Administration Area Signin</title>
  <style type="text/css">
<?php include("style.css"); ?>
  </style>
  <script language="JavaScript">
  function signin() {
    var f = document.forms[0];
    if (f.adminid.value == "" || f.adminpw.value == "") {
      alert("Please supply a user name and password.");
      return false;
    }
    f.arg.value = "1";
    f.submit();
  }

  function initFocus() {
    var f = document.forms[0];
    f.adminid.focus();
  }

  onload=initFocus;
  </script>
</head>
<body bgcolor="<?php echo $bgcolor; ?>" link="<?php echo $link; ?>" alink="<?php echo $alink; ?>" vlink="<?php echo $vlink; ?>">
<div align="center">
<?php
if ($msg > "") {
  echo "<b class='cell'>$msg</b>";
  echo "<p>";
}
?>
<form name="data" method="post" action="index.php">
<input type="hidden" name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Super-Administration Login</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">ID</td>
    <td class="fg2">Password</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="adminid" size="15"></td>
    <td class="fg5"><input type="password" name="adminpw" size="15"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2"><input type="submit" value="Login"></td>
  </tr>
</table>
<br />
</form>
</div>
</body>
</html>

