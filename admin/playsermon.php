<?php
include('path.inc');
include("$PATH/includes/lib.inc");

$id =& $_REQUEST['id'];

$sql = "SELECT * FROM site_schedule_weeks " .
       "WHERE sw_id='$id'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $audio = $row->sw_audio;
  header("Location: $MESSAGE_PATH/$audio");
}
?>
