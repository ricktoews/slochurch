<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$metatitle = $_REQUEST["metatitle"];
$metadescription = $_REQUEST["metadescription"];
$metakeywords = $_REQUEST["metakeywords"];
$text = $_REQUEST["text"];
$layout = $_REQUEST["layout"];
$datetime = date("Y-m-d h:i:s");

if ($arg == "2") {
  $sql = "UPDATE site_content " .
         "SET cont_metatitle='$metatitle', " .
         "    cont_metadescription='$metadescription', " .
         "    cont_metakeywords='$metakeywords', " .
         "    cont_text='$text', " .
         "    cont_layout='$layout', " .
         "    cont_date='$datetime' " .
         "WHERE cont_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: content.php");

?>
