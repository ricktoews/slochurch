<?php
include("path.inc");
include("$PATH/includes/lib.inc");

$id = $_REQUEST["id"];

$sql = "SELECT * FROM site_photos " .
       "WHERE p_id='$id' ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $gallery_detail = new Gallery($row);
}
?>
<html>
<head>
  <title><?php echo $gallery_detail->caption; ?></title>
  <style type="text/css">
  .caption {
    font-weight:bold;
    color:#333333;
    font-family:arial; helvetica;
    font-size:8pt;
  }
  </style>
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <div align="center">
      <img src="<?php echo $gallery_detail->picture; ?>" alt="<?php echo $gallery_detail->caption; ?>" border="0" /><br />
      <div class="caption"><?php echo $gallery_detail->caption; ?></div>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
