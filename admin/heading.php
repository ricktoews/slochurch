<?php 
include("path.inc");
include("includes/lib_admin.inc");
include("includes/colors.inc");

$sql = "SELECT * FROM site_admin " .
       "WHERE a_id='$LOGIN_ID' ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $firstname = $row->a_first;
  $lastname = $row->a_last;
}
$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $name = $row->am_name;
}
?>
<html>
<head>
  <title>Administration Platform</title>
  <style type="text/css">
<?php include("$PATH/admin/style.css"); ?>
  }
  </style>
</head>

<body bgcolor="<?php echo $sidebg; ?>" topmargin="0" leftmargin="0">
<img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="10"><br>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="10" height="1"></td>
    <td class="side">
      <b>Welcome to the <?php echo $name; ?> Administrative area.<br>
      <img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"><br>
      You are logged in as <?php echo $firstname; ?> <?php echo $lastname; ?></b>.
    </td>
  </tr>
</table>
</body>
</html>

