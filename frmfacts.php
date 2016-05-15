<?php
include("path.inc");
$HEADING = "facts_figures";
include("includes/frmheader.inc");
?>

<?php 
$ff = new Facts();
$Services = new Services();
$SmallGroups = new SmallGroups();
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="4" /><br /><img src="<?php echo $PATH; ?>/images/arrow1.gif" /></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1" /></td>
    <td class="fg2" colspan="2"><b>Church Address</b></td>
  </tr>
  <tr valign="top">
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6" /></td>
  </tr>
  <tr valign="top">
    <td></td>
    <td></td>
    <td>
<?php
if ($ff->churchimage) {
  echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
  echo "  <tr><td><img src='$FACTS_PATH/$ff->churchimage' /></td><td><img src='images/spacer.gif' width='10' height='1' /></td></tr>";
  echo "  <tr><td colspan='2'><img src='images/spacer.gif' width='1' height='6' /></td></tr>";
  echo "</table>\n";
}
?>
    </td>
    <td class='cellsm'>
      <?php echo $ff->address; ?><br />
      <?php echo $ff->fmtphone; ?><br />
      <a href="contact.php" target="_parent">Email Us</a><br />
      <br />
      Membership: <?php echo $ff->membership; ?><br />
      Weekly Attendance: <?php echo $ff->avgattendance; ?><br />
    </td>
  </tr>
</table>

<br /><br />
<table border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="4" /><br /><img src="<?php echo $PATH; ?>/images/arrow1.gif" /></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1" /></td>
    <td class="fg2" colspan="2"><b>Services</b></td>
  </tr>
  <tr valign="top">
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6" /></td>
  </tr>
  <tr valign="top">
    <td></td>
    <td></td>
    <td>
<?php
if ($ff->servicesimage) {
  echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
  echo "  <tr><td><img src='$FACTS_PATH/$ff->servicesimage' /></td><td><img src='images/spacer.gif' width='10' height='1' /></td></tr>";
  echo "  <tr><td colspan='2'><img src='images/spacer.gif' width='1' height='6' /></td></tr>";
  echo "</table>\n";
}
?>
    </td>
    <td class="cellsm">
<?php
foreach($Services->data as $n => $s) {
  echo "<i>$s->weekday, $s->fmttime</i><br />";
  echo "&nbsp; &nbsp;$s->description<br />";
  echo "<img src='images/spacer.gif' width='1' height='6' /><br />";
}
?>
    </td>
  </tr>
</table>

<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="4" /><br /><img src="<?php echo $PATH; ?>/images/arrow1.gif" /></td>
    <td><img src="<?php echo $PATH; ?>/images/spacer.gif" width="5" height="1" /></td>
    <td class="fg2" colspan="2"><b>Small Groups</b></td>
  </tr>
  <tr valign="top">
    <td colspan="4"><img src="<?php echo $PATH; ?>/images/spacer.gif" width="1" height="6" /></td>
  </tr>
  <tr valign="top">
    <td></td>
    <td></td>
    <td>
<?php
if ($ff->groupsimage) {
  echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
  echo "  <tr><td><img src='$FACTS_PATH/$ff->groupsimage' /></td><td><img src='images/spacer.gif' width='10' height='1' /></td></tr>";
  echo "  <tr><td colspan='2'><img src='images/spacer.gif' width='1' height='6' /></td></tr>";
  echo "</table>\n";
}
?>
    </td>
    <td width="99%">
      <table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr bgcolor="<?php echo $bg3; ?>">
          <td class="fg3">Group</td>
          <td class="fg3">Where</td>
          <td class="fg3">When</td>
        </tr>
<?php
foreach($SmallGroups->data as $n => $sg) {
  echo "  <tr bgcolor='$bg5' valign='top'>";
  echo "    <td class='fg5'>$sg->name</td>";
  echo "    <td class='fg5'>$sg->location</td>";
  echo "    <td class='fg5'>$sg->time</td>";
  echo "  </tr>";
}
echo "</table>";
?>
    </td>
  </tr>
</table>
<?php
include("includes/frmfooter.inc");
?>
