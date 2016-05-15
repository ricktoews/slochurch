<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$heading = $_REQUEST["heading"];
$text = $_REQUEST["text"];
$followid = $_REQUEST["followid"];
$upload_image = $_FILES["upload_image"];
$image_name = $upload_image["name"];
$image_tmpname = $upload_image["tmp_name"];
$del_image = $_REQUEST["del_image"];
$HOME_PATH =& $_REQUEST["HOME_PATH"];

if ($upload_image) {
  move_uploaded_file($image_tmpname, "$HOME_PATH/$image_name");
}

// Add
if ($arg == "2") {
// Deal with image upload.
  $sql = "SELECT hp_image FROM site_homepage " .
         "WHERE hp_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->hp_image && $row->hp_image != $image_name && $image_name) {
      if (is_file("$HOME_PATH/$row->hp_image")) {
        unlink("$HOME_PATH/$row->hp_image");
      }
    }
    if ($del_image) {
      if ($row->hp_image && is_file("$HOME_PATH/$row->hp_image")) {
        unlink("$HOME_PATH/$row->hp_image");
      }
      $sql = "UPDATE site_homepage " .
             "SET hp_image='' " .
             "WHERE hp_id='$id'";
      mysql_query($sql, $connect);
    }
  }

  if ($id) {
    $sql = "UPDATE site_homepage " .
           "SET hp_heading='$heading', ";
    if ($image_name) {
      $sql .=
           "    hp_image='$image_name', ";
    }
    $sql .=
           "    hp_text='$text' " .
           "WHERE hp_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_homepage " .
           "SET hp_heading='$heading', ";
    if ($image_name) {
      $sql .=
           "    hp_image='$image_name', ";
    }
    $sql .=
           "    hp_text='$text'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_homepage " .
         "WHERE hp_id <> '$id' " .
         "ORDER BY hp_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->hp_id;
    if ($followid == $row->hp_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_homepage " .
           "SET hp_seq='$s' " .
           "WHERE hp_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete 
elseif ($arg == "4") {
  $sql = "SELECT hp_image FROM site_homepage " .
         "WHERE hp_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->hp_image) {
      if (is_file("$HOME_PATH/$row->hp_image")) {
        unlink("$HOME_PATH/$row->hp_image");
      }
    }
  }
 
  $sql = "DELETE FROM site_homepage " .
         "WHERE hp_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: homepage.php");
?>
