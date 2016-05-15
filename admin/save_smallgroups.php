<?php
include("path.inc");
include("includes/lib_admin.inc");

$id = $_REQUEST['id'];
$name = $_REQUEST['name'];
$contact = $_REQUEST['contact'];
$location = $_REQUEST['location'];
$description = $_REQUEST['description'];
$phone = unformat_phone($_REQUEST['phone']);
$email = $_REQUEST['email'];
$time = $_REQUEST['time'];
$followid = $_REQUEST['followid'];

// Update group.
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_smallgroups " .
           "SET sg_name='$name', " .
           "    sg_contact='$contact', " .
           "    sg_phone='$phone', " .
           "    sg_email='$email', " .
           "    sg_description='$description', " .
           "    sg_location='$location', " .
           "    sg_time='$time' " .
           "WHERE sg_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
// Add group.
    $sql = "INSERT INTO site_smallgroups " .
           "SET sg_name='$name', " .
           "    sg_contact='$contact', " .
           "    sg_phone='$phone', " .
           "    sg_email='$email', " .
           "    sg_description='$description', " .
           "    sg_location='$location', " .
           "    sg_time='$time' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_smallgroups " .
         "WHERE sg_id <> '$id' " .
         "ORDER BY sg_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->sg_id;
    if ($followid == $row->sg_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_smallgroups " .
           "SET sg_seq='$s' " .
           "WHERE sg_id='$i'";
    mysql_query($sql, $connect);
  }
}
// Delete group.
else if ($arg == "4") {
  $sql = "DELETE FROM site_smallgroups " .
         "WHERE sg_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: smallgroups.php");
?>
