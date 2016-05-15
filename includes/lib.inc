<?
error_reporting(E_ALL ^ E_DEPRECATED);
$PATH = '.';
if (!isset($CONT_TAG)) { $CONT_TAG = ''; };
require_once("connect.inc");
require_once("classes.inc");
/*
if ($_SESSION["LOGINID"]) {
  $Login = new Login($_SESSION["LOGINID"]);
}
*/
$Site = new Site();
$Nav = new Nav();

$MAILING_PATH = "$PATH/images/mailing";
$ABSMAILING_PATH = "images/mailing";
$_REQUEST['WEEKDAYS'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$WEEKDAYS = $_REQUEST['WEEKDAYS'];
$_REQUEST['IMAGE_PATH'] = "$PATH/images/members";
$IMAGE_PATH =& $_REQUEST['IMAGE_PATH'];
$_REQUEST['SCHEDULE_PATH'] = "$PATH/images/schedule";
$SCHEDULE_PATH =& $_REQUEST['SCHEDULE_PATH'];
$_REQUEST['MESSAGE_PATH'] = "$PATH/audio/messages";
$MESSAGE_PATH =& $_REQUEST['MESSAGE_PATH'];
$_REQUEST['PODCAST_PATH'] = "$PATH/audio/podcasts";
$PODCAST_PATH =& $_REQUEST['PODCAST_PATH'];
$_REQUEST['MARKET_PATH'] = "$PATH/images/market";
$MARKET_PATH =& $_REQUEST['MARKET_PATH'];
$_REQUEST['FACTS_PATH'] = "$PATH/images/facts";
$FACTS_PATH =& $_REQUEST['FACTS_PATH'];
$_REQUEST['GALLERY_PATH'] = "$PATH/images/gallery";
$GALLERY_PATH =& $_REQUEST['GALLERY_PATH'];
$_REQUEST['HOME_PATH'] = "$PATH/images/home";
$HOME_PATH =& $_REQUEST['HOME_PATH'];
$HORIZ_LINE = "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td background='$PATH/images/dot_horiz.gif'><img src='$PATH/images/spacer.gif' width='1' height='1'></td></tr></table>";
$_REQUEST['THUMB_WIDTH'] = 100;
$_REQUEST['TODAY'] = date("Y-m-d");
$TODAY = $_REQUEST['TODAY'];

$FIELD_STYLE = "
  font-family:Verdana, Arial, Helvetica;
  font-size:8pt;
  color:#697882;
  background-color:#cde5f9;
  border-width:1;
  border-style:solid;
  border-color:#a4c1d8;
";


function imageHeight($img_name, $img_file) {
  global $THUMB_WIDTH;
  if (preg_match("/\.jpg/", $img_name)) {
    $im = imagecreatefromjpeg($img_file);
  }
  else {
    $im = imagecreatefromgif($img_file);
  }
  $original_width = imagesx($im);
  $original_height = imagesy($im);
  return intval($THUMB_WIDTH / $original_width * $original_height);
}

function fix_html($ta) {
  global $HORIZ_LINE;

  $ta = preg_replace("/<table[^>]*>/", "<table border='1' cellpadding='3' cellspacing='1'>\n", $ta);
  $ta = preg_replace("/<hr[^>]*>/", "$HORIZ_LINE", $ta);
  return $ta;
}


function format_textarea($ta) {
  global $HORIZ_DIVIDER;
  $ta = preg_replace("/<hr[^>]*>/", "$HORIZ_DIVIDER", $ta);
  $para = explode("\r\n\r\n", $ta);
  $text = "";
  while (list($n, $p) = each($para)) {
    $p = "<p>" . preg_replace("/\r\n/", "<br />", $p) . "</p>";
    $text .= $p . "\n";
  }
  return $text;
}

function format_text($t) {
  $t = preg_replace("/\r\n/", "<br />", $t);
  return $t;
}

srand((double) microtime() * 1000000);
function gen_confirmid() {
  global $connect;
  $idlength = 6;
  $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
  $counter = 0;
  $unique = 0;
  $id = "";
  while (strlen($id) < $idlength) {
    $n = intval(.5 + rand(0, strlen($chars) - 1));
    $c = substr($chars, $n, 1);
    $id .= $c;
  }
  return $id;
}


function fix_addslashes($s) {
// $s = addslashes($s);
  return $s;
}


function fix_stripslashes($s) {
// $s = stripslashes($s);
  return $s;
}


function unformat_phone($p) {
  $up = "";
  for ($i = 0; $i < strlen($p); $i++) {
    $c = substr($p, $i, 1);
    if (ord($c) >= 48 && ord($c) <= 57) {
      $up .= $c;
    }
  }
  return $up;
}


function format_phone($p) {
  if (strlen($p) == 0) {
    return "";
  }
  $args = func_get_args();
  if (sizeof($args) == 2) {
    $e = $args[1];
  }
  $area = substr($p, 0, 3);
  $exch = substr($p, 3, 3);
  $num = substr($p, 6, 4);
  $fmt = "($area) " . $exch . "-" . $num;
  if ($e) {
    $fmt .= " x$e";
  }
  return $fmt;
}


function format_name($f, $l) {
  $fmtname = "Name not provided";
  if (strlen($f) > 0 && strlen($l) > 0) {
    $fmtname = "$f $l";
  }
  elseif (strlen($l) > 0) {
    $fmtname = $l;
  }
  elseif (strlen($f) > 0) {
    $fmtname = $f;
  }
  return $fmtname;
}


function format_name_reverse($f, $l) {
  $fmtname = "Name not provided";
  if (strlen($f) > 0 && strlen($l) > 0) {
    $fmtname = "$l, $f";
  }
  elseif (strlen($l) > 0) {
    $fmtname = $l;
  }
  elseif (strlen($f) > 0) {
    $fmtname = $f;
  }
  return $fmtname;
}


function format_address($a, $c, $s, $z) {
  $fmtaddress = "Address not provided";
  $city = "";
  if ($c && $s) {
    $city = "$c, $s";
  }
  elseif (!$s) {
    $city = $c;
  }
  else {
    $city = $s;
  }
  if ($a && $city) {
    $fmtaddress = "$a, $city";
  }
  elseif (!$city) {
    $fmtaddress = $a;
  }
  else {
    $fmtaddress = $city;
  }
  if ($fmtaddress && $z) {
    $fmtaddress .= "  $z";
  }
  elseif (!fmtaddress) {
    $fmtaddress = $z;
  }
  return $fmtaddress;
}


function format_time($b, $e = '') {
  list($bhr, $bmn) = explode(':', $b);
  $bap = $bhr >= 12 ? 'pm' : 'am';
  $bhr = 1 * ($bhr > 12 ? $bhr - 12 : $bhr);
  $bhm = "$bhr:$bmn";
  if ($e) {
    list($ehr, $emn) = explode(':', $e);
    $eap = $ehr >= 12 ? 'pm' : 'am';
    $ehr = 1 * ($ehr > 12 ? $ehr - 12 : $ehr);
    $ehm = "$ehr:$emn";
    if ($bap == $eap) {
      $fmttime = "$bhm - $ehm$eap";
    }
    else {
      $fmttime = $bhm . $bap . " - " . $ehm . $eap;
    }
  }
  else {
    $fmttime = $bhm . $bap;
  }

  return $fmttime;
}


function make_email_link($e) {
  $eparts = array();
  while (strlen($e) > 5) {
    $eparts[] = substr($e, 0, 5);
    $e = substr($e, 5, strlen($e) - 5);
  }
  if (strlen($e) > 0) {
    $eparts[] = $e;
  }
  $link = "<script language=\"JavaScript\">\n";
  $link .= "mercury = \"\";\n";
  while (list($n, $m) = each($eparts)) {
    $link .= "mercury += \"$m\";\n";
  }
  if ($l) {
    $link .= "venus = \"$l\";\n";
  }
  else {
    $link .= "venus = mercury;\n";
  }
  $link .= "document.write(\"<a href='mailto:\" + mercury + \"'>\" + venus + \"</a>\");\n";
  $link .= "</script>\n";
  return $link;
}


function help_tag($tag) {
  global $connect;
  $WIDTH = 300;
  $style = "display:none";
  $style = "position:absolute;top:5;left:5;padding:3;background-color:#cccccc;color:#666666;display:none";
  $style = "position:absolute;width:$WIDTH;padding:3;border-width:1;border-style:solid;border-color:#000000;background-color:#cccccc;color:#666666;display:none";
  $helptext_block = "<div id='help_$tag' style='$style'>__HELP__</div>";
  $click = "if (!this.on) { " .
           "  this.on=1; " .
           "  el=document.getElementById(\"help_$tag\"); " .
           "  x=(!window.x) ? event.clientX : x; " .
           "  y=(!window.y) ? event.clientY : y; " .
           "  el.style.top=y; " .
           "  el.style.left=x; " .
           "  el.style.display = \"\"; " .
           "} " .
           "else { " .
           "  this.on=0; " .
           "  x = 0; " .
           "  y = 0; " .
           "  el.style.display=\"none\"; " .
           "}";
  $click = "attr=\"top=50,left=50,width=200,height=200,scrollbars\"; " .
           "helpwin=open(\"\",\"helpwin\",attr); " .
           "helpwin.document.write(\"__HELP__\"); ";
  $mouseover = "el=document.getElementById(\"help_$tag\"); " .
               "y=(!window.y) ? event.clientY-5 : y; " .
               "x=(!window.x) ? event.clientX+5 : x; " .
               "x=(document.body.clientWidth - x < $WIDTH) ? document.body.clientWidth - $WIDTH - 3 : x; " .
               "el.style.top=y-el.clientHeight; " .
               "el.style.left=x+2; " .
               "el.style.display = \"\"; ";
  $mouseout =  "x = 0; " . 
               "y = 0; " .
               "el.style.display=\"none\"; ";
  $helptag_pre = "<a href='#' xonclick='$click' onmouseover='$mouseover' onmouseout='$mouseout'>";
  $helptag_post = "</a>";
  $filename = basename($_SERVER['PHP_SELF']);
  $sql = "SELECT * FROM site_admin_help " .
         "WHERE ah_filename='$filename' " .
         "  AND ah_tag='$tag'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    if ($row->ah_helptext) {
      $helptext = preg_replace("/'/", "`", $row->ah_helptext);
      $block = preg_replace("/__HELP__/", $helptext, $helptext_block);
      $tag = $block . $tag . " " . preg_replace("/__HELP__/", $helptext, $helptag_pre) . "(help)" . $helptag_post . "";
    }
  }
  return $tag;
}


