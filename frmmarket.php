<?php
include("path.inc");
$HEADING = "farmers_market";
include("includes/frmheader.inc");

class TriviaInfo {
  function TriviaInfo($category, $question)
  {
    $this->category = $category;
    $this->question = $question;
  }
}


class TriviaImages {
  function TriviaImages($name)
  {
    $this->name = $name;
  }
}


class TriviaWeek {
  function TriviaWeek()
  {
    $this->info = array();
    $this->images = array();
  }
}


class Trivia {
  function Trivia()
  {
    $MARKET_PATH = $_REQUEST['MARKET_PATH'];
    $connect = $_REQUEST['connect'];
    $TODAY = $_REQUEST['TODAY'];
    $sql = "SELECT MIN(bt_date) AS current FROM site_bibletrivia_weeks " .
           "WHERE bt_date >= '$TODAY'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $CURRENT = $row->current;
    }
    $sql = "SELECT * FROM site_bibletrivia_weeks, site_bibletrivia_questions, site_bibletrivia_categories " .
           "WHERE bq_date=bt_date " .
           "  AND bq_bcid=bc_id " .
           "  AND bt_date='$CURRENT' " .
           "ORDER BY bc_seq";
    $result = mysql_query($sql, $connect);
    $lastdate = '';
    $this->data = array();
    while ($row = mysql_fetch_object($result)) {
      $date = $row->bt_date;
      if ($date != $lastdate) {
        $lastdate = $date;
        $this->data[$date] = new TriviaWeek();
        if ($row->bt_image1 && file_exists("$MARKET_PATH/$row->bt_image1")) {
          $this->data[$date]->images[] = new TriviaImages($row->bt_image1);
        }
        if ($row->bt_image2 && file_exists("$MARKET_PATH/$row->bt_image2")) {
          $this->data[$date]->images[] = new TriviaImages($row->bt_image2);
        }
        if ($row->bt_image3 && file_exists("$MARKET_PATH/$row->bt_image3")) {
          $this->data[$date]->images[] = new TriviaImages($row->bt_image3);
        }
      }
      $this->data[$date]->info[] = new TriviaInfo($row->bc_category, $row->bq_question);
    }
  }
}

$triviaList = new Trivia();
?>

<?php
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($triviaList->data as $d => $t) {
  $fmtdate = date("l, F j, Y", strtotime($d));
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2' colspan='2'><b>for $fmtdate</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='4'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td>\n";
  if (sizeof($t->images) > 0) {
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    foreach ($t->images as $n => $img) {
      echo "  <tr><td><img src='$MARKET_PATH/$img->name' /></td><td><img src='images/spacer.gif' width='10' height='1' /></td></tr>";
      echo "  <tr><td colspan='2'><img src='images/spacer.gif' width='1' height='6' /></td></tr>";
    }
    echo "</table>\n";
  }
  echo "  </td>\n" .
       "  <td>\n";
  foreach ($t->info as $l => $q) {
    $line = "<p><i class='cellsm'>$q->category</i><br /><span class='fg3'>$q->question</span></p>";
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
<p class="general">Come and share your answers with us (or find out what the answers are) at our booth at the Farmers' Market each Thursday.</p>
<?php
include("includes/frmfooter.inc");
?>
