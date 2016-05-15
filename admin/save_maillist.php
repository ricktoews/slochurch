<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST['id'];
$first = $_REQUEST["first"];
$last = $_REQUEST["last"];
$address = $_REQUEST["address"];
$address2 = $_REQUEST["address2"];
$city = $_REQUEST["city"];
$state = $_REQUEST["state"];
$zip = $_REQUEST["zip"];
$phone = unformat_phone($_REQUEST["phone"]);
$email = $_REQUEST["email"];
$emailformat = $_REQUEST["emailformat"];

if ($arg == "2") {
  if ($id) {
// Update information.
    $sql = "UPDATE site_maillist " .
           "SET ml_first='$first', " .
           "    ml_last='$last', " .
           "    ml_address='$address', " .
           "    ml_address2='$address2', " .
           "    ml_city='$city', " .
           "    ml_state='$state', " .
           "    ml_zip='$zip', " .
           "    ml_phone='$phone', " .
           "    ml_confirm='C', " .
           "    ml_email='$email', " .
           "    ml_emailformat='$emailformat' " .
           "WHERE ml_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
// Add information.
    $sql = "INSERT INTO site_maillist " .
           "SET ml_first='$first', " .
           "    ml_last='$last', " .
           "    ml_address='$address', " .
           "    ml_address2='$address2', " .
           "    ml_city='$city', " .
           "    ml_state='$state', " .
           "    ml_zip='$zip', " .
           "    ml_phone='$phone', " .
           "    ml_confirm='C', " .
           "    ml_email='$email', " .
           "    ml_emailformat='$emailformat' ";
    mysql_query($sql, $connect);
  }
}
// Delete member  information.
else if ($arg == "4") {
  $sql = "DELETE FROM site_maillist " .
         "WHERE ml_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: maillist.php");
?>
