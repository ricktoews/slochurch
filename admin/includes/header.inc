<?php
include("path.inc");
include("includes/lib_admin.inc");
include("includes/colors.inc");

$filename = basename($_SERVER['PHP_SELF']);
$querystring = $_SERVER['QUERY_STRING'];

$sql = "SELECT * FROM site_admin_funcs " .
       "WHERE af_command='$filename' " .
       "  AND af_querystring='$querystring'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $PAGETITLE = $row->af_label;
}
else {
  $sql = "SELECT * FROM site_admin_funcs " .
         "WHERE af_command='$filename' ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $PAGETITLE = $row->af_label;
  }
}

$MARGIN = 20;
?>
<!-- <?php echo basename($SCRIPT_FILE); ?> -->
<html>
<head>
  <title><?php echo $am_name; ?> - <?php echo $TITLE; ?></title>
  <style type="text/css">
<?php include("$PATH/admin/style.css"); ?>
  </style>
  <script language="JavaScript">
  function initFocus() {}
  </script>
  <script language="JavaScript" src="<?php echo $PATH; ?>/includes/jslib.js"></script>
</head>
<body bgcolor="#ffffff" topmargin="0" leftmargin="0">
<br><br>
<div align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="<?php echo $MARGIN; ?>"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="<?php echo $MARGIN; ?>" height="1"></td>
    <td class="general" width="99%">
<!-- Begin content -->
