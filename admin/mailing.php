<?php
include("path.inc");
include("includes/header.inc");

$JUSTIFICATION = array('Right', 'Left');

$sql = "SELECT * FROM site_admin_misc ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $mailingfooter = $row->am_mailingfooter;
  $mailingfromemail = $row->am_mailingfromemail;
  $mailingfromname = $row->am_mailingfromname;
  $mailingcontact = $row->am_mailingcontact;
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
    $mailid = $row->mail_id;
    $date = $row->mail_date;
    $contact = $row->mail_contact;
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
<form name="mailing" method=post action="save_mailing.php" enctype="multipart/form-data">
<input type=hidden name="arg" value="1">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr valign="top" bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Mailings</th>
  </tr>
  <tr valign="top" bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Mailing</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
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
    <td class="fg2">From Email</td>
    <td class="fg2">From Name</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="mailingfromemail" value="<?php echo $mailingfromemail; ?>" size="30"></td>
    <td class="fg5"><input type="text" name="mailingfromname" value="<?php echo $mailingfromname; ?>" size="30"></td>
    <td class="fg5"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Subject</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3"><input type="text" name="subject" size="70" value="<?php echo $subject; ?>"></td>
  </tr>
<?php
$s = 0;
$sql = "SELECT * FROM site_mailing_sections " .
       "WHERE ms_mailid='$mailid' " .
       "ORDER BY ms_seq";
$result = mysql_query($sql, $connect);
while ($s < 1) {
  if ($row = mysql_fetch_object($result)) {
    $msid = $row->ms_id;
    $name = $row->ms_name;
    $align = $row->ms_align;
    $image = $row->ms_image;
    $image2 = $row->ms_image2;
    $image3 = $row->ms_image3;
    $content = $row->ms_content;
  }
  else {
    $msid = '';
    $name = '';
    $align = '';
    $image = '';
    $image2 = '';
    $image3 = '';
    $content = '';
    $s++;
  }
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Section Name</td>
    <td class="fg2">Image(s) alignment</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="hidden" name="msidArr[]" value="<?php echo $msid; ?>" />
      <input type="text" name="msnameArr[]" size="40" value="<?php echo $name; ?>">
    </td>
    <td class="fg5">
      <select name="msalignArr[]">
<?php
foreach($JUSTIFICATION as $n => $j) {
  $selected = $n == $align ? 'selected' : '';
  echo "<option $selected value='$n'>$j</option>\n";
}
?>
      </select>
    </td>
    <td class="fg5"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Image (optional)</td>
    <td class="fg2">Image 2 (optional)</td>
    <td class="fg2">Image 3 (optional)</td>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="hidden" name="current_msimageArr[]" value="<?php echo $image; ?>" />
      <input type="file" name="upload_msimageArr[]" /><br />
<?php
  if (strlen($image) > 0 && file_exists($MAILING_PATH . '/' . $image)) {
?>
      <img src="<?php echo $MAILING_PATH; ?>/<?php echo $image; ?>" border="0" /><br />
      <?php echo $image; ?><br />
      <input type="checkbox" name="del_msimageArr[]" value="1" /> Delete this image
<?php
  }
?>
    </td>
    <td class="fg5">
      <input type="hidden" name="current_msimage2Arr[]" value="<?php echo $image2; ?>" />
      <input type="file" name="upload_msimage2Arr[]" /><br />
<?php
  if (strlen($image2) > 0 && file_exists($MAILING_PATH . '/' . $image2)) {
?>
      <img src="<?php echo $MAILING_PATH; ?>/<?php echo $image2; ?>" border="0" /><br />
      <?php echo $image2; ?><br />
      <input type="checkbox" name="del_msimage2Arr[]" value="1" /> Delete this image
<?php
  }
?>
    </td>
    <td class="fg5">
      <input type="hidden" name="current_msimage3Arr[]" value="<?php echo $image3; ?>" />
      <input type="file" name="upload_msimage3Arr[]" /><br />
<?php
  if (strlen($image3) > 0 && file_exists($MAILING_PATH . '/' . $image3)) {
?>
      <img src="<?php echo $MAILING_PATH; ?>/<?php echo $image3; ?>" border="0" /><br />
      <?php echo $image3; ?><br />
      <input type="checkbox" name="del_msimage3Arr[]" value="1" /> Delete this image
<?php
  }
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Content</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3"><textarea name="mscontentArr[]" rows="10" cols="70" wrap="physical"><?php echo stripslashes($content); ?></textarea></td>
  </tr>
<?php
}
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Footer</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3"><textarea name="mailingfooter" rows="2" cols="70" wrap="physical"><?php echo stripslashes($mailingfooter); ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3">
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
