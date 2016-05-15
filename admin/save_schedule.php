<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$date = $_REQUEST["date"];
$description = $_REQUEST["description"];
$upload_image = $_FILES["upload_image"];
$image_name = $upload_image["name"];
$image_tmpname = $upload_image["tmp_name"];
$del_image = $_REQUEST["del_image"];
$SCHEDULE_PATH =& $_REQUEST["SCHEDULE_PATH"];

if ($upload_image) {
  move_uploaded_file($image_tmpname, "$SCHEDULE_PATH/$image_name");
}

if ($arg == "2") {
// Deal with image upload.
  $sql = "SELECT sw_image FROM site_schedule_weeks " .
         "WHERE sw_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->sw_image && $row->sw_image != $image_name && $image_name) {
      if (is_file("$SCHEDULE_PATH/$row->sw_image")) {
        unlink("$SCHEDULE_PATH/$row->sw_image");
      }
    }
  }
  if ($del_image) {
    if (is_file("$SCHEDULE_PATH/$row->sw_image")) {
      unlink("$SCHEDULE_PATH/$row->sw_image");
      $sql = "UPDATE site_schedule_weeks " .
             "SET sw_image='' " .
             "WHERE sw_id='$id'";
      mysql_query($sql, $connect);
    }
  }

// Save week's description and image.
  $sql = "UPDATE site_schedule_weeks " .
         "SET sw_description='$description' ";
    if ($image_name) {
      $sql .=
           ", sw_image='$image_name' ";
    }
  $sql .=
         "WHERE sw_id='$id'";
  mysql_query($sql, $connect);

// Save information.
  foreach($_POST as $k => $v) {
    if (preg_match("/^info_(\d+)$/", $k, $matches)) {
      $sl_id = $matches[1];
      $info = $v;
      $sql = "SELECT * FROM site_schedule_information " .
             "WHERE si_slid='$sl_id' " .
             "  AND si_date='$date' ";
      $result = mysql_query($sql, $connect);
      if ($row = mysql_fetch_object($result)) {
        $sql2 = "UPDATE site_schedule_information " .
                "SET si_info='$info' " .
                "WHERE si_id='$row->si_id'";
        mysql_query($sql2, $connect);
      }
      elseif (strlen($info) > 0) {
        $sql2 = "INSERT INTO site_schedule_information " .
                "SET si_date='$date', " .
                "    si_slid='$sl_id', " .
                "    si_info='$info' ";
        mysql_query($sql2, $connect);
      }
    }
  }
}
header("Location: schedule.php");
?>