function show_subs($sub, $indent, $colorflag) {
  global $connect;
  global $match;
  global $exclude;
  global $ccarray;

  $sql = "SELECT * FROM site_calendar_categories " .
         "WHERE cc_subid='$sub' " .
         "ORDER BY cc_subid, cc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    if (sizeof($ccarray) == 0 || array_search($row->cc_id, $ccarray) === TRUE) {
//      $FLAG = "X";
    }
    else {
//      $FLAG = "O";
    }
    $selected = $match[$row->cc_id] == 1 ? "selected" : "";
    if ($colorflag) {
      $background_color = $row->cc_background_color;
      $color = $row->cc_color;
      if ($row->cc_id == $exclude) {
        echo "<option style='background-color:$background_color; color:$color' value=''>$indent $row->cc_category $FLAG</option>\n";
      }
      else {
        echo "<option $selected style='background-color:$background_color; color:$color' value='$row->cc_id'>$indent $row->cc_category $FLAG</option>\n";
      }
    }
    else {
      if ($row->cc_id == $exclude) {
        echo "<option value=''>$indent $row->cc_category $FLAG</option>\n";
      }
      else {
        echo "<option $selected value='$row->cc_id'>$indent $row->cc_category $FLAG</option>\n";
      }
    }
    show_subs($row->cc_id, $indent . " -", $colorflag);
  }
  return;
}


function get_categories($ccid) {
  global $connect;
  static $catlist;
  if (sizeof($catlist) == 0) {
    $catlist = array();
  }
  
  $sql = "SELECT * FROM site_calendar_categories " .
         "WHERE cc_subid='$ccid' " .
         "ORDER BY cc_subid, cc_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $catlist[] = $row->cc_id;
    get_categories($row->cc_id);
  }
  return $catlist;
}


function pm_gallery_dropdown($id) {
  global $connect;

  $selectString = "<option value=''>Gallery</option>\n";
  $sql = "SELECT * FROM site_photo_galleries " .
         "ORDER BY pg_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $listid = $row->pg_id;
    $selected = $listid == $id ? "selected" : "";
    $listitem = $row->pg_gallery;
    $selectString .= "<option $selected value='$listid'>$listitem</option>\n";
  }
  return $selectString;
}


function check_mysql($sql, $connect) {
// echo "SQL: $sql<br />";
  $result = mysql_query($sql, $connect);
  if ($errno = mysql_errno($connect)) {
    $error = mysql_error($connect);
    echo "MySQL error: $error ($errno)<br />";
    echo "$sql<br />";
  }
  return $result;
}
?>
