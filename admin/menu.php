<?php
include("path.inc");
include("includes/lib_admin.inc");
include("includes/colors.inc");
?>
<html>
<head>
  <title>Administration Menu</title>
  <style type="text/css">
<?php include("$PATH/admin/style.css"); ?>
  </style>
</head>

<body bgcolor="#7F83A3" topmargin="0" leftmargin="0">
<table border=0 cellpadding=0 cellspacing=0>
<?php
$sql = "SELECT * FROM site_admin_funcs, site_admin_area_categories, site_admin_access " .
       "WHERE af_categoryid=ac_id " .
       "  AND aa_funcid=af_id " .
       "  AND aa_adminid='$LOGIN_ID' " .
       "ORDER BY ac_seq, af_sequence, af_label";
$result = mysql_query($sql, $connect);
$lastcategory = "";
while ($row = mysql_fetch_object($result)) {
  $command = $row->af_command;
  $label = $row->af_label;
  if ($row->ac_category != $lastcategory) {
    $lastcategory = $row->ac_category;
?>
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td class="menunav"><br><b><?php echo $row->ac_category; ?></b></td>
  </tr>
  <tr>
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"></td>
  </tr>
<?php
  }
?>
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td class="menunav" style="padding-left:10px;"><a href="<?php echo $command; ?>" target="content"><?php echo $label; ?></a></td>
  </tr>
  <tr>
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"></td>
  </tr>
<?php
}
?>
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td class="menunav"><a href="logoff.php" target="_parent">Sign Off</a></td>
  </tr>
  <tr>
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"></td>
  </tr>
</table>
</body>
</html>
