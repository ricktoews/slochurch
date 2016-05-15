<?php
include("path.inc");
$id = $_REQUEST['id'];
$IFRAME_SRC = $id ? "frmcontact.php?id=$id" : "frmcontact.php";
include("includes/header.inc");
?>

<?php
include("includes/footer.inc");
?>
