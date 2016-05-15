<?php
include("path.inc");
$HEADING = "map";
include("includes/frmheader.inc");

$directions = array(
  'south' => 'US-101 S.<br />' .
         'Take the exit toward SANTA ROSA STREET. (<0.1 miles)<br />' .
         'Turn SLIGHT RIGHT onto CA-1/OLIVE ST. (0.1 miles)<br />' .
         'Turn RIGHT onto CA-1/SANTA ROSA ST. Continue to follow SANTA ROSA ST. (0.4 miles)<br />' .
         'Turn RIGHT onto HIGUERA ST. (<0.1 miles)<br />' .
         'Turn LEFT onto OSOS ST. (0.1 miles)<br />' .
         'End at 1301 Osos St.',
  'north' => 'US-101 N.<br />' .
         'Take the MARSH ST exit. (0.1 miles)<br />' .
         'Stay STRAIGHT to go onto MARSH ST. (0.6 miels)<br />' .
         'Turn RIGHT onto OSOS ST. (<0.1 miles)<br />' .
         'End at 1301 Osos St.'
);

?>
<?php
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($directions as $direction => $detail) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'><b>Driving $direction</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>\n" .
       "    $detail\n" .
       "  </td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='24' /></td>\n" .
       "</tr>\n";
}
echo "</table>\n";
?>
<table bgcolor="#687680" border="0" cellpadding="0" cellspacing="1"><tr><td><img src="images/map.gif" /></td></tr></table>
<br />
<?php
include("includes/frmfooter.inc");
?>
