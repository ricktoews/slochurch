<?php
include("path.inc");
include("includes/lib_admin.inc");

$id = $_REQUEST["id"];
$arg = $_REQUEST['arg'];
$first = $_REQUEST["first"];
$last = $_REQUEST["last"];
$email = $_REQUEST["email"];
$emailformat = $_REQUEST["emailformat"];
$about = $_REQUEST["about"];
$upload_image = $_FILES["upload_image"];
$image_name = $upload_image["name"];
$image_tmpname = $upload_image["tmp_name"];
$del_image = $_REQUEST["del_image"];
$IMAGE_PATH =& $_REQUEST["IMAGE_PATH"];
$connect =& $_REQUEST["connect"];

if ($upload_image) {
  move_uploaded_file($image_tmpname, "$IMAGE_PATH/$image_name");
}

if ($arg == "2") {
  if ($id) {
// Deal with image upload.
    $sql = "SELECT m_image FROM site_members " .
           "WHERE m_id='$id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      if ($row->m_image && $row->m_image != $image_name && $image_name) {
        if (is_file($IMAGE_PATH . "/" . $row->m_image)) {
          unlink($IMAGE_PATH . "/" . $row->m_image);
        }
      }
    }
    if ($del_image) {
      if (is_file($IMAGE_PATH . "/" . $row->m_image)) {
        unlink($IMAGE_PATH . "/" . $row->m_image);
        $sql = "UPDATE site_members " .
               "SET m_image='' " .
               "WHERE m_id='$id'";
        mysql_query($sql, $connect);
      }
    }

// Update information.
    $sql = "UPDATE site_members " .
           "SET m_first='$first', " .
           "    m_last='$last', ";
    if ($image_name) {
      $sql .=
           "    m_image='$image_name', ";
    }
    $sql .= 
           "    m_email='$email', " .
           "    m_emailformat='$emailformat', " .
           "    m_about='$about' " .
           "WHERE m_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
// Add information.
    $sql = "INSERT INTO site_members " .
           "SET m_first='$first', " .
           "    m_last='$last', " .
           "    m_email='$email', " .
           "    m_emailformat='$emailformat', " .
           "    m_about='$about', " .
           "    m_image='$image_name' ";
    mysql_query($sql, $connect);
  }
}
// Delete member  information.
elseif ($arg == "4") {
  $sql = "SELECT m_image FROM site_members " .
         "WHERE m_id='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->m_image != '') {
      if (is_file($IMAGE_PATH . "/" . $row->m_image)) {
        unlink($IMAGE_PATH . "/" . $row->m_image);
      }
    }
  }
  $sql = "DELETE FROM site_members " .
         "WHERE m_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: members.php");
?>
