<?php
include("path.inc");
include("$PATH/includes/lib.inc");

$q = $_SERVER["QUERY_STRING"];
list($id, $confirmid) = explode("|", $q);

$sql = "SELECT * FROM site_maillist " .
       "WHERE ml_id='$id' " .
       "  AND ml_confirm='$confirmid'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $format = $row->ml_format;
  $email = $row->ml_email;
  $sql = "UPDATE site_maillist " .
         "SET ml_confirm='' " .
         "WHERE ml_id='$id'";
  mysql_query($sql, $connect);
  $recipient = $email;
  $id = mysql_insert_id($connect);

  $sql = "SELECT * FROM site_general ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $confirmfrom = $row->g_confirmfrom;
    $confirmsubject = $row->g_confirmsubject;
    $confirmtext = $row->g_confirmtext;
  }
  if ($format == "T") {
    $confirm = "http://www.slochurch.com/confirm.php?$id|$confirmid";
  }
  else {
    $confirm = "<a href='http://www.slochurch.com/confirm.php?$id|$confirmid'>Confirm</a>";
  }

  $mailobj = new EmailObject;
  $mailobj->set_from($confirmfrom);
  $mailobj->set_to($recipient);
  $mailobj->set_subject($confirmsubject);
  $mailobj->set_message($confirmtext);
  $mailobj->set_format($format);
  $mailobj->send();
  
  $location = "confirm_response.php";
}
else {
  $location = "noconfirm.php";
}

header("Location: $location");
?>
