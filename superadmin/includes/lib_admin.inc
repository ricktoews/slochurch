<?php
include("$PATH/includes/lib.inc");

$LOGIN_ID = $_COOKIE["cookie_superadmin"];

if (!$LOGIN_ID) {
  header("Location: index.php");
  exit();
}
?>

