<?php
include("path.inc");
include("includes/header.inc");

$SCHEDULE_PATH =& $_REQUEST["SCHEDULE_PATH"];

$arg = "";
$id = $_REQUEST["id"];
$sql = "SELECT * FROM site_bibletrivia_weeks " .
       "WHERE bt_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $date = $row->bt_date;
  $fmtdate = date("F j", strtotime($date));
  $bt_image1 = $row->bt_image1;
  $bt_image2 = $row->bt_image2;
  $bt_image3 = $row->bt_image3;
}
?>

<script language="JavaScript">
function updateTrivia() {
  var f = document.forms["data"];
  f.submit();
}

function initFocus() {
//  document.forms["data"].description.focus();
}

onload=initFocus;
</script>
<form name="data" method="post" action="save_trivia.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="2" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<div align="center">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Questions for <?php echo $fmtdate; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Images</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="file" name="upload_image_1" /><br />
<?php
if ($bt_image1 && file_exists($MARKET_PATH . '/' . $bt_image1)) {
?>
      <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image1; ?>" border="0" /><br />
      <?php echo $bt_image1; ?><br />
      <input type="checkbox" name="del_image_1" value="1" /> Delete this image
<?php
}
?>
    </td>
    <td class="fg5">
      <input type="file" name="upload_image_2" /><br />
<?php
if ($bt_image2 && file_exists($MARKET_PATH . '/' . $bt_image2)) {
?>
      <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image2; ?>" border="0" /><br />
      <?php echo $bt_image2; ?><br />
      <input type="checkbox" name="del_image_2" value="1" /> Delete this image
<?php
}
?>
    </td>
    <td class="fg5">
      <input type="file" name="upload_image_3" /><br />
<?php
if ($bt_image3 && file_exists($MARKET_PATH . '/' . $bt_image3)) {
?>
      <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image3; ?>" border="0" /><br />
      <?php echo $bt_image3; ?><br />
      <input type="checkbox" name="del_image_3" value="1" /> Delete this image
<?php
}
?>
    </td>
  </tr>
<?php
$sql = "SELECT * FROM site_bibletrivia_categories " .
       "LEFT JOIN site_bibletrivia_questions ON bq_bcid=bc_id AND bq_date='$date' " .
       "ORDER BY bc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $bc_id = $row->bc_id;
  $bc_category = $row->bc_category;
  $bq_question = $row->bq_question;
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3"><?php echo $bc_category; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <input type="text" name="question_<?php echo $bc_id; ?>" value="<?php echo $bq_question; ?>" size="50" />
    </td>
  </tr>

<?php
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3" class="fg5">
      <input type="button" value="Update" onclick="updateTrivia()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="3" class="fg5">
      <p>Return to <a href="trivia.php">Trivia Questions</a>.</p>
    </td>
  </tr>
</table>
</div>
</form>
<?php
include("includes/footer.inc");
?>

