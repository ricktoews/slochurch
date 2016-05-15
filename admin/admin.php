<?php 
include("path.inc");
include("$PATH/includes/lib.inc"); 
?>
<html>
<head>
  <title>Administrative Area - <?php echo $Site->name; ?></title>
</head>

<frameset rows="74,*" border=0 frameborder=0 framespacing=0>
  <frame name="heading" src="heading.php" border=0 marginwidth=0>
  <frameset cols="147,*" border=0 frameborder=0 framespacing=0>
    <frame name="menu" src="menu.php" border=0 marginwidth=0>
    <frame name="content" src="blank.php" border=0 marginwidth=0>
  </frameset>
</frameset>
</html>

