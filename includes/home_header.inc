<?php 
include("lib.inc");
include("colors.inc");

get_pagedata($CONT_TAG);
?>
<html>
<head>
<?php if ($METATITLE) { ?>
  <title><?php echo $am_name; ?> - <?php echo $METATITLE; ?></title>
<?php } else { ?>
  <title><?php echo $am_name; ?></title>
<?php } ?>
  <meta name="description" content="<?php echo $METADESCRIPTION; ?>">
  <meta name="keywords" content="<?php echo $METAKEYWORDS; ?>">
  <script language="JavaScript">
  function login() {
    var f = document.forms["frmLogin"];
    f.submit();
  }

  navImgArray = new Array(<?php echo $NAVIMGLIST; ?>);
  function makeNavImg(n) {
    this.on = new Image();
    this.on.src = "<?php echo $PATH; ?>/images/nav_" + n + "_on.gif";
    this.off = new Image();
    this.off.src = "<?php echo $PATH; ?>/images/nav_" + n + "_off.gif";
  }

  navImg = new Array();
  for (i = 0; i < navImgArray.length; i++) {
    navImg[navImgArray[i]] = new makeNavImg(navImgArray[i]);
  }
  
  function toggle(n, on) {
    document.images["nav_"+n].src = on ? navImg[n].on.src : navImg[n].off.src;
  }

  path = "<?php echo $PATH; ?>/";
  loaded = false;

  function initFocus() {}
  </script>
  <script language="JavaScript" src="<?php echo $PATH; ?>/includes/jslib.js"></script>
<style type="text/css">
<?php include("$PATH/style.css"); ?>
</style>
</head>
<body bgcolor="<?php echo $bgcolor; ?>" topmargin="0" leftmargin="0">
<div align="center">
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="770">
  <tr>
    <td><img src="images/home1.jpg" /></td>
    <td><img src="images/home2.jpg" /><img src="images/home3.jpg" /></td>
    <td><img src="images/home4.jpg" /></td>
    <td bgcolor="#ff0000">
      <img src="images/spacer.gif" width="1" height="1" /><br />
      <table border="0" cellpadding="0" cellspacing="0" width="770">
        <tr>
          <td align="center">
            <a href="parks.php"><img src="images/nav_parks_off.gif" border="0" /></a>
            <img src="images/spacer.gif" width="20" height="1" />
            <a href="rides.php"><img src="images/nav_parks_off.gif" border="0" /></a>
          </td>
        </tr>
      </table>
      <img src="images/spacer.gif" width="1" height="1" /><br />
    </td>
    <td><img src="images/home5.jpg" /></td>
  </tr>
</table>
</div>
</body>
</html>  
<?php
if (!$LOGINID && !$SUPPRESSLOGIN) {
?>
  <tr>
    <td></td>
    <td align="right">
      <form name="frmLogin" method="post" action="login.php">
      <table>
        <tr>
          <td class="cell">Member Login</td>
          <td><input type="text" class="smallfield" name="username" size="10" maxlength="10"></td>
          <td><input type="password" class="smallfield" name="password" size="10" maxlength="10"></td>
          <td><input type="button" class="smallfield" value="Login" onclick="login()"></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="3" class="cell"><a href="signup.php">Not a member yet?</a></td>
        </tr>
      </table>
      </form>
    </td>
    <td></td>
  </tr>
<?php
} elseif ($LOGINID) {
?>
  <tr>
    <td></td>
    <td align="right" class="cell">
      <br />
      Welcome back to ThrillRideZone.com, <?php echo $FIRST; ?>.<br />
      <a href="logout.php">Log out</a>
    </td>
    <td></td>
  </tr>
<?php
}
?>
  <tr>
    <td width="50"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="50" height="1"></td>
    <td class="general">
      <br /><br />
<!-- Begin content -->
      <?php echo $PAGECONTENT; ?>
