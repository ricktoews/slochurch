<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$tag = $_REQUEST["tag"];
$title = $_REQUEST["title"];

// Add a customizable page.
if ($arg == "2") {
  if (!$id) {
    $sql = "INSERT INTO site_content " .
           "SET cont_tag='$tag', " .
           "    cont_metatitle='$title' ";
//  echo "SQL: $sql<br>";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Update a customizable page.
  else {
    $sql = "UPDATE site_content " .
           "SET cont_tag='$tag', " .
           "    cont_metatitle='$title' " .
           "WHERE cont_id='$id'";
// echo "SQL: $sql<br>";
    mysql_query($sql, $connect);
  }
}
// Delete a customizable page.
elseif ($arg == "4") {
  $sql = "DELETE FROM site_content " .
         "WHERE cont_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: site_content.php");
?>
