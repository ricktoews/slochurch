<?php
$today = date("Ymd");
$monthdays = "312831303130313130313031";
$monthname = array("January",
                   "February",
                   "March",
                   "April",
                   "May",
                   "June",
                   "July",
                   "August",
                   "September",
                   "October",
                   "November",
                   "December");

function drawMonth($month, $year, $datewidth, $dateheight) {
  global $connect;
  global $details;
  global $borcol, $bg2, $bg1, $bg4, $bg3, $bg5;
  global $monthdays, $monthname;
  global $today;

  if ($datewidth <= 0) {
    $datewidth = 100;
  }
  if ($dateheight <= 0) {
    $dateheight = 30;
  }

  $days = substr($monthdays, ($month-1)*2, 2);
  $blanks = date("w", mktime(0, 0, 0, $month, 1, $year));
  $rows = ceil(($days + $blanks) / 7);

  $d = 1;
  $mname = $monthname[$month-1];
  echo "
    <tr bgcolor='$bg1'>
      <th colspan='7' class='fg1'>$mname, $year</th>
    </tr>
    <tr bgcolor='$bg2'>
      <th class='cell'>Sun<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Mon<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Tue<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Wed<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Thu<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Fri<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
      <th class='cell'>Sat<br><img src='$PATH/images/spacer.gif' width='$datewidth' height='1'></th>
    </tr>
  ";
  for ($i = 0; $i < $rows; $i++) {
    echo "<tr valign=top>";
    for ($j = 0; $j < 7; $j++) {
      $eventCell = 0;
      $dateCell = 0;
      if ($i * 7 + $j >= $blanks && $d <= $days) {
        $thisdate = date("Ymd", mktime(0,0,0,$month,$d,$year));
        if ($thisdate >= $today) {
          $obsolete = 0;
          $day = "00$d";
          $day = substr($day, strlen($day)-2, 2);
          $sql = "SELECT * FROM ci_calendar " .
                 " WHERE year='$year' " .
                 "   AND month='$month' " .
                 "   AND day='$day' " .
                 "   AND status<>'U' ";
          $result = mysql_query($sql, $connect);
          $row = mysql_fetch_object($result);
        }
        else {
          $obsolete = 1;
          $row = null;
        }
        if ($row) {
          $bg = $bg4;
          $eventCell = 1;
// Write a cell with an event date in it.
    ;     echo "  <td bgcolor='$bg' class='cell' align='right' width='$datewidth'><img src='$PATH/images/spacer.gif' width='1' height='$dateheight' align='left'>";
          echo "<a href='javascript:showItem(\"$year\", \"$month\", \"$day\")'>$d</a>";
          echo "<p>";
          $legends = "";
          do {
            $categoryid = $row->categoryid ? $row->categoryid : "*";
            if ($row->status == "C") {
              $categoryid = "($categoryid)";
            } 
            $legends .= ", " . $categoryid;
          } while ($row = mysql_fetch_object($result));
          $legends = substr($legends, 2, strlen($legends) - 2);
          echo "$legends";
        }

// Write a cell with no event date in it.
        else {
          $cell = $obsolete ? "pastdate" : "cell";
          $dateCell = 1;
          echo "
            <td bgcolor='$bg3' class='$cell' align='right' width='$datewidth'><img src='$PATH/images/spacer.gif' width='1' height='$dateheight' align='left'>$d
          ";
        }
        if (!$obsolete) {
          echo "<p><a href='javascript:openEdit(\"$year\", \"$month\", \"$day\")'>Add Item</a>";
        }
        echo "</td>";
        $d++;
      }

// Write a blank cell.
      else {
        echo "
          <td bgcolor='$bg5'><img src='$PATH/images/spacer.gif' width=1 height='$dateheight' align='left'></td>
        ";
      }
    }
    echo "
      </tr>
    ";
  }
}
?>
