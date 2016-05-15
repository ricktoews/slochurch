<?php
include("path.inc");
include("includes/header.inc");
?>
<script language="JavaScript">
function updateServices() {
  var f = document.forms["data"];
  f.arg.value = "2";
  f.submit();
}
</script>
<form name="data" method="post" action="save_services.php">
<input type="hidden" name="arg" value="" />
<div align="center">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th colspan="5" class="fg1"><?php echo $PAGETITLE; ?></th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Day</td>
    <td class="fg2">Begin Time</td>
    <td class="fg2">End Time</td>
    <td class="fg2">Description</td>
    <td class="fg2">Delete</td>
  </tr>
<?php
$r = 0;
$sql = "SELECT * FROM site_services " .
       "ORDER BY s_day, s_begin, s_end";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $id = $row->s_id;
  $day = $row->s_day;
  list($bhr, $bmn) = explode(':', $row->s_begin);
  $bap = $bhr >= 12 ? 'p' : 'a';
  $bhr = 1 * ($bhr > 12 ? $bhr - 12 : $bhr);
  $bhm = $row->s_begin ? "$bhr:$bmn" : "";
  list($ehr, $emn) = explode(':', $row->s_end);
  $eap = $ehr >= 12 ? 'p' : 'a';
  $ehr = 1 * ($ehr > 12 ? $ehr - 12 : $ehr);
  $ehm = $row->s_end ? "$ehr:$emn" : "";
  $description = $row->s_description;
?>
  <tr valign=top bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <input type="hidden" name="id_<?php echo $r; ?>" value="<?php echo $id; ?>" />
      <select name="day_<?php echo $r; ?>">
      <option value="">Select</option>
<?php
  for ($i = 1; $i <= 7; $i++) {
    $selected = $i == $day ? "selected" : "";
    echo "<option $selected value='$i'>" . $WEEKDAYS[$i-1] . "</option>\n"; 
  }
?>
      </select>
    </td>
    <td class="fg5">
      <input type="text" name="bhm_<?php echo $r; ?>" value="<?php echo $bhm; ?>" size="5" />
<?php $checked = $bap == 'a' ? 'checked' : ''; ?>
      <input <?php echo $checked; ?> type="radio" name="bap_<?php echo $r; ?>" value="a" /> am /
<?php $checked = $bap == 'p' ? 'checked' : ''; ?>
      <input <?php echo $checked; ?> type="radio" name="bap_<?php echo $r; ?>" value="p" /> pm
    </td>
    <td class="fg5">
      <input type="text" name="ehm_<?php echo $r; ?>" value="<?php echo $ehm; ?>" size="5" />
<?php $checked = $eap == 'a' ? 'checked' : ''; ?>
      <input <?php echo $checked; ?> type="radio" name="eap_<?php echo $r; ?>" value="a" /> am /
<?php $checked = $eap == 'p' ? 'checked' : ''; ?>
      <input <?php echo $checked; ?> type="radio" name="eap_<?php echo $r; ?>" value="p" /> pm
    </td>
    <td class="fg5"><textarea name="description_<?php echo $r; ?>" rows="2" cols="60" wrap="physical"><?php echo $description; ?></textarea></td>
    <td class="fg5"><input type="checkbox" name="del_<?php echo $r; ?>" value="1" /></td>
  </tr>
<?php
  $r++;
}
$max_r = $r + 3;
while ($r < $max_r) {
?>
  <tr valign=top bgcolor="<?php echo $bg5; ?>">
    <td class="fg5">
      <select name="day_<?php echo $r; ?>">
      <option value="">Select</option>
<?php
  for ($i = 1; $i <= 7; $i++) {
    echo "<option value='$i'>" . $WEEKDAYS[$i-1] . "</option>\n"; 
  }
?>
      </select>
    </td>
    <td class="fg5">
      <input type="text" name="bhm_<?php echo $r; ?>" value="" size="5" />
      <input type="radio" name="bap_<?php echo $r; ?>" value="a" /> am /
      <input type="radio" name="bap_<?php echo $r; ?>" value="p" /> pm
    </td>
    <td class="fg5">
      <input type="text" name="ehm_<?php echo $r; ?>" value="" size="5" />
      <input type="radio" name="eap_<?php echo $r; ?>" value="a" /> am /
      <input type="radio" name="eap_<?php echo $r; ?>" value="p" /> pm
    </td>
    <td class="fg5"><textarea name="description_<?php echo $r; ?>" rows="2" cols="60" wrap="physical"></textarea></td>
    <td class="fg5">&nbsp;</td>
  </tr>
<?php
  $r++;
}
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align=center colspan="5">
      <input type="button" value="Update" onclick="updateServices()" />
    </td>
  </tr>
</table>
</div>
<input type="hidden" name="max_rows" value="<?php echo $r; ?>">
</form>
<?php
include("includes/footer.inc");
?>
