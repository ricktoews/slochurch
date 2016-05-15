<?php
include("path.inc");
include("includes/lib_admin.inc");

$id = $_REQUEST["id"];
$arg = $_REQUEST['arg'];
$office = $_REQUEST["office"];
$display = $_REQUEST["display"];
$followid = $_REQUEST["followid"];
$upload_officelist = $_FILES["upload_officelist"];
$officelist_name = $upload_officelist["name"];
$officelist_tmpname = $upload_officelist["tmp_name"];
$del_officelist = $_REQUEST["del_officelist"];
$staffids = $_REQUEST["staffids"];

if ($officelist_name) {
// Delete existing data, if requested.
  if ($del_officelist) {
    $sql = "DELETE FROM site_offices ";
    mysql_query($sql, $connect);
    $sql = "DELETE FROM site_staff ";
    mysql_query($sql, $connect);
  }
// Get uploaded data.
  $offices = file($upload_officelist);
  while (list($n, $line) = each($offices)) {
    if ($n == 0) { continue; }
    list($office, $name, $display) = explode("\t", $line);
// Parse name.
    $first = "";
    $suffix = "";
    $name = preg_replace("/\"/", "", $name);
    if (preg_match("/(.+), ([JjSs][rR][\.]?)$/", $name, $regs)) {
      $name = $regs[1];
      $suffix = $regs[2];
    }
    $nameparts = explode(" ", $name);
    $last = $nameparts[sizeof($nameparts) - 1];
    for ($i = 0; $i < sizeof($nameparts) - 1; $i++) {
      $first .= " " . $nameparts[$i];
    }
    $first = trim($first) . ($suffix ? " $suffix" : "");
    if (!$office || !$last) {
      continue;
    }
    $office = trim($office);
    $sql = "SELECT * FROM site_members " .
           "WHERE m_first='$first' " .
           "  AND m_last='$last'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $m_id = $row->m_id;
    }
    $sql = "SELECT * FROM site_offices " .
           "WHERE o_office='$office'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $o_id = $row->o_id;
    }
    else {
      $sql = "INSERT INTO site_offices " .
             "SET o_office='$office', " .
             "    o_seq='9999', " .
             "    o_display='0'";
      mysql_query($sql, $connect);
      $o_id = mysql_insert_id($connect);
    }
    if ($m_id) {
      $sql = "SELECT * FROM site_staff " .
             "WHERE s_officeid='$o_id' " .
             "  AND s_memberid='$m_id'";
      $result = mysql_query($sql, $connect);
      if (mysql_num_rows($result) == 0) {
        $sql = "INSERT INTO site_staff " .
               "SET s_officeid='$o_id', " .
               "    s_memberid='$m_id'";
        mysql_query($sql, $connect);
      }
    }
    else {
      echo "<font face='arial', size='-2'>$office: $first $last (unlisted)</font><br />";
    }
  }
}

// Update office.
if ($arg == "2" && $office > "") {
  if ($id) {
    $sql = "UPDATE site_offices " .
           "SET o_office='$office', " .
           "    o_display='$display' " .
           "WHERE o_id='$id'";
    mysql_query($sql, $connect);
    $sql = "DELETE FROM site_staff " .
           "WHERE s_officeid='$id'";
    mysql_query($sql, $connect);
    $staff = explode("|", $staffids);
    while (list($n, $s) = each($staff)) {
      $sql = "INSERT INTO site_staff " .
             "SET s_officeid='$id', " .
             "    s_memberid='$s'";
      mysql_query($sql, $connect);
    }
  }
  else {
// Add office.
    $sql = "INSERT INTO site_offices " .
           "SET o_office='$office', " .
           "    o_display='$display' ";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_offices " .
         "WHERE o_id <> '$id' " .
         "ORDER BY o_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->o_id;
    if ($followid == $row->o_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_offices " .
           "SET o_seq='$s' " .
           "WHERE o_id='$i'";
    mysql_query($sql, $connect);
  }
}
// Delete office.
else if ($arg == "4") {
  $sql = "DELETE FROM site_offices " .
         "WHERE o_id='$id'";
  mysql_query($sql, $connect);
  $sql = "DELETE FROM site_staff " .
         "WHERE s_officeid='$id'";
  mysql_query($sql, $connect);
}

header("Location: staff.php");
?>
