<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$galleryid = $_REQUEST["galleryid"];

if ($arg == "1") {
  $sql = "SELECT * FROM site_photo_galleries " .
         "WHERE pg_id='$galleryid' ";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $gallery_name = $row->pg_gallery;
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
function updateGallery() {
  var f = document.forms["data"];
  f.arg.value = "4";
  f.submit();
}

function getGallery() {
  var f = document.forms["data"];
  f.arg.value = "1";
  f.action = "photo_manager.php";
  f.submit();
}

function showpic(id, w, h) {
  var attr = "width=" + (1*w+2) + ",height=" + (1*h+40) + ",screenX=50,screenY=50,top=50,left=50";
  var picwin = open("showpic.php?id="+id, "picwin", attr);
}
//-->
</script>
<form name="data" method="post" action="photo_manager.php">
<input type="hidden" name="arg" value="" />
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="<?php echo $perrow; ?>">Photo Manager</th>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="galleryid" onchange="getGallery()">
<?php
echo pm_gallery_dropdown($galleryid);
?>
      </select>
    </td>
  </tr>
</table>
<?php
if ($galleryid) {

  $perrow = 4;
  $gallery = array();
  $sql = "SELECT * FROM site_photos " .
         "WHERE p_galleryid='$galleryid' " .
         "ORDER BY p_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $gallery[] = new Gallery($row);
  }

  if (sizeof($gallery) > 0) {
?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg2; ?>">
    <th class="fg2" colspan="<?php echo $perrow; ?>"><?php echo $gallery_name; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="<?php echo $perrow; ?>">
      Click any image to edit its caption or order of appearance.
    </td>
  </tr>
<?php 
    $i = 0;
    $WIDTH_PCT = (100 / $perrow) . "%";
    $capcols = array();
    $cols = array();
    $caprow_pre = "<tr valign='top' bgcolor='$bg3'><td align='left' class='fg3' width='$WIDTH_PCT'>"; 
    $CAP_INTER = "</td><td align='left' class='fg3' width='$WIDTH_PCT'>";
    $caprow_post = "</td></tr>\n"; 
    $picrow_pre = "<tr valign='top' bgcolor='$bg5'><td align='center' class='fg5' width='$WIDTH_PCT'>"; 
    $PIC_INTER = "</td><td align='center' class='fg5' width='$WIDTH_PCT'>";
    $picrow_post = "</td></tr>\n"; 

    foreach ($gallery as $n => $g) {
      $id = $g->id;
      $capcols[] = "Del <input type=checkbox name='del_$g->id' value='$g->picture'>&nbsp; " . $g->photo;
      $cols[] = "<a href='photo_edit.php?id=$id'><img src='$g->picture' width='$g->thumbwidth' height='$g->thumbheight' border='0' /></a><br />" .
                "<br />" .
                "$g->caption<br />";
      if (sizeof($cols) == $perrow) {
        $caprow = $caprow_pre . implode($CAP_INTER, $capcols) . $caprow_post;
        $picrow = $picrow_pre . implode($PIC_INTER, $cols) . $picrow_post;
        $cols = array();
        $capcols = array();
        echo $caprow;
        echo $picrow;
      } 
    }
    if (sizeof($cols) > 0) {
      while (sizeof($cols) < $perrow) {
        $capcols[] = "";
        $cols[] = "";
      }
      $caprow = $caprow_pre . implode($CAP_INTER, $capcols) . $caprow_post;
      $picrow = $picrow_pre . implode($PIC_INTER, $cols) . $picrow_post;
      $cols = array();
      $capcols = array();
      echo $caprow;
      echo $picrow;
    }
?>
</table>
<br />
<div align="center">
<input type=button value="Delete Selected" onclick="updateGallery()" />
</div>
<?php
  }
}
?>
</form>
<div align="center">
<p class="cell"><a href="photo_upload.php">Photo Upload</a></p>
</div>
<?php 
include("includes/footer.inc");
?>
