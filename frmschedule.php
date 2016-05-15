<?php
include("path.inc");
$HEADING = "schedule";
include("includes/frmheader.inc");

class ScheduleInfo {
  function ScheduleInfo($label, $info)
  {
    $this->label = $label;
    $this->info = $info;
  }
}


class ScheduleWeek {
  function ScheduleWeek($description, $image)
  {
    $this->description = $description;
    $this->image = $image;
    $this->info = array();
  }
}


class Schedule {
  function Schedule()
  {
    $TODAY = $_REQUEST['TODAY'];
    $TODAY = '2008-06-01';
    $connect = $_REQUEST['connect'];
    $sql = "SELECT * FROM site_schedule_weeks, site_schedule_information, site_schedule_labels " .
           "WHERE si_date=sw_date " .
           "  AND si_slid=sl_id " .
           "  AND sw_date >= '$TODAY' " .
           "ORDER BY sw_date, sl_seq";
    $result = mysql_query($sql, $connect);
    $lastdate = '';
    $this->data = array();
    while ($row = mysql_fetch_object($result)) {
      $date = $row->sw_date;
      if ($date != $lastdate) {
        $lastdate = $date;
        $this->data[$date] = new ScheduleWeek($row->sw_description, $row->sw_image);
      }
      $this->data[$date]->info[] = new ScheduleInfo($row->sl_label, $row->si_info);
    }
  }
}

$scheduleList = new Schedule();

echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
foreach ($scheduleList->data as $d => $s) {
  $fmtdate = date("F j, Y", strtotime($d));

  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'><b>$fmtdate - $s->description</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>";
  if ($s->image && file_exists($SCHEDULE_PATH . '/' . $s->image)) {
    echo "<table align='left' border='0' cellpadding='0' cellspacing='0'><tr><td><img src='$SCHEDULE_PATH/$s->image' /></td><td><img src='images/spacer.gif' width='10' height='1' /></td></tr></table>";
  }
  foreach ($s->info as $l => $i) {
    $line = "<b>$i->label</b>: $i->info<br />";
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
