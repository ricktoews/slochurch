<?php
include("path.inc");
$s = $_REQUEST['s'];
$HEADING = "helping";
include("includes/frmheader.inc");
?>

<?php
if ($s == 1) {
  echo "<p>Thanks for your interest.  It looks as though you've already signed up.  Unless you didn't respond to the request for confirmation, you should be on the mailing list.</p>";
}
else {
  echo "<p>Thanks for your interest.  You should soon receive an email requesting confirmation.</p>";
}
?>

<?php
include("includes/frmfooter.inc");
?>
