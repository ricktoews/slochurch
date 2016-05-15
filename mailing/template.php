<?php
include("path.inc");
$ABSPATH = "http://www.slochurch.com";
$PAGETITLE = "Mailing";
$PIC = "pic6";
include($PATH . "/includes/lib.inc");
include($PATH . "/includes/colors.inc");
if (!$PIC) {
  $ndx = rand(1,6);
  $PIC = "pic" . $ndx;
}

$HORIZ_LINE = "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td bgcolor='#666666' background='$ABSPATH/images/dots_horiz.gif'><img src='$ABSPATH/images/spacer.gif' width='1' height='1'></td></tr></table>";

$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $mailingcontact = $row->am_mailingcontact;
  $paragraphs = explode("\r\n\r\n", $mailingcontact);
  $cont_text = "";
  while (list($n, $p) = each($paragraphs)) {
    $p = "<p>$p</p>\n";
    $cont_text .= $p;
  }
  $CONTACT_TEXT = preg_replace("/\r\n/", "<br>", $cont_text);
  $mailingfooter = $row->am_mailingfooter;
  $mailingfooter = preg_replace("/\r\n/", "<br>", $mailingfooter);
}

if ($id) {
  $sql = "SELECT * FROM site_mailings " .
         "WHERE mail_id='$id'";
}
else {
  $sql = "SELECT * FROM site_mailings " .
         "ORDER BY mail_date DESC";
}
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $mail_date = date("F j, Y", mktime($row->mail_date));
  $subject = $row->mail_subject;
  $content = $row->mail_content;
  $paragraphs = explode("\r\n\r\n", $content);
  $cont_text = "";
  while (list($n, $p) = each($paragraphs)) {
    $p = "<p>$p</p>\n";
    $cont_text .= $p;
  }
  $CONT_TEXT = preg_replace("/\r\n/", "<br>", $cont_text);
}
$HEADING = $subject;

?>
<html>
<head>
<?php if ($PAGETITLE) { ?>
  <title><?php echo $am_name; ?> - <?php echo $date; ?> - <?php echo $PAGETITLE; ?></title>
<?php } else { ?>
  <title><?php echo $am_name; ?></title>
<?php } ?>
  <style type="text/css">
<?php include($PATH . "/style.css"); ?>
  .contact {
    font-family:arial, helvetica;
    font-size:8pt;
    font-color:#000000;
  }
  .footer {
    font-family:arial, helvetica;
    font-size:8pt;
    color:#cccccc;
  }
  </style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br />
<div align="center">
<table bgcolor="#999999" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td>
      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td>
            <img src="<?php echo $ABSPATH; ?>/newsletter/logo.gif"><br />
            <img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="1" height="11" /><br />
            <img src="<?php echo $ABSPATH; ?>/newsletter/masthead.gif"><br />
            <img src="<?php echo $ABSPATH; ?>/newsletter/contact_heading.gif"><br />
            <table background="<?php echo $ABSPATH; ?>/newsletter/contact_bg.jpg" border="0" cellpadding="0" cellspacing="0" width="211">
              <tr valign="top">
                <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="28" height="407" /></td>
                <td class="contact" width="160">
                  <br />
                </td>
                <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="23" height="1" /></td>
              </tr>
            </table>
          </td>
          <td bgcolor="#000000"><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="4" height="1" /></td>
          <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="25" height="1" /></td>
          <td width="394" class="general">
            <img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="1" height="20" /><br />
            <img src="<?php echo $ABSPATH; ?>/newsletter/news_heading.gif" /><br />
            <?php echo $CONT_TEXT; ?>
            <br />
            <?php echo $HORIZ_LINE; ?>
            <br />
            <img src="<?php echo $ABSPATH; ?>/newsletter/relevant_heading.gif" /><br />
            <?php echo $RELEVANT_TEXT; ?>
            <br />
          </td>
          <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="25" height="1" /></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="668">
  <tr>
    <td class="footer">
      <?php echo $mailingfooter; ?>
    </td>
  </tr>
</table>
<br>
</div>
</body>
</html>
