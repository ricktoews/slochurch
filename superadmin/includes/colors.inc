<?php
$sql = "SELECT * FROM site_colors " .
       "WHERE c_tag='metaadmin'";
$result = mysql_query($sql, $connect);
if ($row = mysql_fetch_object($result)) {
  $bgcolor = "#$row->c_bgcolor";
  $bodytext = "#$row->c_bodytext";
  $sidebg = "#$row->c_sidebg";
  $sidefg = "#$row->c_sidefg";
  $sidelink = "#$row->c_sidelink";
  $sidealink = "#$row->c_sidealink";
  $sidevlink = "#$row->c_sidevlink";
  $navtext = "#$row->c_navtext";
  $navlink = "#$row->c_navlink";
  $navalink = "#$row->c_navalink";
  $navvlink = "#$row->c_navvlink";
  $link = "#$row->c_link";
  $alink = "#$row->c_alink";
  $vlink = "#$row->c_vlink";
  $borcol = "#$row->c_borcol";
  $bg1 = "#$row->c_bg1";
  $fg1 = "#$row->c_fg1";
  $bg2 = "#$row->c_bg2";
  $fg2 = "#$row->c_fg2";
  $bg3 = "#$row->c_bg3";
  $fg3 = "#$row->c_fg3";
  $bg4 = "#$row->c_bg4";
  $fg4 = "#$row->c_fg4";
  $bg5 = "#$row->c_bg5";
  $fg5 = "#$row->c_fg5";
}
?>
