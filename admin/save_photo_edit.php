<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST['arg'];
$id = $_REQUEST['id'];
$orig_galleryid = $_REQUEST['orig_galleryid'];
$galleryid = $_REQUEST['galleryid'];
$photo = $_REQUEST['photo'];
$followid = $_REQUEST['followid'];

// If this is an insert or update, process that first.
if ($arg == "2") {
  if ($orig_galleryid != $galleryid) {
    $renamefrom = "$GALLERY_PATH/$orig_galleryid/$photo";
    $renameto = "$GALLERY_PATH/$galleryid/$photo";
    if (!is_dir("$GALLERY_PATH/$galleryid")) {
      mkdir("$GALLERY_PATH/$galleryid", 0755);
    }
    rename($renamefrom, $renameto);
  }
  $sql = "UPDATE site_photos " .
         "SET p_caption='$caption', " .
         "    p_galleryid='$galleryid' " .
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
elseif ($arg == "2") {
  while (list($n, $i) = each($id)) {
    $this_delete = '$delete' . $n;
    eval("\$del = \"$this_delete\";");
    if ($del) {
      $sql = "DELETE FROM site_photos " .
             "WHERE p_id='$i' ";
      mysql_query($sql, $connect);
    }
  }
}
elseif ($arg == "4") {
  $sql = "DELETE FROM site_photos " .
         "WHERE p_id='$id' ";
  mysql_query($sql, $connect);
}
header("Location: photo_manager.php?galleryid=$galleryid");
?>
