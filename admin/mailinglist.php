<?php
include("path.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

include($PATH . "/mailing/mailing.php");
include("includes/header.inc");
?>
  <script language="JavaScript">
  function getMailing() {
    var f = document.forms["data"];
    f.action = "mailinglist.php";
    f.arg.value = "1";
    f.submit();
  }

  function getMaillist() {
    var f = document.forms["data"];
    if (f.ml_id.selectedIndex == 0) return;
    f.all.checked = false;
  }

  function send() {
    var f = document.forms["data"];
    f.submit();
  }

  function preview(fmt) {
    var pvw;
    pvw = open("mailingpreview.php?fmt="+fmt, "pvw", "width=750,height=480,screenX=50,screenY=50,top=50,left=50,scrollbars=Yes");
  }
  </script>
<form name="data" method="post" action="mailinglistsend.php">
<input type="hidden" name="arg" value="">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2"><?php echo $am_name; ?> Mailing List</th>
  </tr>
  <tr valign="top" bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Mailing</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2">
      <select name="id" onchange="getMailing()">
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

    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Recipient</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="ml_id" onchange="getMaillist()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_maillist " .
       "WHERE ml_email > '' " .
       "  AND ml_emailformat > '' " .
       "ORDER BY ml_last, ml_first";
$result = mysql_query($sql, $connect);
$count = mysql_num_rows($result);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->ml_id == $ml_id ? "selected" : "";
  $listitem = $row->ml_email;
  echo "<option $selected value='$row->ml_id'>$listitem</option>\n";
}
?>
      </select>
    </td>
    <td class="fg5"><input type=checkbox name="all" checked value="1"> Send to all (<?php echo $count; ?> addresses)</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <input type="button" value="Preview Text" onclick="preview(0)">
      <input type="button" value="Preview HTML" onclick="preview(1)">
      <input type=button value="Send" onclick="send()">
    </td>
  </tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
