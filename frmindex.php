<?php
include("path.inc");
$HEADING = "helping";
include("includes/frmheader.inc");
?>
<?php
class HomePageData {
  function HomePageData($data)
  {
    $this->id = $data->hp_id;
    $this->heading = strtolower($data->hp_heading) == "no heading" ? "" : $data->hp_heading;
    $this->image = $data->hp_image;
    $this->text = format_textarea($data->hp_text);
  }
}


class HomePage {
  function HomePage()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();

    $sql = "SELECT * FROM site_homepage " .
           "ORDER BY hp_seq";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new HomePageData($row);
    }
  }
}
?>

<?php 
$HomePageList = new HomePage();

echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($HomePageList->data as $n => $i) {
  if ($i->heading) {
    echo "<tr valign='top'>\n" .
         "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
         "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
         "  <td class='fg2'><b>$i->heading</b></td>\n" .
         "</tr>\n";
    echo "<tr valign='top'>\n" .
         "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
         "</tr>\n";
  }
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>";
  if ($i->image && file_exists($HOME_PATH . '/' . $i->image)) {
    echo "<table align='left' border='0' cellpadding='0' cellspacing='0'><tr><td><img src='$HOME_PATH/$i->image' /></td><td><img src='images/spacer.gif' width='5' height='1' /></td></tr></table>";
  }
  echo "    $i->text" .
       "  </td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='24' /></td>\n" .
       "</tr>\n";
}
echo "</table>\n";
?>

<?php
include("includes/frmfooter.inc");
?>
