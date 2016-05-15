<?php
include("path.inc");
include("connect.inc");

$arg = $_REQUEST["arg"];
$c_pagebgcolor = $_REQUEST["c_pagebgcolor"];
$c_bgcolor = $_REQUEST["c_bgcolor"];
$c_bodytext = $_REQUEST["c_bodytext"];
$c_h1 = $_REQUEST["c_h1"];
$c_h2 = $_REQUEST["c_h2"];
$c_h3 = $_REQUEST["c_h3"];
$c_h4 = $_REQUEST["c_h4"];
$c_sidebg = $_REQUEST["c_sidebg"];
$c_sidefg = $_REQUEST["c_sidefg"];
$c_sidelink = $_REQUEST["c_sidelink"];
$c_sidealink = $_REQUEST["c_sidealink"];
$c_sidevlink = $_REQUEST["c_sidevlink"];
$c_navtext = $_REQUEST["c_navtext"];
$c_navlink = $_REQUEST["c_navlink"];
$c_navalink = $_REQUEST["c_navalink"];
$c_navvlink = $_REQUEST["c_navvlink"];
$c_link = $_REQUEST["c_link"];
$c_alink = $_REQUEST["c_alink"];
$c_vlink = $_REQUEST["c_vlink"];
$c_borcol = $_REQUEST["c_borcol"];
$c_bg1 = $_REQUEST["c_bg1"];
$c_fg1 = $_REQUEST["c_fg1"];
$c_bg2 = $_REQUEST["c_bg2"];
$c_fg2 = $_REQUEST["c_fg2"];
$c_bg3 = $_REQUEST["c_bg3"];
$c_fg3 = $_REQUEST["c_fg3"];
$c_bg4 = $_REQUEST["c_bg4"];
$c_fg4 = $_REQUEST["c_fg4"];
$c_bg5 = $_REQUEST["c_bg5"];
$c_fg5 = $_REQUEST["c_fg5"];
$c_id = $_REQUEST["c_id"];

if ($arg == "2") {
  $sql = "UPDATE site_colors " .
         "SET c_pagebgcolor='$c_pagebgcolor', " .
         "    c_bgcolor='$c_bgcolor', " .
         "    c_bodytext='$c_bodytext', " .
         "    c_h1='$c_h1', " .
         "    c_h2='$c_h2', " .
         "    c_h3='$c_h3', " .
         "    c_h4='$c_h4', " .
         "    c_sidebg='$c_sidebg', " .
         "    c_sidefg='$c_sidefg', " .
         "    c_sidelink='$c_sidelink', " .
         "    c_sidealink='$c_sidealink', " .
         "    c_sidevlink='$c_sidevlink', " .
         "    c_navtext='$c_navtext', " .
         "    c_navlink='$c_navlink', " .
         "    c_navalink='$c_navalink', " .
         "    c_navvlink='$c_navvlink', " .
         "    c_link='$c_link', " .
         "    c_alink='$c_alink', " .
         "    c_vlink='$c_vlink', " .
         "    c_borcol='$c_borcol', " .
         "    c_bg1='$c_bg1', " .
         "    c_fg1='$c_fg1', " .
         "    c_bg2='$c_bg2', " .
         "    c_fg2='$c_fg2', " .
         "    c_bg3='$c_bg3', " .
         "    c_fg3='$c_fg3', " .
         "    c_bg4='$c_bg4', " .
         "    c_fg4='$c_fg4', " .
         "    c_bg5='$c_bg5', " .
         "    c_fg5='$c_fg5' " .
         "WHERE c_id='$c_id'";
  mysql_query($sql, $connect);

  if ($c_id == 1) {
    $lines = file("styletemplate.css");
    foreach ($lines as $n => $l) {
      if (preg_match("/\[([^\]]+)\]/", $l, $match)) {
        $value = "#" . $GLOBALS["c_" . $match[1]];
        $lines[$n] = preg_replace("/\[[^\]]+\]/", $value, $l);
      }
    } 
    $style = implode("", $lines);
    $out = "$PATH/style/style.css";
    $fp = fopen($out, "w");
    fputs($fp, $style);
    fclose($fp); 
  }
}

header("Location: colors.php");
?>
