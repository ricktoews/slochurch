<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
?>
<?php
// Retrieve user information.
if ($arg == "1") {
  $sql = "SELECT * FROM site_maillist " .
         "WHERE ml_id='$id' ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $last = $row->ml_last;
    $first = $row->ml_first;
    $address = $row->ml_address;
    $address2 = $row->ml_address2;
    $city = $row->ml_city;
    $state = $row->ml_state;
    $zip = $row->ml_zip;
    $phone = format_phone($row->ml_phone);
    $email = $row->ml_email;
    $emailformat = $row->ml_emailformat;
  }
}
?>
  <script language="JavaScript">
  function getMaillist() {
    var f = document.forms["data"];
    f.arg.value = "1";
    f.action = "maillist.php";
    f.submit();
  }

  function newMaillist() {
    location = "maillist.php";
  }

  function updateMaillist() {
    var f = document.forms["data"];
    f.arg.value = "2";
    f.submit();
  }

  function deleteMaillist() {
    var f = document.forms["data"];
    f.arg.value = "4";
    f.submit();
  }

  function initFocus() {
    document.forms["data"].email.focus();
  }

  onload=initFocus;
  </script>
<form name="data" method="post" action="save_maillist.php" enctype="multipart/form-data">
<input type=hidden name="arg" value="">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">Maillist List Maintenance</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Email</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2" class="fg5">
      <select name="id" onchange="getMaillist()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_maillist " .
       "ORDER BY ml_last, ml_first";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->ml_id == $id ? "selected" : "";
  $listitem = $row->ml_email;
  echo "<option $selected value='$row->ml_id'>$listitem</option>\n";
}
?>
      </select>
      <input type=button value="New" onclick="newMaillist()">
    </td>
  </tr>
<!--
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">First Name</td>
    <td class="fg2" colspan="2">Last Name</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="first" value="<?php echo $first; ?>" size=20></td>
    <td class="fg5" colspan="2"><input type=text name="last" value="<?php echo $last; ?>" size=20></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Address</td>
    <td class="fg2">Address 2</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="2"><input type="text" name="address" value="<?php echo $address; ?>" size="20"></td>
    <td class="fg5"><input type="text" name="address2" value="<?php echo $address2; ?>" size="20"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">City</td>
    <td class="fg2">State</td>
    <td class="fg2">Zip</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="city" value="<?php echo $city; ?>" size="20"></td>
    <td class="fg5"><input type="text" name="state" value="<?php echo $state; ?>" size="2"></td>
    <td class="fg5"><input type="text" name="zip" value="<?php echo $zip; ?>" size="10"></td>
  </tr>
-->
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Email</td>
    <td class="fg2">Email Format</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="email" value="<?php echo $email; ?>" size="30" onblur="valEmail(this)"></td>
    <td class="fg5">
<?php $checked = $emailformat == "H" ? "checked" : ""; ?>
      <input type=radio name="emailformat" <?php echo $checked; ?> value="H"> HTML / 
<?php $checked = $emailformat != "H" ? "checked" : ""; ?>
      <input type=radio name="emailformat" <?php echo $checked; ?> value="T"> Plain Text
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align=center colspan="2">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateMaillist()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateMaillist()">
      <input type=button value="Delete" onclick="deleteMaillist()">
<?php } ?>
    </td>
  </tr>
</table>
<p align="center"><a href="export_maillist.php">Export Mailing List</a></p>
</form>
<?php
include("includes/footer.inc");
?>
