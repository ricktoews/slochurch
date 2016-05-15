<?php
include("path.inc");
include("$PATH/includes/lib.inc");

$email = $_REQUEST['email'];
$format = 'T';

$status = 0;
$sql = "SELECT * FROM site_maillist " .
       "WHERE ml_email='$email' ";
$result = mysql_query($sql, $connect);
if (mysql_num_rows($result) != 0) {
  $status = 1;
}

if ($status == 0) {
  $confirmid = gen_confirmid();
  $password = gen_confirmid();
  $today = date("Y-m-d");
  $sql = "INSERT INTO site_maillist " .
         "SET ml_email='$email', " .
         "    ml_emailformat='$format', " .
         "    ml_confirm='$confirmid', " .
         "    ml_date='$today'";
  mysql_query($sql, $connect);

  $recipient = $email;
  $confreqsubject = "Pastor's Email List Sign-up Confirmation";
  $id = mysql_insert_id($connect);

  $sql = "SELECT * FROM site_general ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $confreqfrom = $row->g_confreqfrom;
    $confreqsubject = $row->g_confreqsubject;
    $confreqtext = $row->g_confreqtext;
  }
  if ($format == "T") {
    $confirm = "http://www.slochurch.com/confirm.php?$id|$confirmid";
  }
  else {
    $confirm = "<a href='http://www.slochurch.com/confirm.php?$id|$confirmid'>Confirm</a>";
  }
  $confreqtext = preg_replace("/\[confirm\]/", $confirm, $confreqtext);

  $recipient = $email;
  $mailobj = new EmailObject;
  $mailobj->set_from($confreqfrom);
  $mailobj->set_to($recipient);
  $mailobj->set_subject($confreqsubject);
  $mailobj->set_message($confreqtext);
  $mailobj->set_format($format);
  $mailobj->send();

  $location = "signup_response.php";
}
else {
  $location = "signup_response.php?s=$status";
}

header("Location: $location");
?>
