<?php
include("path.inc");
include("includes/header.inc");

$arg = "";
$id = $_REQUEST["id"];
$sql = "SELECT * FROM site_news " .
       "WHERE news_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $arg = 1;
  list($y, $m, $d) = explode("-", $row->news_expdate);
  $id = $row->news_id;
  $tagline = $row->news_tagline;
  $description = $row->news_description;
  $userid = $row->news_userid;
  $expdate = $row->news_expdate;
  $seq = $row->news_seq;
}
$sql = "SELECT MAX(news_seq) AS follow FROM site_news " .
       "WHERE news_seq < '$seq'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $follow = $row->follow;
}
?>

<script language="JavaScript">
function updateNews() {
  var f = document.forms[0];
  f.submit();
}

function deleteNews() {
  var f = document.forms[0];
  f.arg.value = "4";
  f.submit();
}

monthdays = "312831303130313130313031";

function updateDates(m) {
  var f = document.forms[0];
  var days = 1*monthdays.substr((m-1) * 2, 2) + 1;
  if (f.elements["day"].options.length > days) {
    f.elements["day"].options.length = days;
    f.elements["day"].options[days] = null;
  }
  else if (f.elements["day"].options.length < days) {
    var n = f.elements["day"].options.length;
    while (n < days) {
      f.elements["day"].options[n] = new Option(n, n);
      n++;
    }
  }
}
</script>
<form method=post action="save_news.php">
<input type=hidden name="arg" value="1">
<input type=hidden name="id" value="<?php echo $id; ?>">
<div align=center>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">News Administration</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Tagline</td>
    <td class="fg2">News item this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type=text name="tagline" value="<?php echo preg_replace('/"/', "&quot;", $tagline); ?>" size=35></td>
    <td class="fg5">
      <select name="followid" class="cell">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_news " .
       "ORDER BY news_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->news_id == $id) {
    continue;
  }
  $selected = $row->news_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->news_id'>$row->news_tagline</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Description</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2"><textarea name="description" rows="8" cols="70" wrap="physical"><?php echo $description; ?></textarea></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="2">Expiration date</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2">
      <select name="month" onchange="updateDates(this.selectedIndex)">
      <option value=""></option>
<?php
  for ($i = 1; $i <= 12; $i++) {
    $selected = $m == $i ? "selected" : "";
    echo "<option $selected value='" . sprintf("%02d", $i) . "'>" . date("F", mktime(0, 0, 0, $i, 1, $y)) . "</option>\n";
  }
?>
      </select>

      <select name="day">
      <option value=""></option>
<?php
  $days = date("t", mktime(0, 0, 0, $m, 1, $y));
  for ($i = 1; $i <= $days; $i++) {
    $selected = $d == $i ? "selected" : "";
    echo "<option $selected value='" . sprintf("%02d", $i) . "'>$i</option>\n";
  }
?>
      </select>

      <select name="year">
<?php
  $thisyear = date("Y");
  for ($i = 0; $i < 3; $i++) {
    $selected = $y == $thisyear + $i ? "selected" : "";
    echo "<option $selected value='" . ($thisyear + $i) . "'>" . ($thisyear + $i) . "</option>\n";
  }
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateNews()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateNews()">
      <input type=button value="Delete" onclick="deleteNews()">
<?php } ?>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" colspan="2" class="fg5">
      <p>Return to <a href="news.php">News</a>.</p>
    </td>
  </tr>
</table>
</div>
</form>
<?php
include("includes/footer.inc");
?>

