<?php
include("path.inc");
$ABSPATH = "http://www.slochurch.com";
$PAGETITLE = "Mailing";
$LEFT_MARGIN = '30';
$RIGHT_MARGIN = '30';
include($PATH . "/includes/lib.inc");
include($PATH . "/includes/colors.inc");
if (!$PIC) {
  $ndx = rand(1,6);
  $PIC = "pic" . $ndx;
}

$HORIZ_LINE = "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td bgcolor='#666666' background='$ABSPATH/images/dots_horiz.gif'><img src='$ABSPATH/images/spacer.gif' width='1' height='1'></td></tr></table>";
$SQ1 = "<table border='0' cellpadding='0' cellspacing='0'><tr><td bgcolor='#ff9900'><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td></tr></table>";
$DIVIDER = "<table border='0' cellpadding='0' cellspacing='0'><tr>" .
           "  <td bgcolor='#ff9900'><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td>" .
           "  <td><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td>" .
           "  <td bgcolor='#ff9900'><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td>" .
           "  <td><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td>" .
           "  <td bgcolor='#ff9900'><img src='$ABSPATH/images/spacer.gif' width='8' height='8'></td>" .
           "</tr></table>";

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
  $id = $row->mail_id;
  $subject = $row->mail_subject;
  $contact = $row->mail_contact;
  $mail_date = date("F j, Y", mktime($row->mail_date));
  $sql2 = "SELECT * FROM site_mailing_sections " .
          "WHERE ms_mailid='$id' " .
          "ORDER BY ms_seq";
  $result2 = mysql_query($sql2, $connect);
  while ($row2 = mysql_fetch_object($result2)) {
    $name = "<b>$row2->ms_name</b>";
    $ms_image = $row2->ms_image;
    $ms_image2 = $row2->ms_image2;
    $ms_image3 = $row2->ms_image3;
    $images = '';
    if ($ms_image && file_exists("$MAILING_PATH/$ms_image")) {
      $images .=
        "  <tr>" .
        "    <td><table bgcolor='#000000' border='0' cellpadding='0' cellspacing='1'><tr><td><img src='$ABSPATH/$ABSMAILING_PATH/$ms_image' /></td></tr></table></td>" .
        "  </tr>";
    }
    if ($ms_image2 && file_exists("$MAILING_PATH/$ms_image2")) {
      $images .=
        "  <tr>" .
        "    <td><img src='$ABSPATH/images/spacer.gif' width='1' height='10' /></td>" .
        "  </tr>" .
        "  <tr>" .
        "    <td><table bgcolor='#000000' border='0' cellpadding='0' cellspacing='1'><tr><td><img src='$ABSPATH/$ABSMAILING_PATH/$ms_image2' /></td></tr></table></td>" .
        "  </tr>";
    }
    if ($ms_image3 && file_exists("$MAILING_PATH/$ms_image3")) {
      $images .=
        "  <tr>" .
        "    <td><img src='$ABSPATH/images/spacer.gif' width='1' height='10' /></td>" .
        "  </tr>" .
        "  <tr>" .
        "    <td><table bgcolor='#000000' border='0' cellpadding='0' cellspacing='1'><tr><td><img src='$ABSPATH/$ABSMAILING_PATH/$ms_image3' /></td></tr></table></td>" .
        "  </tr>";
    }
    $imagetag = "";
    if (strlen($images) > 0) {
      $images = "<table border='0' cellpadding='0' cellspacing='0'>$images</table>";

      if ($row2->ms_align == 0) {
        $imagetag = "<table border='0' cellpadding='0' cellspacing='0' align='right'>" .
                    "  <tr>" .
                    "    <td><img src='$ABSPATH/images/spacer.gif' width='10' height='1' /></td>" .
                    "    <td>$images</td>" .
                    "  </tr>" .
                    "</table>";
      }
      else {
        $imagetag = "<table border='0' cellpadding='0' cellspacing='0' align='left'>" .
                    "  <tr>" .
                    "    <td>$images</td>" .
                    "    <td><img src='$ABSPATH/images/spacer.gif' width='10' height='1' /></td>" .
                    "  </tr>" .
                    "</table>";
      }
    }
    if (sizeof($CONTENT_TEXT) == 0) {
      $content = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>" .
                 "  <tr valign='top'>" .
                 "    <td class='cell'>" . date("F j, Y") . "</td>" .
                 "    <td align='right'><img src='$ABSPATH/mailing/images/mailing_heading.gif' /></td>" .
                 "  </tr>" .
                 "</table>" .
                 "<img src='$ABSPATH/images/spacer.gif' width='1' height='20' /><br />" .
                 $imagetag . 
                 "$name\r\n\r\n" .
                 $row2->ms_content;
    }
    else {
      $content = $imagetag . $name . "\r\n\r\n" . $row2->ms_content;
    }
    $paragraphs = explode("\r\n\r\n", $content);
    $cont_text = "";
    while (list($n, $p) = each($paragraphs)) {
      $p = "<p>$p</p>\n";
      $cont_text .= $p;
    }
    $CONTENT_TEXT[] = preg_replace("/\r\n/", "<br />", $cont_text);
  }
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
<?php include($ABSPATH . "/mailing/style.css"); ?>
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
<table border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td bgcolor="#ffffff" colspan="3"><img src="<?php echo $ABSPATH; ?>/mailing/images/mailing_1.gif" /></td>
  </tr>
  <tr valign="top">
    <td bgcolor="#ff772d" colspan="3"><img src="<?php echo $ABSPATH; ?>/mailing/images/mailing_2.gif" /></td>
  </tr>
  <tr valign="top">
    <td bgcolor="#b0b2c2">
      <img src="<?php echo $ABSPATH; ?>/mailing/images/mailing_3.jpg" /><br />
      <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#2e3b45"><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="1" height="2" /></td></tr></table>
    </td>
    <td bgcolor="#2e3b45"><img src="<?php echo $ABSPATH; ?>/mailing/images/mailing_4.gif" /></td>
    <td background="<?php echo $ABSPATH; ?>/mailing/images/mailing_bg.jpg">
      <img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="1" height="10" /><br />
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="<?php echo $LEFT_MARGIN; ?>" height="1" /></td>
          <td width="99%">
            <br />
            <div class="general">
<?php
foreach ($CONTENT_TEXT as $c) {
  echo $c;
  echo "<br clear='all' /><br />";
}
?>
            </div>
          </td>
          <td><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="<?php echo $RIGHT_MARGIN; ?>" height="1" /></td>
        </tr>
      </table>
      <br /><img src="<?php echo $ABSPATH; ?>/images/spacer.gif" width="1" height="10" /><br />
    </td>
  </tr>
</table>
<br />
<!--
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="668">
  <tr>
    <td class="footer">
      <?php echo $mailingfooter; ?>
    </td>
  </tr>
</table>
<br>
-->
</div>
</body>
</html>
