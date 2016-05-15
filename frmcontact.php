<?php
include("path.inc");
$HEADING = "contact_us";
include("includes/frmheader.inc");

$id = $_REQUEST['id'];

if ($id) {
  $sql = "SELECT * FROM site_staff, site_members, site_offices " .
         "WHERE s_memberid=m_id " .
         "  AND s_officeid=o_id " .
         "  AND s_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $office = $row->o_office;
    $contact = format_name($row->m_first, $row->m_last);
  }
  $recipient = "$contact ($office)";
}
else {
  $recipient = "the church office";
}
$ff = new Facts();
?>
<script language="JavaScript1.2">
<!--
function validate() {
  alert("Form disabled for demo.");
  return false;

  var f = document.forms[0];
  var fldlist = "";
  if (f.from.value.length == 0) {
    fldlist += "Name\n";
  }
  if (f.email.value.length == 0) {
    fldlist += "Email address\n";
  }
  if (f.subject.value.length == 0) {
    fldlist += "Subject\n";
  }
  if (f.comments.value.length == 0) {
    fldlist += "Comments\n";
  }
  if (fldlist.length) {
    alert("Before sending the email, please supply the following:\n\n"+fldlist);
    return false;
  }
  else {
    return true;
  }
}
//-->
</script>
<div class="cell">
<p>
<span class="fg2"><b><?php echo $Site->name; ?></b></span><br />
<?php
if ($ff->churchimage && file_exists("$FACTS_PATH/$ff->churchimage")) {
  echo "<img src='$FACTS_PATH/$ff->churchimage'  align='left' />";
}
?>
<?php echo $ff->address; ?><br />
<?php echo $ff->fmtphone; ?><br />
<a href="#"><?php echo $Site->email; ?></a><br clear="all" />
</p>
<?php
if ($sent == "1") {
?>
<p>
Thank you.  Your message has been sent to <?php echo $recipient; ?>.
<p>
<?php
}
else {
?>
<p>Message to be sent to <?php echo $recipient; ?>.</p>
</div>
<form method="post" action="" onsubmit="return validate()">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="cont" value="<?php echo $cont; ?>" />
<table>
  <tr>
    <td class="cell">Your Name </td>
    <td><input type="text" name="from" size="25" maxlength="50" /></td>
  </tr>
  <tr>
    <td class="cell">Email </td>
    <td><input type="text" name="email" size="25" maxlength="50" /></td>
  </tr>
  <tr>
    <td class="cell">Subject </td>
    <td><input type="text" name="subject" size="25" maxlength="50" /></td>
  </tr>
</table>
<table>
  <tr>
    <td class="cell">Comments </td>
  </tr>
  <tr>
    <td><textarea style="<?php echo $FIELD_STYLE; ?>" name="comments" rows="3" cols="40" wrap="physical"></textarea></td>
  </tr>
  <tr>
    <td align="center">
      <input type=submit value="Send" />
    </td>
  </tr>
</table>
</form>
<?php
}
?>
<?php
include("includes/frmfooter.inc");
?>
