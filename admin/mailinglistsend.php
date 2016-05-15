<?php
include("path.inc");
include("includes/header.inc");

$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $mailingfooter = $row->am_mailingfooter;
  $mailingfromemail = $row->am_mailingfromemail;
  $mailingfromname = $row->am_mailingfromname;
}
       
$arg = $_REQUEST["arg"];
$all = $_REQUEST['all'];
$id = $_REQUEST["id"];
$ml_id = $_REQUEST["ml_id"];

$sql = "SELECT * FROM site_mailings " .
       "WHERE mail_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $subject = $row->mail_subject;
}
?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3"><?php echo $am_name; ?> Mailing List</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Name</td>
    <td class="fg2">Email</td>
    <td class="fg2">Format</td>
  </tr>
<?php
$count = 0;
if ($all == "1") {
  $sql = "SELECT * FROM site_maillist " .
         "WHERE ml_email > '' " .
         "  AND ml_emailformat > '' " .
         "  AND ml_confirm='C' " .
         "ORDER BY ml_last, ml_first";
}
else {
  $sql = "SELECT * FROM site_maillist " .
         "WHERE ml_id='$ml_id' " .
         "  AND ml_confirm='C' ";
}

$result = mysql_query($sql, $connect);
$sentlist = "";
while ($row = mysql_fetch_object($result)) {
  $email = $row->ml_email;
  if (stristr($sentlist, $email)) {
    continue;
  }
  $sentlist .= "|$email";
  $firstname = $row->ml_first;
  $lastname = $row->ml_last;
  $emailformat = "";
  if ($row->ml_emailformat == "T") {
    $emailformat = "Text";
  }
  if ($row->ml_emailformat == "H") {
    $emailformat = "HTML";
  }
  $name = "$firstname $lastname";
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><?php echo $name; ?></td>
    <td class="fg5"><?php echo $email; ?></td>
    <td class="fg5"><?php echo $emailformat; ?></td>
  </tr>
<?php
  if ($emailformat == "Text") {
    mail("$email", "Pastor's Email - $subject", `nroff ../tmp/texttmp`, "From: $mailingfromname <$mailingfromemail>");
  }
  elseif ($emailformat == "HTML") {
    mail("$email", "Pastor's Email - $subject", `cat ../tmp/htmltmp`, "Content-type: text/html\nFrom: $mailingfromname <$mailingfromemail>");
  }
$count++;
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="3" class="fg5">
      <b><?php echo $count; ?> sent.</b>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
