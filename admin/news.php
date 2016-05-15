<?php
include("path.inc");
include("includes/header.inc");


function formatDate($ymd) {
  $yr = substr($ymd, 0, 4);
  $mo = substr($ymd,5,2);
  $da = substr($ymd,8,2);
  $fmt = date("F j, Y", mktime(0, 0, 0, $mo, $da, $yr));
  return ($fmt);
}


function getContributor($id) {
  global $connect;

  $sql = "SELECT * FROM site_admin " .
         "WHERE a_email='$e'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $contributor = "$row->a_first $row->a_last";
  }
  elseif (0) {
    $sql = "SELECT * FROM site_members " .
           "WHERE m_id='$id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $contributor = "$row->m_first $row->m_last";
    }
  }
  if ($contributor > "") {
   return $contributor;
  }
  else {
    return $e;
  }
}
?>
  <script language="JavaScript">
  function addNews() {
    location = "news_edit.php";
  }

  function deleteNews() {
    var f = document.forms[0];
    f.arg.value = "2";
    f.submit();
  }
  </script>
<div align=center>
<form method=post action="save_news.php">
<input type=hidden name="arg" value="1" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="2">News</th>
  </tr>
<?php
$r = 0;
$sql = "SELECT * FROM site_news " .
       "ORDER BY news_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  list($y, $m, $d) = explode("-", $row->news_expdate);
  $id = $row->news_id;
  $tagline = $row->news_tagline;
  $description = $row->news_description;
  $userid = $row->news_userid;
  list($expy, $expm, $expd) = explode("-", $row->news_expdate);
  $expdate = date("F j, Y", mktime(0,0,0,$expm, $expd, $expy));
  $postdate = formatDate($row->news_postdate);
  $sequence = $row->news_seq;
  $contributor = getContributor($userid); 
?>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5">
      <input type="hidden" name="id[]" value="<?php echo $id; ?>">
      <div style="text-indent: -20px; padding-left: 20px;">
      <b><?php echo $tagline; ?></b> - <?php echo $description; ?>
      </div>
      <i>Expires: <?php echo $expdate; ?></i>
    </td>
    <td class="fg5">
      <input type="checkbox" value="1" name="delete<?php echo $r; ?>"> Del<br>
      <a href="news_edit.php?id=<?php echo $id; ?>">Edit</a><br>
    </td>
  </tr>
  <tr>
    <td colspan="2"><img src="images/spacer.gif" width=1 height=2></td>
  </tr>
<?php
  $r++;
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="2" align="center">
      <input type=button value="Add" onclick="addNews()">
      <input type=button value="Delete Marked" onclick="deleteNews()">
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
