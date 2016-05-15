<?php
include("path.inc");
include("includes/lib.inc");

$id = $_REQUEST['id'];
$from = $_REQUEST['from'];
$email = $_REQUEST['email'];
$subject = $_REQUEST['subject'];
$comments = $_REQUEST['comments'];

$args = "?sent=1";
$contact = $Site->name;
$recipient = $Site->email;
if ($id) {
  $args .= "&id=$id";
  $sql = "SELECT * FROM site_staff, site_members, site_offices " .
         "WHERE s_memberid=m_id " .
         "  AND s_officeid=o_id " .
         "  AND s_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $contact = format_name($row->m_first, $row->m_last);
    if ($row->o_office) {
      $contact .= " ($row->o_office)";
    }
  }
  $recipient = $row->m_email;
  $subject .= " ($Site->name Contact page)";
}  

$OK_TO_SEND = TRUE;
if ($OK_TO_SEND) {
    mail("$contact <$recipient>", "$subject", "$comments", "From: $from <$email>\nCc: $Site->name <$Site->email>");
}

header("Location: frmcontact.php$args");
?>
