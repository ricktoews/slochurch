<?php 
include("path.inc");
include($PATH . "/includes/lib.inc"); 

$fmt = $_REQUEST["fmt"];

if ($fmt) {
  include("../tmp/htmltmp");
}
else {
  echo "<pre>";
  echo `nroff ../tmp/texttmp`;
  echo "</pre>";
}
?>
