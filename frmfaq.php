<?php
include("path.inc");
$HEADING = "faq";
include("includes/frmheader.inc");
?>

<?php
class FAQData {
  function FAQData($data)
  {
    $this->id = $data->faq_id;
    $this->question = $data->faq_question;
    $this->answer = $data->faq_answer;
  }
}


class FAQ {
  function FAQ()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();

    $lastoffice = '';
    $sql = "SELECT * FROM site_faq " .
           "ORDER BY faq_seq";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new FAQData($row);
    }
  }
}
?>

<?php 
$FAQList = new FAQ();

echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($FAQList->data as $n => $f) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='cell'><a href='#q_$n'>$f->question</a></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
}
echo "</table>\n";
echo "<br /><br />\n";
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($FAQList->data as $n => $f) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'><a name='q_$n'></a><b>$f->question</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>$f->answer</td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='3' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='cellsm' align='right'><a href='#top'>top</a></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='12' /></td>\n" .
       "</tr>\n";
}
echo "</table>\n";
?>
<?php
include("includes/frmfooter.inc");
?>
