<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$filename = $_REQUEST["filename"];
$tag = $_REQUEST["tag"];
$helptext = $_REQUEST["helptext"];

for ($t = 0; $t < sizeof($tag); $t++) {
  if ($id[$t]) {
    $sql = "UPDATE site_admin_help " .
           "SET ah_helptext='$helptext[$t]' " .
           "WHERE ah_id='$id[$t]'";
  }
  else {
    $sql = "INSERT INTO site_admin_help " .
           "SET ah_filename='$filename', " .
           "    ah_tag='$tag[$t]', " .
           "    ah_helptext='$helptext[$t]' ";
  }
  mysql_query($sql, $connect);
}

header("Location: admin_help.php");
?>
