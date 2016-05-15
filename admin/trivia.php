<?php
include("path.inc");
include("includes/header.inc");

$startdate = date("Y-m-d", mktime() + 86400 * (4 - date("w")));
$weeksahead = 8;
?>
<script language="JavaScript">
function editTrivia(id) {
  if (id) {
    location = "trivia_edit.php?id=" + id;
  }
}
</script>
<div align=center>
<form method=post action="save_trivia.php">
<input type=hidden name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Trivia Questions</th>
  </tr>
<?php
$r = 0;
$date = $startdate;
list($y,$m,$d) = explode("-", $date);
for ($i = 0; $i < $weeksahead; $i++) {
  $date = date("Y-m-d", mktime(0,0,0,$m,$d,$y)); 
  $sql = "SELECT * FROM site_bibletrivia_weeks " .
         "WHERE bt_date='$date'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $id = $row->bt_id;
    $bt_image1 = $row->bt_image1;
    $bt_image2 = $row->bt_image2;
    $bt_image3 = $row->bt_image3;
  }
  else {
    $sql = "INSERT INTO site_bibletrivia_weeks " .
           "SET bt_date='$date'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  $fmtdate = date("F j", strtotime($date));
  $sql = "SELECT * FROM site_bibletrivia_questions, site_bibletrivia_categories " .
         "WHERE bq_bcid=bc_id " .
         "  AND bq_date='$date' " .
         "ORDER BY bc_seq";
  $result = mysql_query($sql, $connect);
  $infoArray = array();
  while ($row = mysql_fetch_object($result)) {
    $category = $row->bc_category;
    $question = $row->bq_question ? $row->bq_question : "<i>TBD</i>";
    $infoArray[] = "<b>$category</b>: $question";
  }
  $info = sizeof($infoArray) > 0 ? $info = implode("<br />", $infoArray) : "No questions chosen";
?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">
      <input type="hidden" name="id[]" value="<?php echo $id; ?>">
      <b><?php echo $fmtdate; ?></b>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <table border="0" cellpadding="0" cellspacing="2">
        <tr valign="top">
<?php
if ($bt_image1 && file_exists($MARKET_PATH . '/' . $bt_image1)) {
?>
          <td class="fg5">
            <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image1; ?>" border="0" /><br />
            <?php echo $bt_image1; ?><br />
          </td>
<?php
}
if ($bt_image2 && file_exists($MARKET_PATH . '/' . $bt_image2)) {
?>
          <td class="fg5">
            <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image2; ?>" border="0" /><br />
            <?php echo $bt_image2; ?><br />
          </td>
<?php
}
if ($bt_image3 && file_exists($MARKET_PATH . '/' . $bt_image3)) {
?>
          <td class="fg5">
            <img src="<?php echo $MARKET_PATH; ?>/<?php echo $bt_image3; ?>" border="0" /><br />
            <?php echo $bt_image3; ?><br />
          </td>
<?php
}
?>
        </tr>
      </table>
    </td>
    <td class="fg5" width="99%">
      <?php echo $info; ?>
    </td>
    <td class="fg5">
      <input type="button" value="Edit" onclick="editTrivia('<?php echo $id; ?>')" />
    </td>
  </tr>
<?php
  $r++;
  $d += 7;
}
?>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
