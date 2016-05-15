<?php
include("path.inc");
$HEADING = "links";
include("includes/frmheader.inc");
?>

<?php
class LinkCategory {
  function LinkCategory($category)
  {
    $this->category = $category;
    $this->info = array();
  }
}


class LinkData {
  function LinkData($data)
  {
    $this->id = $data->l_id;
    $this->link = substr($data->l_url, 0, 5) != 'http' ? "http://$data->l_url" : $data->l_url;
    $this->url = $data->l_url;
    $this->title = $data->l_title;
    $this->description = $data->l_description;
    $this->flag = $data->l_flag;
  }
}


class Link {
  function Link()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();

    $sql = "SELECT * FROM site_links, site_link_categories " .
           "WHERE l_categoryid=lc_id " .
           "ORDER BY lc_seq, l_seq";
    $result = mysql_query($sql, $connect);
    $lastcategoryid = -1;
    $this->data = array();
    while ($row = mysql_fetch_object($result)) {
      if ($row->lc_id != $lastcategoryid) {
        $lastcategoryid = $row->lc_id;
        $this->data[$row->lc_id] = new LinkCategory($row->lc_category);
      }
      $this->data[$row->lc_id]->info[] = new LinkData($row);
    }
  }
}
?>

<?php 
$linkList = new Link();

echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($linkList->data as $c => $l) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'><b>$l->category</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>";
  foreach ($l->info as $n => $i) {
    $line = "<a href='$i->link' target='_parent'>$i->title</a>: $i->description<br />";
    echo $line;
  }
  echo "  </td>\n" .
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
