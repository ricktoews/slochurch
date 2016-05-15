<?php
include("path.inc");
include("connect.inc");
include("includes/colors.inc");
?>
<html>
<head>
  <title>Administration Menu</title>
  <style type="text/css">
<?php include("$PATH/admin/style.css"); ?>
  </style>
</head>

<body bgcolor="<?php echo $sidebg; ?>" text="<?php echo $sidefg; ?>" link="<?php echo $sidelink; ?>" alink="<?php echo $sidealink; ?>" vlink="<?php echo $sidevlink; ?>">
<img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="60"><br>
<table border="0" cellpadding="0" cellspacing="0">
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="admin_setup.php" target="content">Admin Setup</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="category.php" target="content">Admin Area Categories</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="admin_funcs.php" target="content">Admin Functions Setup</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="admin_help.php" target="content">Admin Help Setup</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="colors.php" target="content">Colors</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="specifics.php" target="content">Site Specifics</a></td>
  </tr>
  <tr>
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="site_content.php" target="content">Site Pages</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
  <tr valign=top>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width=10 height=1></td>
    <td class="menunav"><a href="nav.php" target="content">Site Primary Nav</a></td>
  </tr>
  <tr>
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6"></td>
  </tr>
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1"></td>
    <td class="menunav"><a href="logoff.php" target="_parent">Sign Off</a></td>
  </tr>
  <tr>
    <td colspan=4><img src="<?php echo $PATH; ?>/images/spacer.gif" width=1 height=6></td>
  </tr>
</table>
</body>
</html>
