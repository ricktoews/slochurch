<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST['arg'];
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];
$date = date("Y-m-d");
$podcast = $_REQUEST['podcast'];
$upload_podcast = $_FILES["upload_podcast"];
$upload_podcast_tmp_name = $upload_podcast["tmp_name"];
$upload_podcast_name = $upload_podcast["name"];
$followid = $_REQUEST['followid']; 

if ($upload_podcast_tmp_name) {
  if ($podcast != $upload_podcast_name) {
    if (file_exists("$PODCAST_PATH/$podcast")) {
      unlink("$PODCAST_PATH/$podcast");
    }
  }
  $podcast_file = "$PODCAST_PATH/$upload_podcast_name";
  move_uploaded_file($upload_podcast_tmp_name, $podcast_file);
  $podcast = $upload_podcast_name;
}
if ($arg == '2') {
  if (!id) {
    $sql = "INSERT INTO site_podcasts " .
           "SET pc_podcast='$podcast', " .
           "    pc_title='$title', " .
           "    pc_date='$date' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  else {
    $sql = "UPDATE site_podcasts " .
           "SET pc_podcast='$podcast', " .
           "    pc_title='$title' " .
           "WHERE pc_id='$id'";
    mysql_query($sql, $connect);
  }
  
// Get the sequence in which the podcasts are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_podcasts " .
         "WHERE pc_id <> '$id' " .
         "ORDER BY pc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->pc_id;
    if ($followid == $row->pc_id) {
      $idlist[$s++] = $id;
    }
// Update the sequence numbers.
    for ($i = 0; $i < $s; $i++) {
      $sql = "UPDATE site_podcasts " .
             "SET pc_seq='$i' " .
             "WHERE pc_id='$idlist[$i]'";
      mysql_query($sql, $connect);
    }
  }
}
elseif ($arg == '4') {
  $sql = "SELECT pc_podcast FROM site_podcasts " .
         "WHERE pc_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->pc_podcast && file_exists("$PODCAST_PATH/$row->pc_podcast")) {
      unlink("$PODCAST_PATH/$row->pc_podcast");
    }
  }
  $sql = "DELETE FROM site_podcasts " .
         "WHERE pc_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: podcast_upload.php");
?>
