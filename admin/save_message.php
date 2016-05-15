<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$upload_audio = $_FILES["upload_audio"];
$audio_name = $upload_audio["name"];
$audio_tmpname = $upload_audio["tmp_name"];
$del_audio = $_REQUEST["del_audio"];
$MESSAGE_PATH =& $_REQUEST["MESSAGE_PATH"];

if ($upload_audio) {
  move_uploaded_file($audio_tmpname, "$MESSAGE_PATH/$audio_name");
}

if ($arg == "2") {
// Deal with audio upload.
  $sql = "SELECT sw_audio FROM site_schedule_weeks " .
         "WHERE sw_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->sw_audio && $row->sw_audio != $audio_name && $audio_name) {
      if (is_file("$MESSAGE_PATH/$row->sw_audio")) {
        unlink("$MESSAGE_PATH/$row->sw_audio");
      }
    }
  }
  if ($del_audio) {
    if (is_file("$MESSAGE_PATH/$row->sw_audio")) {
      unlink("$MESSAGE_PATH/$row->sw_audio");
      $sql = "UPDATE site_schedule_weeks " .
             "SET sw_audio='' " .
             "WHERE sw_id='$id'";
      mysql_query($sql, $connect);
    }
  }

// Save week's audio file.
  $sql = "UPDATE site_schedule_weeks " .
         "SET sw_audio='$audio_name' " .
         "WHERE sw_id='$id'";
  mysql_query($sql, $connect);

}
header("Location: messages.php");
?>
