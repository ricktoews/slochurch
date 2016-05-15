<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST['arg'];
$id = $_REQUEST['id'];

if ($arg == '1') {
  $sql = "SELECT * FROM site_podcasts " .
         "WHERE pc_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $podcast = $row->pc_podcast;
    $title = $row->pc_title;
    $date = $row->pc_date;
    $seq = $row->pc_seq;
  }
}
?>
<script language="JavaScript">
function getPodcast() {
  var f = document.forms["data"];
  if (f.id.selectedIndex == 0) {
    return; 
  }
  f.arg.value = "1";
  f.action = "podcast_upload.php";
  f.submit();
}


function updatePodcast() {
  var f = document.forms["data"];
  f.arg.value = "2";
  f.submit();
}


function deletePodcast() {
  var f = document.forms["data"];
  f.arg.value = "4";
  f.submit();
}
</script>
</head>
<div align="center">
<form name="data" method="post" action="save_podcast_upload.php" enctype="multipart/form-data">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Podcast Upload</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Select Podcast</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="id" onchange="getPodcast()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_podcasts " .
       "ORDER BY pc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $row->pc_id == $id ? 'selected' : '';
  $listitem = $row->pc_title ? $row->pc_title : $row->pc_podcast;
  echo "<option $selected value='$row->pc_id'>$listitem</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Title</td>
    <td class="fg2">Specify podcast to upload</td>
    <td class="fg2">Podcast this one should follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>" valign="top">
    <td class="fg5"><input type="text" name="title" value="<?php echo $title; ?>" size="30" /></td>
    <td class="fg5">
      <input type="hidden" name="podcast" value="<?php echo $podcast; ?>" /><input type="file" name="upload_podcast" />
<?php
if ($podcast) {
  echo "<br />Podcast: $podcast";
}
?>
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_podcasts " .
       "ORDER BY pc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->pc_id == $id) {
    continue;
  }
  $selected = $row->pc_seq == $follow ? "selected" : "";
  $label = $row->pc_title ? $row->pc_title : $row->pc_podcast;
  echo "<option $selected value='$row->pc_id'>$label</option>\n";
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php
if (!$id) {
?>
      <input type="button" value="Add" onclick="updatePodcast()" />
<?php
}
else {
?>
      <input type="button" value="Update" onclick="updatePodcast()" />
      <input type="button" value="Delete" onclick="deletePodcast()" />
<?php
}
?>
    </td>
  </tr>
</table>
</form>
</div>
<?php
include("includes/footer.inc");
?>
