<?php
include("path.inc");
$HEADING = "picture_gallery";
include("includes/frmheader.inc");

$arg = $_REQUEST["arg"];
$galleryid = $_REQUEST["galleryid"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_photo_galleries " .
         "WHERE pg_id='$galleryid' ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $gallery_name = $row->pg_gallery;
    $gallery_desc = $row->pg_description;
  }
}
elseif ($arg == "4") {
  while (list($k, $v) = each($HTTP_POST_VARS)) {
    if (preg_match("/del_(.*)/", $k, $regs)) {
      $sql = "SELECT p_photo FROM site_photos " .
             "WHERE p_id='$regs[1]'";
      $result = mysql_query($sql, $connect);
      if ($row = mysql_fetch_object($result)) {
        $photo = $row->p_photo;
        if (file_exists("$GALLERY_PATH/$galleryid/$photo")) {
          unlink("$GALLERY_PATH/$galleryid/$photo");
        }
      }
      $sql = "DELETE FROM site_photos " .
             "WHERE p_id='$regs[1]'";
      mysql_query($sql, $connect);
    } 
    if (preg_match("/cap_(.*)/", $k, $regs)) {
      $sql = "UPDATE site_photos " .
             "SET p_caption='$v' " .
             "WHERE p_id='$regs[1]'";
      mysql_query($sql, $connect);
    }
  }  
}
?>
<script language="JavaScript">
<!--
function getGallery() {
  var f = document.forms["data"];
  f.arg.value = "1";
  f.action = "frmgallery.php";
  f.submit();
}

function showpic(id, w, h) {
  if (w > 0 && h > 0) {
    var attr = "width=" + (1*w+2) + ",height=" + (1*h+40) + ",screenX=50,screenY=50,top=50,left=50";
  }
  else {
    var attr = "width=640,height=480,screenX=50,screenY=50,top=50,left=50";
  }
  var picwin = open("showpic.php?id="+id, "picwin", attr);
}
//-->
</script>

<form name="data" method="post" action="frmgallery.php">
<input type="hidden" name="arg" value="" />
<select style="<?php echo $FIELD_STYLE; ?>" name="galleryid" onchange="getGallery()">
<?php
echo pm_gallery_dropdown($galleryid);
?>
</select>
<br />
<br />
<?php
if ($galleryid) {

  $perrow = 3;
  $gallery = array();
  $sql = "SELECT * FROM site_photos " .
         "WHERE p_galleryid='$galleryid' " .
         "ORDER BY p_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $gallery[] = new Gallery($row);
  }

  if (sizeof($gallery) > 0) {
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr valign='top'>\n" .
         "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
         "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
         "  <td class='fg2'><b>$gallery_name</b></td>\n" .
         "</tr>\n";
    echo "<tr valign='top'>\n" .
         "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
         "</tr>\n";
    echo "<tr valign='top'>\n" .
         "  <td></td>\n" .
         "  <td></td>\n" .
         "  <td class='generalsm'>$gallery_desc</td>\n" .
         "</tr>\n";
    echo "</table>\n";
?>
<br />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
<?php 
    $i = 0;
    $WIDTH_PCT = (100 / $perrow) . "%";
    $cols = array();
    $CAP_INTER = "</td><td align='left' class='fg3' width='$WIDTH_PCT'>";
    $picrow_pre = "<tr valign='top' bgcolor='$bg5'><td align='center' class='fg5' width='$WIDTH_PCT'>"; 
    $PIC_INTER = "</td><td align='center' class='fg5' width='$WIDTH_PCT'>";
    $picrow_post = "</td></tr>\n"; 

    foreach ($gallery as $n => $g) {
      $id = $g->id;
      if ($g->picture && file_exists($g->picture)) {
        $cols[] = "<a href='javascript:showpic($id, $g->width, $g->height)'><img src='$g->picture' width='$g->thumbwidth' height='$g->thumbheight' border='0' /></a><br />" .
                  "<br />" .
                  "$g->caption<br />";
        if (sizeof($cols) == $perrow) {
          $picrow = $picrow_pre . implode($PIC_INTER, $cols) . $picrow_post;
          $cols = array();
          echo $picrow;
        } 
      }
    }
    if (sizeof($cols) > 0) {
      while (sizeof($cols) < $perrow) {
        $cols[] = "";
      }
      $picrow = $picrow_pre . implode($PIC_INTER, $cols) . $picrow_post;
      $cols = array();
      echo $picrow;
    }
?>
</table>
<br />
<?php
  }
}
?>
</form>
<?php
include("includes/frmfooter.inc");
?>
