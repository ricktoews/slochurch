<?php
include("path.inc");
include("includes/lib_admin.inc");

$photo = $_FILES["photo"];
$photo_tmp_name = $photo["tmp_name"];
$photo_name = $photo["name"];
$caption = $_REQUEST["caption"];
$galleryid = $_REQUEST["galleryid"];
$GALLERY_PATH =& $_REQUEST["GALLERY_PATH"];

$sql = "INSERT INTO site_photos " .
       "SET p_photo='$photo_name', " .
       "    p_caption='$caption', " .
       "    p_galleryid='$galleryid'";
mysql_query($sql, $connect);
$id = mysql_insert_id($connect);
if (!is_dir("$GALLERY_PATH/$galleryid")) {
  mkdir("$GALLERY_PATH/$galleryid", 0755);
}
$photo_file = "$GALLERY_PATH/$galleryid/$photo_name";

if (move_uploaded_file($photo_tmp_name, $photo_file)) {
// Create JPG image, get width and height.
  if (preg_match("/\.jpg/", $photo_name)) {
    $im = imagecreatefromjpeg($photo_file);
  }
  else {
    $im = imagecreatefromgif($photo_file);
  }
  $original_width = imagesx($im);
  $original_height = imagesy($im);
  $sql = "UPDATE site_photos " .
         "SET p_width='$original_width', " .
         "    p_height='$original_height' " .
         "WHERE p_id='$id'";
  mysql_query($sql, $connect);
// Get the sequence in which the photos are to be displayed.
  $s = 0;
  $idlist = array();
  if ($followid == 0) {
    $idlist[$s++] = $id;
  }
  $sql = "SELECT * FROM site_photos " .
         "WHERE p_id <> '$id' " .
         "  AND p_galleryid='$galleryid' " .
         "ORDER BY p_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[$s++] = $row->p_id;
    if ($followid == $row->p_id) {
      $idlist[$s++] = $id;
    }
  }
// Update the sequence numbers.
  for ($i = 0; $i < $s; $i++) {
    $sql = "UPDATE site_photos " .
           "SET p_seq='$i' " .
           "WHERE p_id='$idlist[$i]'";
    mysql_query($sql, $connect);
  }
}
else {
  echo "There was a problem with upload.";
  $sql = "DELETE FROM site_photos " .
         "WHERE p_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: photo_manager.php?galleryid=$galleryid");
?>
