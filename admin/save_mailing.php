<?php
include("path.inc");
include($PATH . "/includes/lib.inc");

$date = date("Y-m-d");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$subject = $_REQUEST["subject"];
$mailingcontact = $_REQUEST["mailingcontact"];
$mailingfromemail = $_REQUEST["mailingfromemail"];
$mailingfromname = $_REQUEST["mailingfromname"];
$mailingfooter = $_REQUEST["mailingfooter"];
$msidArr = $_REQUEST['msidArr'];
$msnameArr = $_REQUEST['msnameArr'];
$msalignArr = $_REQUEST['msalignArr'];
$current_msimageArr = $_REQUEST['current_msimageArr'];
$upload_msimageArr = $_FILES['upload_msimageArr'];
$del_msimageArr = $_REQUEST['del_msimageArr'];
$current_msimage2Arr = $_REQUEST['current_msimage2Arr'];
$upload_msimage2Arr = $_FILES['upload_msimage2Arr'];
$del_msimage2Arr = $_REQUEST['del_msimage2Arr'];
$current_msimage3Arr = $_REQUEST['current_msimage3Arr'];
$upload_msimage3Arr = $_FILES['upload_msimage3Arr'];
$del_msimage3Arr = $_REQUEST['del_msimage3Arr'];
$mscontentArr = $_REQUEST['mscontentArr'];

// Save mailing.
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_mailings " .
           "SET mail_subject='$subject', " .
           "    mail_contact='$contact' " .
           "WHERE mail_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_mailings " .
           "SET mail_date='$date', " .
           "    mail_subject='$subject', " .
           "    mail_contact='$contact' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
  $sql = "UPDATE site_admin_misc " .
         "SET am_mailingfooter='$mailingfooter', " .
         "    am_mailingcontact='$mailingcontact', " .
         "    am_mailingfromemail='$mailingfromemail', " .
         "    am_mailingfromname='$mailingfromname' ";
  mysql_query($sql, $connect);

  if (sizeof($msidArr) > 0) {
    for ($i = 0; $i < sizeof($msidArr); $i++) {
      $msid = $msidArr[$i];
      $msname = $msnameArr[$i];
      $msalign = $msalignArr[$i];
      $mscontent = $mscontentArr[$i];
// First image
      $msimage_name = $upload_msimageArr['name'][$i];
      $msimage_tmpname = $upload_msimageArr['tmp_name'][$i];
      $current_msimage = $current_msimageArr[$i];
      $del_msimage = $del_msimageArr[$i];
      if ($msimage_name) {
        move_uploaded_file($msimage_tmpname, "$MAILING_PATH/$msimage_name");
      }
      if ($del_msimage || $msimage_name != $current_msimage && $current_msimage > '' && $msimage_name > '') {
        if (file_exists("$MAILING_PATH/$current_msimage")) {
          unlink("$MAILING_PATH/$current_msimage");
        }
        $msimage_name = '';
      }
// Second image
      $msimage2_name = $upload_msimage2Arr['name'][$i];
      $msimage2_tmpname = $upload_msimage2Arr['tmp_name'][$i];
      $current_msimage2 = $current_msimage2Arr[$i];
      $del_msimage2 = $del_msimage2Arr[$i];
      if ($msimage2_name) {
        move_uploaded_file($msimage2_tmpname, "$MAILING_PATH/$msimage2_name");
      }
      if ($del_msimage2 || $msimage2_name != $current_msimage2 && $current_msimage2 > '' && $msimage2_name > '') {
        if (file_exists("$MAILING_PATH/$current_msimage2")) {
          unlink("$MAILING_PATH/$current_msimage2");
        }
        $msimage2_name = '';
      }
// Third image
      $msimage3_name = $upload_msimage3Arr['name'][$i];
      $msimage3_tmpname = $upload_msimage3Arr['tmp_name'][$i];
      $current_msimage3 = $current_msimage3Arr[$i];
      $del_msimage3 = $del_msimage3Arr[$i];
      if ($msimage3_name) {
        move_uploaded_file($msimage3_tmpname, "$MAILING_PATH/$msimage3_name");
      }
      if ($del_msimage3 || $msimage3_name != $current_msimage3 && $current_msimage3 > '' && $msimage3_name > '') {
        if (file_exists("$MAILING_PATH/$current_msimage3")) {
          unlink("$MAILING_PATH/$current_msimage3");
        }
        $msimage3_name = '';
      }

      if ($msid) {
        $sql = "UPDATE site_mailing_sections " .
               "SET ms_name='$msname', " .
               "    ms_align='$msalign', ";
        if ($msimage_name > '' && $msimage_name != $current_msimage) {
          $sql .= "ms_image='$msimage_name', ";
        }
        if ($msimage2_name > '' && $msimage2_name != $current_msimage2) {
          $sql .= "ms_image2='$msimage2_name', ";
        }
        if ($msimage3_name > '' && $msimage3_name != $current_msimage3) {
          $sql .= "ms_image3='$msimage3_name', ";
        }
        $sql .=
               "    ms_content='$mscontent' " .
               "WHERE ms_id='$msid'";
      }
      else if (strlen($msname) > 0) {
        $sql = "INSERT INTO site_mailing_sections " .
               "SET ms_mailid='$id', " .
               "    ms_name='$msname', " .
               "    ms_align='$msalign', ";
        if ($msimage_name > '' && $msimage_name != $current_msimage) {
          $sql .= "ms_image='$msimage_name', ";
        }
        if ($msimage2_name > '' && $msimage2_name != $current_msimage2) {
          $sql .= "ms_image2='$msimage2_name', ";
        }
        if ($msimage3_name > '' && $msimage3_name != $current_msimage3) {
          $sql .= "ms_image3='$msimage3_name', ";
        }
        $sql .=
               "    ms_content='$mscontent'";
      }
      mysql_query($sql, $connect);
    } 
  }
}

