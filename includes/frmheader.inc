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
  <script language="JavaScript" src="<?php echo $PATH; ?>/includes/jslib.js"></script>
  <link rel="stylesheet" href="<?php echo $PATH; ?>/style/style.css" />
  <style type="text/css">
  body {
    scrollbar-base-color:#6a6e6b;
    scrollbar-3dlight-color:#818f9a;
    scrollbar-highlight-color:#6a6e6b;
    scrollbar-track-color:#818f9a;
    scrollbar-arrow-color:#ff772d;
    scrollbar-shadow-color:#6a6e6b;
  }
  </style>
</head>
<body background="<?php echo $PATH; ?>/images/iframebg.jpg" bgcolor="#B3D1E7" topmargin="0" leftmargin="0">
<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="20" height="1" /></td>
    <td width="99%">
      <div class="general">
        <img src="<?php echo $PATH; ?>/images/heading_<?php echo $HEADING; ?>.gif" /><br />
        <img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6" /><br />
        <?php echo $HORIZ_LINE; ?><br />
