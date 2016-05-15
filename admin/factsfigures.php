<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$phone = unformat_phone($_REQUEST['phone']);
$address = $_REQUEST['address'];
$membership = $_REQUEST['membership'];
$avgattendance = $_REQUEST['avgattendance'];
$upload_image = array();
$image_name = array();
$image_tmpname = array();
$del_image = array();
for ($i = 1; $i <= 3; $i++) {
  $upload_image[$i-1] = $_FILES["upload_image_$i"];
  $image_name[$i-1] = $upload_image[$i-1]["name"];
  $image_tmpname[$i-1] = $upload_image[$i-1]["tmp_name"];
  $del_image[$i-1] = $_REQUEST["del_image_$i"];
}

for ($i = 0; $i < 3; $i++) {
  if ($upload_image[$i]) {
    move_uploaded_file($image_tmpname[$i], "$FACTS_PATH/$image_name[$i]");
  }
}

if ($arg == "1") {
// Deal with image upload.
  for ($i = 0; $i < 3; $i++) {
    $ndx = $i + 1;
    $sql = "SELECT ff_image$ndx AS ff_image FROM site_facts_figures ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      if ($row->ff_image && $row->ff_image != $image_name[$i] && $image_name[$i]) {
        if (is_file("$FACTS_PATH/$row->ff_image")) {
          unlink("$FACTS_PATH/$row->ff_image");
        }
      }
    }
    if ($del_image[$i]) {
      if (is_file("$FACTS_PATH/$row->ff_image")) {
        unlink("$FACTS_PATH/$row->ff_image");
        $sql = "UPDATE site_facts_figures " .
               "SET ff_image$ndx='' ";
        mysql_query($sql, $connect);
      }
    }
  }

  $sql = "UPDATE site_facts_figures " .
         "SET ff_phone='$phone', " .
         "    ff_address='$address', " .
         "    ff_membership='$membership', ";
  for ($i = 0; $i < 3; $i++) {
    $ndx = $i + 1;
    if ($image_name[$i]) {
      $sql .= "    ff_image$ndx='$image_name[$i]', ";
    }
  }
  $sql .=
         "    ff_avgattendance='$avgattendance' ";
  mysql_query($sql, $connect);
}

$sql = "SELECT * FROM site_facts_figures ";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $phone = format_phone($row->ff_phone);
  $address = $row->ff_address;
  $membership = $row->ff_membership;
  $avgattendance = $row->ff_avgattendance;
  $ff_image1 = $row->ff_image1;
  $ff_image2 = $row->ff_image2;
  $ff_image3 = $row->ff_image3;
}
?>
<form method="post" action="factsfigures.php" enctype="multipart/form-data">
<input type=hidden name="arg" value="1">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">Facts and Figures</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Church Address Image</td>
    <td class="fg2">Services Image</td>
    <td class="fg2" colspan="2">Small Groups Image</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="file" name="upload_image_1" /><br />
<?php
if ($ff_image1 && file_exists($FACTS_PATH . '/' . $ff_image1)) {
?>
      <img src="<?php echo $FACTS_PATH; ?>/<?php echo $ff_image1; ?>" border="0" /><br />
      <?php echo $ff_image1; ?><br />
      <input type="checkbox" name="del_image_1" value="1" /> Delete this image
<?php
}
?>
    </td>
    <td class="fg5">
      <input type="file" name="upload_image_2" /><br />
<?php
if ($ff_image2 && file_exists($FACTS_PATH . '/' . $ff_image2)) {
?>
      <img src="<?php echo $FACTS_PATH; ?>/<?php echo $ff_image2; ?>" border="0" /><br />
      <?php echo $ff_image2; ?><br />
      <input type="checkbox" name="del_image_2" value="1" /> Delete this image
<?php
}
?>
    </td>
    <td class="fg5" colspan="2">
      <input type="file" name="upload_image_3" /><br />
<?php
if ($ff_image3 && file_exists($FACTS_PATH . '/' . $ff_image3)) {
?>
      <img src="<?php echo $FACTS_PATH; ?>/<?php echo $ff_image3; ?>" border="0" /><br />
      <?php echo $ff_image3; ?><br />
      <input type="checkbox" name="del_image_3" value="1" /> Delete this image
<?php
}
?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Phone #</td>
    <td class="fg2">Address</td>
    <td class="fg2">Membership</td>
    <td class="fg2">Average Attendance</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type="text" name="phone" value="<?php echo $phone; ?>" size="20" onblur="formatPhone(this)" /></td>
    <td class="fg5"><textarea name="address" rows="3" cols="30" wrap="physical"><?php echo $address; ?></textarea></td>
    <td class="fg5"><input type="text" name="membership" value="<?php echo $membership; ?>" size="5" /></td>
    <td class="fg5"><input type="text" name="avgattendance" value="<?php echo $avgattendance; ?>" size="5" /></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="4">
      <input type="submit" value="Update">
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
