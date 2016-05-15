<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$date = $_REQUEST["date"];
$upload_image = array();
$image_name = array();
$image_tmpname = array();
$del_image = array();
for ($i = 1; $i <= 3; $i++) {
  $upload_image[$i-1] = $_FILES["upload_image_$i"];
  $image_name[$i-1] = $upload_image[$i-1]["name"];
  $image_tmpname[$i-1] = $upload_image[$i-1]["tmp_name"];
  $del_image[$i-1] = $_REQUEST["del_image_$i"];
}
$MARKET_PATH =& $_REQUEST["MARKET_PATH"];

for ($i = 0; $i < 3; $i++) {
  if ($upload_image[$i]) {
    move_uploaded_file($image_tmpname[$i], "$MARKET_PATH/$image_name[$i]");
  }
}

if ($arg == "2") {
// Deal with image upload.
  for ($i = 0; $i < 3; $i++) {
    $ndx = $i + 1;
    $sql = "SELECT bt_image$ndx AS bt_image FROM site_bibletrivia_weeks " .
           "WHERE bt_id='$id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      if ($row->bt_image && $row->bt_image != $image_name[$i] && $image_name[$i]) {
        if (is_file("$MARKET_PATH/$row->bt_image")) {
          unlink("$MARKET_PATH/$row->bt_image");
        }
      }
    }
    if ($del_image[$i]) {
      if (is_file("$MARKET_PATH/$row->bt_image")) {
        unlink("$MARKET_PATH/$row->bt_image");
        $sql = "UPDATE site_bibletrivia_weeks " .
               "SET bt_image$ndx='' " .
               "WHERE bt_id='$id'";
        mysql_query($sql, $connect);
      }
    }

// Save week's image.
    if ($image_name[$i]) {
      $sql = "UPDATE site_bibletrivia_weeks " .
             "SET bt_image$ndx='$image_name[$i]' " .
             "WHERE bt_id='$id'";
      mysql_query($sql, $connect);
    }
  }

// Save information.
  foreach($_POST as $k => $v) {
    if (preg_match("/^question_(\d+)$/", $k, $matches)) {
      $bc_id = $matches[1];
      $question = $v;
      $sql = "SELECT * FROM site_bibletrivia_questions " .
             "WHERE bq_bcid='$bc_id' " .
             "  AND bq_date='$date' ";
      $result = mysql_query($sql, $connect);
      if ($row = mysql_fetch_object($result)) {
        $sql2 = "UPDATE site_bibletrivia_questions " .
                "SET bq_question='$question' " .
                "WHERE bq_id='$row->bq_id'";
        mysql_query($sql2, $connect);
      }
      elseif (strlen($question) > 0) {
        $sql2 = "INSERT INTO site_bibletrivia_questions " .
                "SET bq_date='$date', " .
                "    bq_bcid='$bc_id', " .
                "    bq_question='$question' ";
        mysql_query($sql2, $connect);
      }
    }
  }
}
header("Location: trivia.php");
?>
