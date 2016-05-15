<?php
include("path.inc");
include("includes/lib_admin.inc");
include("includes/colors.inc");

$ymd = $_REQUEST["ymd"];
$ccid = $_REQUEST["ccid"];
$catlist = get_categories($ccid);
if ($ccid) {
  array_unshift($catlist, $ccid);
}
$seq = $_REQUEST["seq"];

if (!$seq) {
  $seq = 0;
}

?>
<html>
<head>
  <title>Calendar for <?php echo date("F j, Y", strtotime($ymd)); ?></title>
  <style type="text/css">
<?php include("style.css"); ?>
  .date {
  font-family:arial, helvetica;
  font-size:10pt;
  color:<?php echo $fg1; ?>;
  }
  .cell {
  font-family:arial;
  font-size:8pt;
  }
  </style>
</head>
<body bgcolor="<?php echo $bgcolor; ?>" link="<?php echo $linkCol; ?>" alink="<?php echo $alinkCol; ?>" vlink="<?php echo $vlinkCol; ?>">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="date"><?php echo date("F j, Y", strtotime($ymd)); ?></th>
  </tr>
<?php
if ($seq !== "") {
  $sql = "SELECT * FROM site_calendar " .
         "LEFT JOIN site_calendar_categories ON cal_ccid=cc_id " .
         " WHERE cal_ymd='$ymd' " .
         "   AND cal_ccid IN (" . implode(",", $catlist) . ") " .
         "   AND cal_seq='$seq' " .
         "   AND cal_status<>'U' ";
  $result = check_mysql($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $category = $row->cc_category;
    $item = $row->cal_item;
    if ($category) {
      $event = $category;
    }
    if ($item) {
      $event = $item;
    }
    $description = preg_replace("/\r\n/", "<br>", $row->cal_description);
  }
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="cell"><b><?php echo $event; ?></b></td>
  </tr>
<?php
  if ($description) {
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="cell"><?php echo $description; ?></td>
  </tr>
<?php
  }
}
else {
  $sql = "SELECT * FROM site_calendar " .
         " WHERE cal_ymd='$ymd' " .
         "   AND cal_ccid IN (" . implode(",", $catlist) . ") " .
         "   AND cal_status<>'U' " .
         " ORDER BY cal_seq";
  $result = check_mysql($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="cell"><b><?php echo $row->cal_item; ?></b></td>
  </tr>
  <tr>
    <td class="cell"><?php echo $row->cal_description; ?><br><br></td>
  </tr>
<?php
  }
}
?>
</table>
</body>
</html>

