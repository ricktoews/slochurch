<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_faq " .
         "WHERE faq_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $question = $row->faq_question;
    $answer = $row->faq_answer;
    $seq = $row->faq_seq;
  }
  $sql = "SELECT MAX(faq_seq) AS follow FROM site_faq " .
         "WHERE faq_seq < '$seq'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
  <script language="JavaScript">
  function getFAQ() {
    var f = document.forms[0];
    f.arg.value = "1";
    f.action = "faq.php";
    f.submit();
  }

  function newFAQ() {
    location = "faq.php";
  }

  function updateFAQ() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }

  function deleteFAQ() {
    if (confirm("Are you sure you want to delete this?")) {
      var f = document.forms[0];
      f.arg.value = "4";
      f.submit();
    }
  }

  function initFocus() {
    var f = document.forms[0];
    f.question.focus();
  }

  onload=initFocus;
  </script>
<div align=center>
<form name="data" method="post" action="save_faq.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="4">FAQ</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">FAQ list</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="id" onchange="getFAQ()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_faq " .
       "ORDER BY faq_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->faq_id == $id ? "selected" : "";
  $listitem = substr($row->faq_question, 0, 50);
  echo "<option $selected value='$row->faq_id'>$listitem</option>\n";
}
?>
      </select>
      <input type="button" value="New" onclick="newFAQ()">
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Question</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <input type=text name="question" value="<?php echo $question; ?>" size="50" />
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Answer</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <textarea name="answer" rows="5" cols="80" wrap="physical"><?php echo $answer; ?></textarea>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Question this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_faq " .
       "ORDER BY faq_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->faq_id == $id) {
    continue;
  }
  $selected = $row->faq_seq == $follow ? "selected" : "";
  $listitem = substr($row->faq_question, 0, 50);
  echo "<option $selected value='$row->faq_id'>$listitem</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5">
<?php if (!$id) { ?>
      <input type=button value="Add" onclick="updateFAQ()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateFAQ()">
      <input type=button value="Delete" onclick="deleteFAQ()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
