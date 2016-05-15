<?php
include("path.inc");
$HEADING = "listen";
include("includes/frmheader.inc");

class MessageInfo {
  function MessageInfo($label, $info)
  {
    $this->label = $label;
    $this->info = $info;
  }
}


class MessageWeek {
  function MessageWeek($id, $description, $image, $audio)
  {
    global $MESSAGE_PATH;
    $this->id = $id;
    if ($audio && file_exists("$MESSAGE_PATH/$audio")) {
      $audiopre = "<a href=\"javascript:playAudio('$id', 'M')\">";
      $audiopost = "</a>";
    }
    else {
      $audiopre = "";
      $audiopost = "";
    }
    if (!$description) {
      $description = 'No title supplied';
    }
    $this->description = $audiopre . $description . $audiopost;
    $this->image = $image;
    $this->info = array();
  }
}


class Message {
  function Message()
  {
    $connect = $_REQUEST['connect'];
    $nextSabbath = date("Y-m-d", mktime() + 86400 * (6 - date("w")));
    $startdate = date("Y-m-d", strtotime($nextSabbath) - 86400 * 28);
    $weeksback = -4;
    $sql = "SELECT * FROM site_schedule_weeks, site_schedule_information, site_schedule_labels " .
           "WHERE si_date=sw_date " .
           "  AND si_slid=sl_id " .
           "  AND sw_date >= '$startdate' " .
           "  AND sw_date < '$nextSabbath' " .
           "ORDER BY sw_date, sl_seq";
    $result = mysql_query($sql, $connect);
    $lastdate = '';
    $this->data = array();
    while ($row = mysql_fetch_object($result)) {
      $date = $row->sw_date;
      if ($date != $lastdate) {
        $lastdate = $date;
        $this->data[$date] = new MessageWeek($row->sw_id, $row->sw_description, $row->sw_image, $row->sw_audio);
      }
      if ($row->sl_label == 'Speaker') {
        $this->data[$date]->info[] = new MessageInfo($row->sl_label, $row->si_info);
      }
    }
  }
}


class PodcastInfo {
  function PodcastInfo($id, $podcast, $title, $description, $date, $seq)
  {
    global $PODCAST_PATH;
    $this->id = $id;
    $this->podcast = $podcast;
    if ($podcast && file_exists("$PODCAST_PATH/$podcast")) {
      $podcastpre = "<a href=\"javascript:playAudio('$id', 'P')\">";
      $podcastpost = "</a>";
    }
    else {
      $podcastpre = "";
      $podcastpost = "";
    }
    $this->title = $podcastpre . $title . $podcastpost;
    $this->date = $date;
    $this->seq = $seq;
  }
}


class Podcast {
  function Podcast()
  {
    $connect = $_REQUEST['connect'];
    $sql = "SELECT * FROM site_podcasts " .
           "ORDER BY pc_id";
    $result = mysql_query($sql, $connect);
    $lastdate = '';
    $this->data = array();
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new PodcastInfo($row->pc_id, $row->pc_podcast, $row->pc_title, $row->pc_description, $row->pc_date, $row->pc_seq);
    }
  }
}

$messageList = new Message();
$podcastList = new Podcast();
?>
<script language="JavaScript">
function playAudio(id,type) {
  var messagewin;
  messagewin = open("playaudio.php?id=" + id + "&type=" + type, "audio", "width=500,height=300,top=50,left=50,screenY=60,screenX=50,location,menubar,statusbar");
}
</script>
<p><b>Messages</b></p>
<table border="0" cellpadding="0" cellspacing="0">
<?php
foreach ($messageList->data as $d => $m) {
  $fmtdate = date("F j, Y", strtotime($d));

  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'><b>$fmtdate - $m->description</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm'>";
  foreach ($m->info as $l => $i) {
    $line = "<b>$i->label</b>: $i->info<br />";
    echo $line;
  }
  echo "  </td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='24' /></td>\n" .
       "</tr>\n";
}
?>
</table>
<br />
<p><b>Podding Along</b></p>
<table border="0" cellpadding="0" cellspacing="0">
<?php
foreach ($podcastList->data as $n => $p) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2'>$p->title</b></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='3'><img src='$PATH/images/spacer.gif' width='1' height='24' /></td>\n" .
       "</tr>\n";
}
?>
</table>
<?php
include("includes/frmfooter.inc");
?>
