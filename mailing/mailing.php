<?php 
include($PATH . "/includes/connect.inc");

$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $mailingcontact = $row->am_mailingcontact;
  $mailingcontact = preg_replace("/\r\n/", "<br>", $mailingcontact);
  $mailingfooter = $row->am_mailingfooter;
  $mailingfooter = preg_replace("/\r\n/", "<br>", $mailingfooter);
}

// Set up HTML format mailing.
$get = "GET /mailing/pastoremail.php?id=$id HTTP/1.0\n" .
       "Host: www.slochurch.com\n" .
       "Connection: Close\n" .
       "\n";

$fp = fsockopen("www.slochurch.com", 80);
if ($fp) {
  fputs($fp, $get);
  $page = "";
  $keep = false;
  while (!feof($fp)) {
    $response = fgets($fp, 1024);
    if ($keep) {
      $page .= $response;
    }
    $keep = $keep || $response == "\r\n";
  }
}

$fp = fopen("../tmp/htmltmp", "w");
fwrite($fp, $page);
fclose($fp);

// Set up Text format mailing.
$weirdapostrophe = chr(146);
$weirdopenq = chr(147);
$weirdcloseq = chr(148);

$path = "http://www.slochurch.com";
if ($id) {
  $sql = "SELECT * FROM site_mailings " .
         "WHERE mail_id='$id'";
}
else {
  $sql = "SELECT * FROM site_mailings " .
         "ORDER BY mail_date DESC";
}
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $id = $row->mail_id;
  $subject = $row->mail_subject;
  $content = '';
  $sql2 = "SELECT * FROM site_mailing_sections " .
          "WHERE ms_mailid='$id' " .
          "ORDER BY ms_seq";
  $result2 = mysql_query($sql2, $connect);
  while ($row2 = mysql_fetch_object($result2)) {
    $ms_content = $row2->ms_content;
    $ms_content = preg_replace("/$weirdapostrophe/", "'", $ms_content);
    $ms_content = preg_replace("/$weirdopenq/", "\"", $ms_content);
    $ms_content = preg_replace("/$weirdcloseq/", "\"", $ms_content);
    $content = $content .
               $row2->ms_name . "\n\n" .
               $ms_content . "\n\n\n\n";
  }
  $content = ereg_replace("\n", "\n.br\n", $content);
}
$mdate = date("F j");


$mailing =
  ".ll 70\n.nh\n" .
  ".ce\n\n.ce\n$mdate - $subject\n\n".
  "$content\n\n".
  "----------------------------------------------------------------------\n";

$mailing = ereg_replace("<[^>]*>", "", $mailing);

$fp = fopen("../tmp/texttmp", "w");
fwrite($fp, $mailing);
fclose($fp);
?>