// Delete mailing.
elseif ($arg == "4") {
// Delete unused images
  $sql = "SELECT ms_image FROM site_mailing_sections " .
         "WHERE ms_mailid='$id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
// Check for first image
    if ($row->ms_image) {
      $sql2 = "SELECT ms_id FROM site_mailing_sections " .
              "WHERE ms_image='$row->ms_image' " .
              "  AND ms_mailid<>'$id'";
      $result2 = mysql_query($sql2, $connect);
      if (mysql_num_rows($result2) == 0) {       
        if (is_file("$MAILING_PATH/$row->ms_image")) {
          unlink("$MAILING_PATH/$row->ms_image");
        }
      }
    }
// Check for second image
    if ($row->ms_image2) {
      $sql2 = "SELECT ms_id FROM site_mailing_sections " .
              "WHERE ms_image2='$row->ms_image2' " .
              "  AND ms_mailid<>'$id'";
      $result2 = mysql_query($sql2, $connect);
      if (mysql_num_rows($result2) == 0) {       
        if (is_file("$MAILING_PATH/$row->ms_image2")) {
          unlink("$MAILING_PATH/$row->ms_image2");
        }
      }
    }
// Check for third image
    if ($row->ms_image3) {
      $sql2 = "SELECT ms_id FROM site_mailing_sections " .
              "WHERE ms_image3='$row->ms_image3' " .
              "  AND ms_mailid<>'$id'";
      $result2 = mysql_query($sql2, $connect);
      if (mysql_num_rows($result2) == 0) {       
        if (is_file("$MAILING_PATH/$row->ms_image3")) {
          unlink("$MAILING_PATH/$row->ms_image3");
        }
      }
    }

  }
  $sql = "DELETE FROM site_mailings " .
         "WHERE mail_id='$id'";
  mysql_query($sql, $connect);
  $sql = "DELETE FROM site_mailing_sections " .
         "WHERE ms_mailid='$id'";
  mysql_query($sql, $connect);
}

header("Location: mailing.php");
?>
