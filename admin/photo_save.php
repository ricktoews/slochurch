<?php
include("path.inc");
include("includes/lib_admin.inc");

if (!is_dir("$GALLERY_PATH")) {
  mkdir("$GALLERY_PATH", 0755);
}

$img = $_REQUEST["img"];
$img_file = "pics/$img";
// Create JPG image, get width and height.
$im = ImageCreateFromJPEG($img_file);
$original_width = ImageSX($im);
$original_height = ImageSY($im);

// Create default thumbnail image.
$thumb_height = $THUMB_WIDTH / $original_width * $original_height;
$thumb_im = ImageCreate($THUMB_WIDTH, $thumb_height);
$thumb_image = ImageCopyResized($thumb_im, $im, 0, 0, 0, 0, $THUMB_WIDTH, $thumb_height, $original_width, $original_height);
ImageJPEG($thumb_im, "$GALLERY_PATH/thumb_$img", 60);
//copy("thumb_$img", "$GALLERY_PATH/thumb_$img");
//unlink("thumb_$img");


// Create resized image.
if ($original_width != $new_width) {
  $new_im = ImageCreate($new_width, $new_height);
  $new_image = ImageCopyResized($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
  ImageJPEG($new_im, "$img_file", 60);
}

// Copy image to photo album directory.
copy("$img_file", "$GALLERY_PATH/$img");
unlink("$img_file");

//rename("$img", "$GALLERY_PATH/$img");
$sql = "UPDATE site_photos " .
       "SET p_width='$new_width', " .
       "    p_height='$new_height', " .
       "    p_caption='$caption' " .
       "WHERE p_photo='$img'";
mysql_query($sql, $connect);

header("Location: photo_manager.php");
?>
