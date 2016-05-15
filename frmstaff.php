<?php
include("path.inc");
$HEADING = "staff";
include("includes/frmheader.inc");
?>

<?php
class StaffData {
  function StaffData($data)
  {
    $this->id = $data->s_id;
    $this->name = format_name($data->m_first, $data->m_last);
    $this->office = $data->o_office;
    $this->about = $data->m_about;
    $this->image = $data->m_image;
  }
}


class Staff {
  function Staff()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();

    $lastoffice = '';
    $sql = "SELECT * FROM site_staff, site_members, site_offices " .
           "WHERE s_officeid=o_id " .
           "  AND s_memberid=m_id " .
           "  AND o_display='1' " .
           "ORDER BY o_seq, m_last, m_first";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new StaffData($row);
    }
  }
}
?>
<script language="JavaScript">
function toggle(id)
{
  var a = document.getElementById("a_" + id);
  var b = document.getElementById("b_" + id);
  if (a.style.display == "none") {
    a.style.display = "";
    b.style.display = "";
  }
  else {
    a.style.display = "none";
    b.style.display = "none";
  } 
}
</script>
<?php 
$staffList = new Staff();

echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
foreach ($staffList->data as $n => $s) {
  echo "<tr valign='top'>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='1' height='4' /><br /><img src='$PATH/images/arrow1.gif' onclick='toggle($s->id)' /></td>\n" .
       "  <td><img src='$PATH/images/spacer.gif' width='5' height='1' /></td>\n" .
       "  <td class='fg2' width='99%'><b>$s->name, $s->office</b></td>\n" .
       "  <td class='cellsm' align='right'><a href='contact.php?id=$s->id' target='_parent'>email</a></td>\n" .
       "</tr>\n";
  echo "<tr id='a_$s->id' style='display:' valign='top'>\n" .
       "  <td colspan='4'><img src='$PATH/images/spacer.gif' width='1' height='6' /></td>\n" .
       "</tr>\n";
  echo "<tr id='b_$s->id' style='display:' valign='top'>\n" .
       "  <td></td>\n" .
       "  <td></td>\n" .
       "  <td class='generalsm' colspan='2'>";
  if ($s->image && file_exists($IMAGE_PATH . '/' . $s->image)) {
    echo "<table align='left' border='0' cellpadding='0' cellspacing='0'><tr><td><img src='$IMAGE_PATH/$s->image' /></td><td><img src='images/spacer.gif' width='5' height='1' /></td></tr></table>";
  }
  echo "    $s->about" .
       "  </td>\n" .
       "</tr>\n";
  echo "<tr valign='top'>\n" .
       "  <td colspan='4'><img src='$PATH/images/spacer.gif' width='1' height='12' /></td>\n" .
       "</tr>\n";
}
echo "</table>\n";
?>
<?php
include("includes/frmfooter.inc");
?>
