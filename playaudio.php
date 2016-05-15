<?php
include('path.inc');
include("$PATH/includes/lib.inc");

$id =& $_REQUEST['id'];
$type =& $_REQUEST['type'];

if ($type == 'P') {
  $sql = "SELECT * FROM site_podcasts " .
         "WHERE pc_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $podcast = $row->pc_podcast;
    $audio = "$PODCAST_PATH/$podcast";
  }
}
else {
  $dir = $type == 'P' ? $PODCAST_PATH : $MESSAGE_PATH;
  $sql = "SELECT * FROM site_schedule_weeks " .
         "WHERE sw_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $audio = $MESSAGE_PATH . '/' . $row->sw_audio;
  }
}
header("Location: $audio");
?>
