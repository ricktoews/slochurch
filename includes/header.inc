<?php 
include("lib.inc");
include("colors.inc");

$Page = new Page($CONT_TAG);
?>
<html>
<head>
<?php if ($Page->metatitle) { ?>
  <title><?php echo $Page->metatitle; ?></title>
<?php } else { ?>
  <title><?php echo $Site->name; ?></title>
<?php } ?>
  <meta name="description" content="<?php echo $Page->metadescription; ?>">
  <meta name="keywords" content="<?php echo $Page->metakeywords; ?>">
  <script language="JavaScript">
  function initSignup() {
    var f = document.forms["signup"];
    f.email.value = "";
  }

  navImgArray = new Array(<?php echo $navimglist; ?>);
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
  <link rel="stylesheet" href="<?php echo $PATH; ?>/style/style.css" />
</head>
<body background="images/pagebg.jpg" topmargin="0" leftmargin="0">
<form name="signup" method="post" action="signup.php">
<div align="center">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="images/homepic1a.jpg" /></td>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td><img src="images/thinbar_left.gif" width="236" height="3" /></td>
    <td bgcolor="#FF772D"><img src="images/spacer.gif" width="20" height="1" /></td>
    <td bgcolor="#FF772D"><img src="images/spacer.gif" width="266" height="1" /></td>
    <td><img src="images/thinbar_mid.gif" /></td>
    <td bgcolor="#818F9A"><img src="images/spacer.gif" width="178" height="1" /></td>
    <td><img src="images/thinbar_right.gif" width="42" height="3" /></td>
  </tr>
  <tr valign="top">
    <td><img src="images/homepic1.jpg" /></td>
    <td><img src="images/sda_banner.gif" /></td>
    <td><img src="images/homepic2a.jpg" /></td>
    <td>
      <img src="images/homepic2b.gif" /><br />
      <img src="images/homepic2c.jpg" /><br />
    </td>
    <td bgcolor="#818f9a">
      <img src="images/spacer.gif" width="1" height="9" /><br />
      <table border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td bgcolor="#818f9a">
            <img src="images/spacer.gif" width="12" height="56" /><br />
            <img src="images/nav_lefta.gif" /><br />
          </td>
          <td>
            <a href="<?php echo $PATH; ?>/schedule.php"><img src="images/nav_schedule.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
            <img src="images/spacer.gif" width="7" height="1" /><a href="<?php echo $PATH; ?>/staff.php"><img src="images/nav_staff.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
            <img src="images/spacer.gif" width="13" height="1" /><a href="<?php echo $PATH; ?>/gallery.php"><img src="images/nav_picture_gallery.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
          </td>
        </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td><img src="images/homepic2d.gif" /></td>
          <td>
            <a href="<?php echo $PATH; ?>/faq.php"><img src="images/nav_faq.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
            <img src="images/spacer.gif" width="7" height="1" /><a href="<?php echo $PATH; ?>/filler.php"><img src="images/nav_collegiate_site.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
            <img src="images/spacer.gif" width="17" height="1" /><a href="<?php echo $PATH; ?>/filler.php"><img src="images/nav_church_calendar.gif" border="0" /></a><br />
            <img src="images/spacer.gif" width="1" height="16" /><br />
            <img src="images/nav_leftb.gif" /><a href="<?php echo $PATH; ?>/links.php"><img src="images/nav_links.gif" border="0" /></a><br />
            <img src="images/nav_leftc.gif" /><br />
            <img src="images/nav_leftd.gif" /><a href="<?php echo $PATH; ?>/contact.php"><img src="images/nav_contact_us.gif" border="0" /></a><br />
          </td>
        </tr>
      </table>
    </td>
    <td>
<?php
if ($HOMEPG) {
?>
      <table border="0" cellpadding="0" cellspacing="0"><tr><td><img src="images/nav_righta.gif" /></td><td bgcolor="#262215"><img src="images/spacer.gif" width="31" height="29" /></td></tr></table>
<?php
}
else {
?>
      <img src="images/nav_righta.gif" /><a href="index.php"><img src="images/nav_home.gif" border="0" /></a><br />
<?php
}
?>
      <img src="images/nav_righta1.gif" /><br />
      <img src="images/nav_rightb.gif" /><br />
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#2E3B45">
      <img src="images/spacer.gif" width="575" height="1" /><br />
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="images/spacer.gif" width="10" height="1" /></td>
          <td>
            <input type="text" class="signup" name="email" size="20" value="Email Address" onfocus="initSignup()" onblur="valEmail(this)" />
          </td>
          <td><img src="images/spacer.gif" width="13" height="1" /></td>
          <td><img src="images/email_signup.gif" /></td>
        </tr>
      </table>
    </td>
    <td bgcolor="#818f9a" width="195">
      <img src="images/nav_lefte.gif" /><br />
      <img src="images/nav_leftf.gif" /><a href="<?php echo $PATH; ?>/map.php"><img src="images/nav_map.gif" border="0" /></a><br />
      <img src="images/nav_leftg.gif" /><br />
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="157" height="269" background="images/big_uparrow.jpg">
      <img src="images/spacer.gif" width="1" height="208" /><br />
      <img src="images/spacer.gif" width="10" height="1" /><a href="<?php echo $PATH; ?>/filler.php"><img src="images/btn_members_page.gif" border="0" /></a><br />
      <img src="images/spacer.gif" width="1" height="9" /><br />
      <img src="images/spacer.gif" width="10" height="1" /><a href="<?php echo $PATH; ?>/filler.php"><img src="images/btn_pastors_page.gif" border="0" /></a><br />
    </td>
    <td width="430">
<!-- Iframe will go here -->
      <table bgcolor="#a8cee2" border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td>
            <iframe frameborder="0" name="frm" src="<?php echo $IFRAME_SRC; ?>" id="frm" width="428" height="267"></iframe>
          </td>
        </tr>
      </table>

