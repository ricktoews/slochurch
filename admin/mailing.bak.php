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
$id = $_REQUEST["id"];
?>
<?php

// If a particular mailing date has been requested, look it up.
if ($arg == "1") {
  $sql = "SELECT * FROM site_mailings " .
         "WHERE mail_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $date = $row->mail_date;
    $content = $row->mail_content;
    $subject = $row->mail_subject;
  }
}
?>
  <script language="JavaScript">
  function newMailing() {
    location = "mailing.php";
  }

  function getMailing(d) {
    if (!d) return;
    var f = document.forms[0];
    f.action = "mailing.php";
    f.submit();
  }

  function updateMailing() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteMailing() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      f.arg.value = "4";
      f.submit();
    }
  }
  </script>
<form name="mailing" method=post action="save_mailing.php">
<input type=hidden name="arg" value="1">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr valign="top" bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Mailings</th>
  </tr>
  <tr valign="top" bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Mailing</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="id" onchange="getMailing(this.options[this.selectedIndex].value)">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_mailings ORDER BY mail_date DESC";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  list($ty,$tm,$td) = explode("-", $row->mail_date);
  $subj = substr($row->mail_subject,0,30);
  $subj = date("F j, Y", mktime(0,0,0,$tm,$td,$ty)) . " - $subj";
  $selected = $row->mail_id == $id ? "selected" : ""; 
  echo "<option $selected value='$row->mail_id'>$subj</option>\n";
}
?>
      </select>

      <input type=button value="New" onclick="newMailing()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Subject</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><input type="text" name="subject" size="70" value="<?php echo $subject; ?>"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Content</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><textarea name="content" rows="10" cols="70" wrap="physical"><?php echo stripslashes($content); ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">From Email</td>
    <td class="fg2">From Name</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="mailingfromemail" value="<?php echo $mailingfromemail; ?>" size="30"></td>
    <td class="fg5"><input type="text" name="mailingfromname" value="<?php echo $mailingfromname; ?>" size="30"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Footer</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><textarea name="mailingfooter" rows="2" cols="70" wrap="physical"><?php echo stripslashes($mailingfooter); ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2">
<?php
if ($id) {
?>
      <input type="button" value="Update" onclick="updateMailing()">
      <input type="button" value="Delete" onclick="deleteMailing()">
<?php
}
else {
?>
      <input type="button" value="Add" onclick="updateMailing()">
<?php
}
?>
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
