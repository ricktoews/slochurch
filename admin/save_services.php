<?php
include("path.inc");
include("includes/lib_admin.inc");

foreach($_POST as $k => $v) {
  if (preg_match("/day_(\d+)$/", $k, $matches)) {
    $r = $matches[1];
    $id = $_POST["id_$r"];
    $day = $_POST["day_$r"];
    if (preg_match("/^(\d+):(\d+)$/", $_POST["bhm_$r"], $matches)) {
      $bhr = $matches[1];
      $bmn = $matches[2];
      $bap = $_POST["bap_$r"];
      $bhr = $bap == 'p' ? $bhr + 12 : $bhr;
      $begin = $bhr < 10 ? "0$bhr:$bmn" : "$bhr:$bmn";
    }
    else {
      $begin = '';
    }
    if (preg_match("/^(\d+):(\d+)$/", $_POST["ehm_$r"], $matches)) {
      $ehr = $matches[1];
      $emn = $matches[2];
      $eap = $_POST["eap_$r"];
      $ehr = $eap == 'p' ? $ehr + 12 : $ehr;
      $end = $ehr < 10 ? "0$ehr:$emn" : "$ehr:$emn";
    }
    else {
      $end = '';
    }
    $description = $_POST["description_$r"];
    $del = $_POST["del_$r"];
    if ($v) {
      if ($id) {
        if ($del) {
          $sql = "DELETE FROM site_services " .
                 "WHERE s_id='$id'";
          mysql_query($sql, $connect);
        }
        else {
          $sql = "UPDATE site_services " .
                 "SET s_day='$day', " .
                 "    s_begin='$begin', " .
                 "    s_end='$end', " .
                 "    s_description='$description' " .
                 "WHERE s_id='$id'";
          mysql_query($sql, $connect);
        }
      }
      else {
        $sql = "INSERT INTO site_services " .
               "SET s_day='$day', " .
               "    s_begin='$begin', " .
               "    s_end='$end', " .
               "    s_description='$description'";
        mysql_query($sql, $connect);
      }
    }
  }
}

header("Location: services.php");
?>
