<?php 
include("path.inc");
include($PATH . "/includes/lib.inc"); 

$samp_bgcolor = $_REQUEST["samp_bgcolor"];
$samp_link = $_REQUEST["samp_link"];
$samp_alink = $_REQUEST["samp_alink"];
$samp_vlink = $_REQUEST["samp_vlink"];
$samp_borcol = $_REQUEST["samp_borcol"];
$samp_bg1 = $_REQUEST["samp_bg1"];
$samp_fg1 = $_REQUEST["samp_fg1"];
$samp_bg2 = $_REQUEST["samp_bg2"];
$samp_fg2 = $_REQUEST["samp_fg2"];
$samp_bg3 = $_REQUEST["samp_bg3"];
$samp_fg3 = $_REQUEST["samp_fg3"];
$samp_bg4 = $_REQUEST["samp_bg4"];
$samp_fg4 = $_REQUEST["samp_fg4"];
$samp_bg5 = $_REQUEST["samp_bg5"];
$samp_fg5 = $_REQUEST["samp_fg5"];
$samp_bodytext = $_REQUEST["samp_bodytext"];
?>

<html>
<head>
  <title>Color Sample</title>
  <style type="text/css">
  .samp_fg1 {
    font-family:arial, helvetica;
    font-size:8pt;
    color:<?php echo $samp_fg1; ?>;
  }
  .samp_fg2 {
    font-family:arial, helvetica;
    font-size:8pt;
    color:<?php echo $samp_fg2; ?>;
  }
  .samp_fg3 {
    font-family:arial, helvetica;
    font-size:8pt;
    color:<?php echo $samp_fg3; ?>;
  }
  .samp_fg4 {
    font-family:arial, helvetica;
    font-size:8pt;
    color:<?php echo $samp_fg4; ?>;
  }
  .samp_fg5 {
    font-family:arial, helvetica;
    font-size:8pt;
    color:<?php echo $samp_fg5; ?>;
  }
  .cell {
    font-family:arial;
    font-size:8pt;
  }
  </style>
</head>
<body bgcolor="<?php echo $samp_bgcolor; ?>" text="<?php echo $samp_bodytext; ?>" link="<?php echo $samp_link; ?>" alink="<?php echo $samp_alink; ?>" vlink="<?php echo $samp_vlink; ?>">
<img src="../images/spacer.gif" width=1 height=10><br>
<div align=center>
<table bgcolor="<?php echo $samp_borcol; ?>" border="0" cellpadding="3" cellspacing="1">
  <tr bgcolor="<?php echo $samp_bg1; ?>">
    <th class="samp_fg1" colspan=5>Sample Heading 1</th>
  </tr>
  <tr bgcolor="<?php echo $samp_bg2; ?>">
    <th class="samp_fg2" colspan=5>Sample Heading 2</th>
  </tr>
  <tr bgcolor="<?php echo $samp_bg3; ?>">
    <th class="samp_fg3">Sample Heading 3</th>
    <th class="samp_fg3">Sample Heading 3</th>
  </tr>
  <tr bgcolor="<?php echo $samp_bg5; ?>">
    <td class="samp_fg5" width="300">
      This is what the ordinary text will look like.
    </td>
    <td class="samp_fg5" width="300">
      This is what the ordinary text will look like.
    </td>
  </tr>
</table>
<p>
<div class="cell">
<font color="<?php echo $samp_link; ?>">Sample Link</font> |
<font color="<?php echo $samp_alink; ?>">Sample Active Link</font> |
<font color="<?php echo $samp_vlink; ?>">Sample Visited Link</font>
</div>
<p><br>
<div class="cell">
<a href="javascript:self.close()"><font color="#888888">close</font></a>
</div>
</div>
</body>
</html>

