<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$contact_email = $_REQUEST["contact_email"];
$confirmfrom = $_REQUEST["confirmfrom"];
$confirmsubject = $_REQUEST["confirmsubject"];
$confirmtext = $_REQUEST["confirmtext"];
$metatitle = $_REQUEST["metatitle"];
$metakeywords = $_REQUEST["metakeywords"];
$metadescription = $_REQUEST["metadescription"];

if ($arg == "1") {
  $sql = "UPDATE site_general " .
         "SET g_contact_email='$contact_email', " .
         "    g_confreqfrom='$confreqfrom', " .
         "    g_confreqsubject='$confreqsubject', " .
         "    g_confreqtext='$confreqtext', " .
         "    g_confirmfrom='$confirmfrom', " .
         "    g_confirmsubject='$confirmsubject', " .
         "    g_confirmtext='$confirmtext', " .
         "    g_metatitle='$metatitle', " .
         "    g_metakeywords='$metakeywords', " .
         "    g_metadescription='$metadescription' ";
  mysql_query($sql, $connect);
}

$sql = "SELECT * FROM site_general ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $contact_email = $row->g_contact_email;
  $confreqfrom = $row->g_confreqfrom;
  $confreqsubject = $row->g_confreqsubject;
  $confreqtext = $row->g_confreqtext;
  $confirmfrom = $row->g_confirmfrom;
  $confirmsubject = $row->g_confirmsubject;
  $confirmtext = $row->g_confirmtext;
  $metatitle = $row->g_metatitle;
  $metakeywords = $row->g_metakeywords;
  $metadescription = $row->g_metadescription;
}
      
?>
<form method="post" action="general.php">
<input type=hidden name="arg" value="1">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">General Settings</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Email Address for Contact Page</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5" colspan="2"><input type="text" name="contact_email" value="<?php echo $contact_email; ?>" size="20"></td>
  </tr>
  <tr bgcolor="<?php echo $bg3; ?>">
    <th class="fg3" colspan="2">Confirmation Request Email Settings</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">From Address for Confirmation Request Email</td>
    <td class="fg2">Confirmation Email Request Subject</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="confreqfrom" value="<?php echo $confreqfrom; ?>" size="50"></td>
    <td class="fg5"><input type="text" name="confreqsubject" value="<?php echo $confreqsubject; ?>" size="50"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Confirmation Request Email Text</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <textarea name="confreqtext" rows="5" cols="80" wrap="physical"><?php echo $confreqtext; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg3; ?>">
    <th class="fg3" colspan="2">Confirmation Email Settings</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">From Address for Confirmation Email</td>
    <td class="fg2">Confirmation Email Subject</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="confirmfrom" value="<?php echo $confirmfrom; ?>" size="50"></td>
    <td class="fg5"><input type="text" name="confirmsubject" value="<?php echo $confirmsubject; ?>" size="50"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Confirmation Email Text</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <textarea name="confirmtext" rows="5" cols="80" wrap="physical"><?php echo $confirmtext; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">General Title Bar Content</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><input type="text" name="metatitle" value="<?php echo $metatitle; ?>" size="50"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">General Meta Keyword Content</td>
    <td class="fg2">General Meta Description Content</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <textarea name="metakeywords" rows="5" cols="40" wrap="physical"><?php echo $metakeywords; ?></textarea>
    </td>
    <td class="fg5">
      <textarea name="metadescription" rows="5" cols="40" wrap="physical"><?php echo $metadescription; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="2">
      <input type="submit" value="Update">
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
