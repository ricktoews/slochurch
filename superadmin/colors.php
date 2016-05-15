<?php
include("path.inc");
include("includes/header.inc");

if ($arg == "1") {
  $sql = "SELECT * FROM site_colors " .
         "WHERE c_id='$c_id'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $c_pagebgcolor = $row->c_pagebgcolor;
    $c_bgcolor = $row->c_bgcolor;
    $c_bodytext = $row->c_bodytext;
    $c_h1 = $row->c_h1;
    $c_h2 = $row->c_h2;
    $c_h3 = $row->c_h3;
    $c_h4 = $row->c_h4;
    $c_sidebg = $row->c_sidebg;
    $c_sidefg = $row->c_sidefg;
    $c_sidelink = $row->c_sidelink;
    $c_sidealink = $row->c_sidealink;
    $c_sidevlink = $row->c_sidevlink;
    $c_navtext = $row->c_navtext;
    $c_navlink = $row->c_navlink;
    $c_navalink = $row->c_navalink;
    $c_navvlink = $row->c_navvlink;
    $c_link = $row->c_link;
    $c_alink = $row->c_alink;
    $c_vlink = $row->c_vlink;
    $c_borcol = $row->c_borcol;
    $c_bg1 = $row->c_bg1;
    $c_fg1 = $row->c_fg1;
    $c_bg2 = $row->c_bg2;
    $c_fg2 = $row->c_fg2;
    $c_bg3 = $row->c_bg3;
    $c_fg3 = $row->c_fg3;
    $c_bg4 = $row->c_bg4;
    $c_fg4 = $row->c_fg4;
    $c_bg5 = $row->c_bg5;
    $c_fg5 = $row->c_fg5;
  }
}

?>
  <script language="JavaScript">
  function update() {
    var f = document.forms["data"];
    f.arg.value = "2";
    f.submit();
  }

  function viewSample() {
    var f = document.forms["data"];
    var sample;
    var params;
    params = "samp_bgcolor=" + f.c_bgcolor.value +
             "&samp_link=" + f.c_link.value +
             "&samp_alink=" + f.c_alink.value +
             "&samp_vlink=" + f.c_vlink.value +
             "&samp_borcol=" + f.c_borcol.value +
             "&samp_bg1=" + f.c_bg1.value +
             "&samp_fg1=" + f.c_fg1.value +
             "&samp_bg2=" + f.c_bg2.value +
             "&samp_fg2=" + f.c_fg2.value +
             "&samp_bg3=" + f.c_bg3.value +
             "&samp_fg3=" + f.c_fg3.value +
             "&samp_bg4=" + f.c_bg4.value +
             "&samp_fg4=" + f.c_fg4.value +
             "&samp_bg5=" + f.c_bg5.value +
             "&samp_fg5=" + f.c_fg5.value +
             "&samp_bodytext=" + f.c_bodytext.value;
    sample = open("color_sample.php?" + params, "sample", "screenX=50,screenY=50,top=50,left=50,width=600,height=300");
    sample.focus();
  }

  function getColors() {
    var f = document.forms["data"];
    f.action = "colors.php";
    f.arg.value = "1";
    f.submit(); 
  }
  </script>

<script language="JavaScript">
function showColorChip(bg, c) {
  var el = document.getElementById("sample_" + selectedText);
  var y = elemTop(el);
  var x = elemLeft(el);
  var chip = document.getElementById("chip_" + selectedText);
  chip.style.top = y + 2;
  chip.style.left = x + 2;
  chip.style.height = el.offsetHeight - 4;
  chip.style.width = el.offsetWidth - 4;
  chip.style.padding = 3;
  if (bg) {
    chip.style.backgroundColor = "#" + bg;
  }
  if (c) {
    chip.style.color = "#" + c;
    chip.innerHTML = "Text";
  }
}


function showTextSample(sample, bg, c) {
  var el = document.getElementById("sample_" + sample);
  var y = elemTop(el);
  var x = elemLeft(el);
  var sample = document.getElementById("chip_" + sample);
  sample.style.top = y + 2;
  sample.style.left = x + 2;
  sample.style.padding = 3;
  sample.style.color = "#" + c;
  sample.style.backgroundColor = "#" + bg;
  sample.innerHTML = "Text";
  sample.style.visibility = "visible";
}


function elemTop(el) {
  eltop = el.offsetTop;
  while (el.offsetParent.tagName != "BODY") {
    el = el.offsetParent;
    eltop += el.offsetTop;
  }
  return eltop;
}


function elemLeft(el) {
  elleft = el.offsetLeft;
  while (el.offsetParent.tagName != "BODY") {
    el = el.offsetParent;
    elleft += el.offsetLeft;
  }
  return elleft;
}


selectedField = null;
selectedBg = null;
selectedText = null;
function selectBgText(fld, bgfld, textfld) {
  selectedField = fld;
  selectedBg = bgfld;
  selectedText = textfld;
}


function saveColor(c) {
  var f = document.forms["data"];
  if (selectedField) {
    selectedField.value = c;
    selectedField.focus();
    updateColors();
  }
}


function updateColors() {
  var f = document.forms["data"];
  var bg = f.elements["c_" + selectedBg].value;
  var c = f.elements["c_" + selectedText].value;
  showColorChip(bg, c);
}


fglist = new Array();
fglist["bgcolor"] = new Array();
fglist["bgcolor"][0] = "bodytext";
fglist["bgcolor"][1] = "link";
fglist["bgcolor"][2] = "alink";
fglist["bgcolor"][3] = "vlink";
fglist["bgcolor"][4] = "navtext";
fglist["bgcolor"][5] = "navlink";
fglist["bgcolor"][6] = "navalink";
fglist["bgcolor"][7] = "navvlink";

fglist["sidebg"] = new Array();
fglist["sidebg"][0] = "sidefg";
fglist["sidebg"][1] = "sidelink";
fglist["sidebg"][2] = "sidealink";
fglist["sidebg"][3] = "sidevlink";
function updateMultColors() {
  var f = document.forms["data"];
  var bg = f.elements["c_" + selectedBg].value;
  var c;
  for (i = 0; i < fglist[selectedBg].length; i++) {
    selectedText = fglist[selectedBg][i];
    c = f.elements["c_" + selectedText].value;
    showColorChip(bg, c);
  }
}


function initFocus() {
  var f = document.forms[0];
  f.c_id.focus();
}

onload=initFocus;
</script>
<form name="data" method="post" action="save_colors.php">
<input type="hidden" name="arg" value="">
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="15">Colors</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="15">Select Area</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="15">
      <select name="c_id" onchange="getColors()">
      <option value="">Select</option>
<?php
$sql = "SELECT * FROM site_colors " .
       "ORDER BY c_id";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $selected = $c_id == $row->c_id ? "selected" : "";
  echo "<option $selected value='$row->c_id'>$row->c_tag</option>\n";
}
?>
      </select>
    </td>
  </tr>
<?php
if ($arg) {
?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <th class="fg5" colspan="15">
      <table bgcolor="#666666" border="0" cellpadding="0" cellspacing="1">
<?php
  $chip = array();
  $head = "<tr>";
  $tail = "</tr>\n";
  $inter = "";
  for ($r = 0; $r < 6; $r++) {
    for ($g = 0; $g < 6; $g++) {
      for ($b = 0; $b < 6; $b++) {
        $hex = sprintf("%02x%02x%02x", $r*51, $g*51, $b*51);
        $chip[] = "<td onclick='saveColor(\"$hex\")' style='background-color:#$hex; width:10; height:10'></td>";
      }
      if (sizeof($chip) >= 108) {
        $colorrow = $head . implode($inter, $chip) . $tail;
      echo $colorrow;
        $chip = array();
      }
    }
  }
?>
      </table>
    </th>
  </tr>
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="15">Table Colors</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <th class="fg2" colspan="3">Heading 1</th>
    <th class="fg2" colspan="3">Heading 2</th>
    <th class="fg2" colspan="3">Heading 3</th>
    <th class="fg2" colspan="3">Heading 4</th>
    <th class="fg2" colspan="3">Heading 5</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" id="c_bg1" name="c_bg1" value="<?php echo $c_bg1; ?>" onfocus="selectBgText(this, 'bg1', 'fg1')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="c_fg1" name="c_fg1" value="<?php echo $c_fg1; ?>" onfocus="selectBgText(this, 'bg1', 'fg1')" onchange="updateColors()" size="6"></td>
    <td id="sample_fg1" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_bg2" name="c_bg2" value="<?php echo $c_bg2; ?>" onfocus="selectBgText(this, 'bg2', 'fg2')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="c_fg2" name="c_fg2" value="<?php echo $c_fg2; ?>" onfocus="selectBgText(this, 'bg2', 'fg2')" onchange="updateColors()" size="6"></td>
    <td id="sample_fg2" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_bg3" name="c_bg3" value="<?php echo $c_bg3; ?>" onfocus="selectBgText(this, 'bg3', 'fg3')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="c_fg3" name="c_fg3" value="<?php echo $c_fg3; ?>" onfocus="selectBgText(this, 'bg3', 'fg3')" onchange="updateColors()" size="6"></td>
    <td id="sample_fg3" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_bg4" name="c_bg4" value="<?php echo $c_bg4; ?>" onfocus="selectBgText(this, 'bg4', 'fg4')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="c_fg4" name="c_fg4" value="<?php echo $c_fg4; ?>" onfocus="selectBgText(this, 'bg4', 'fg4')" onchange="updateColors()" size="6"></td>
    <td id="sample_fg4" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_bg5" name="c_bg5" value="<?php echo $c_bg5; ?>" onfocus="selectBgText(this, 'bg5', 'fg5')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="c_fg5" name="c_fg5" value="<?php echo $c_fg5; ?>" onfocus="selectBgText(this, 'bg5', 'fg5')" onchange="updateColors()" size="6"></td>
    <td id="sample_fg5" class="fg5"></td>
  </tr>
</table>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="10">Main Colors</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Main Background</td>
    <td class="fg2">Main Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Active Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Visited Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Border</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" id="c_bgcolor" name="c_bgcolor" value="<?php echo $c_bgcolor; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'bodytext')" onchange="updateMultColors()" /></td>
    <td class="fg5"><input type="text" id="c_bodytext" name="c_bodytext" value="<?php echo $c_bodytext; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'bodytext')" onchange="updateColors()" /></td>
    <td id="sample_bodytext" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_link" name="c_link" value="<?php echo $c_link; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'link')" onchange="updateColors()" /></td>
    <td id="sample_link" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_alink" name="c_alink" value="<?php echo $c_alink; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'alink')" onchange="updateColors()" /></td>
    <td id="sample_alink" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_vlink" name="c_vlink" value="<?php echo $c_vlink; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'vlink')" onchange="updateColors()" /></td>
    <td id="sample_vlink" class="fg5"></td>
    <td class="fg5"><input type="text" name="c_borcol" value="<?php echo $c_borcol; ?>" size="6"></td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2"></td>
    <td class="fg2">H1</td>
    <td class="fg2">Sample</td>
    <td class="fg2">H2</td>
    <td class="fg2">Sample</td>
    <td class="fg2">H3</td>
    <td class="fg2">Sample</td>
    <td class="fg2">H4</td>
    <td class="fg2">Sample</td>
    <td class="fg2"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"></td>
    <td class="fg5"><input type="text" id="c_h1" name="c_h1" value="<?php echo $c_h1; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'h1')" onchange="updateColors()" /></td>
    <td id="sample_h1" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_h2" name="c_h2" value="<?php echo $c_h2; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'h2')" onchange="updateColors()" /></td>
    <td id="sample_h2" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_h3" name="c_h3" value="<?php echo $c_h3; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'h3')" onchange="updateColors()" /></td>
    <td id="sample_h3" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_h4" name="c_h4" value="<?php echo $c_h4; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'h4')" onchange="updateColors()" /></td>
    <td id="sample_h4" class="fg5"></td>
    <td class="fg5"></td>
  </tr>
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="10">Bottom Navigation Colors</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2"></td>
    <td class="fg2">Nav Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Nav Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Nav Active Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Nav Visited Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Page Background</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"></td>
    <td class="fg5"><input type="text" id="c_navtext" name="c_navtext" value="<?php echo $c_navtext; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'navtext')" onchange="updateColors()" /></td>
    <td id="sample_navtext" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_navlink" name="c_navlink" value="<?php echo $c_navlink; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'navlink')" onchange="updateColors()" /></td>
    <td id="sample_navlink" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_navalink" name="c_navalink" value="<?php echo $c_navalink; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'navalink')" onchange="updateColors()" /></td>
    <td id="sample_navalink" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_navvlink" name="c_navvlink" value="<?php echo $c_navvlink; ?>" size="6" onfocus="selectBgText(this, 'bgcolor', 'navvlink')" onchange="updateColors()" /></td>
    <td id="sample_navvlink" class="fg5"></td>
    <td class="fg5"><input type="text" name="c_pagebgcolor" value="<?php echo $c_pagebgcolor; ?>" size="6"></td>
  </tr>
</table>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="9">Sidebar Colors</th>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Background</td>
    <td class="fg2">Text</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Active Link</td>
    <td class="fg2">Sample</td>
    <td class="fg2">Visited Link</td>
    <td class="fg2">Sample</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" id="c_sidebg" name="c_sidebg" value="<?php echo $c_sidebg; ?>" size="6" onfocus="selectBgText(this, 'sidebg', 'sidefg')" onchange="updateMultColors()" /></td>
    <td class="fg5"><input type="text" id="c_sidefg" name="c_sidefg" value="<?php echo $c_sidefg; ?>" size="6" onfocus="selectBgText(this, 'sidebg', 'sidefg')" onchange="updateColors()" /></td>
    <td id="sample_sidefg" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_sidelink" name="c_sidelink" value="<?php echo $c_sidelink; ?>" size="6" onfocus="selectBgText(this, 'sidebg', 'sidelink')" onchange="updateColors()" /></td>
    <td id="sample_sidelink" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_sidealink" name="c_sidealink" value="<?php echo $c_sidealink; ?>" size="6" onfocus="selectBgText(this, 'sidebg', 'sidealink')" onchange="updateColors()" /></td>
    <td id="sample_sidealink" class="fg5"></td>
    <td class="fg5"><input type="text" id="c_sidevlink" name="c_sidevlink" value="<?php echo $c_sidevlink; ?>" size="6" onfocus="selectBgText(this, 'sidebg', 'sidevlink')" onchange="updateColors()" /></td>
    <td id="sample_sidevlink" class="fg5"></td>
  </tr>
</table>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg5; ?>">
    <td colspan="6" align="center">
      <input type=button value="Update" onclick="update()">
    </td>
  </tr>
<?php
}
?>
</table>
</form>
<div id="chip_fg1" style="position:absolute; visibility:visible"></div>
<div id="chip_fg2" style="position:absolute; visibility:visible"></div>
<div id="chip_fg3" style="position:absolute; visibility:visible"></div>
<div id="chip_fg4" style="position:absolute; visibility:visible"></div>
<div id="chip_fg5" style="position:absolute; visibility:visible"></div>
<div id="chip_bodytext" style="position:absolute; visibility:visible"></div>
<div id="chip_link" style="position:absolute; visibility:visible"></div>
<div id="chip_alink" style="position:absolute; visibility:visible"></div>
<div id="chip_vlink" style="position:absolute; visibility:visible"></div>
<div id="chip_h1" style="position:absolute; visibility:visible"></div>
<div id="chip_h2" style="position:absolute; visibility:visible"></div>
<div id="chip_h3" style="position:absolute; visibility:visible"></div>
<div id="chip_h4" style="position:absolute; visibility:visible"></div>
<div id="chip_navtext" style="position:absolute; visibility:visible"></div>
<div id="chip_navlink" style="position:absolute; visibility:visible"></div>
<div id="chip_navalink" style="position:absolute; visibility:visible"></div>
<div id="chip_navvlink" style="position:absolute; visibility:visible"></div>
<div id="chip_sidefg" style="position:absolute; visibility:visible"></div>
<div id="chip_sidelink" style="position:absolute; visibility:visible"></div>
<div id="chip_sidealink" style="position:absolute; visibility:visible"></div>
<div id="chip_sidevlink" style="position:absolute; visibility:visible"></div>
<?php
if ($arg) {
?>
<script language="JavaScript">
function initScreen() {
  var f = document.forms["data"];
  selectedText = "fg1";
  showColorChip(f.c_bg1.value, f.c_fg1.value);
  selectedText = "fg2";
  showColorChip(f.c_bg2.value, f.c_fg2.value);
  selectedText = "fg3";
  showColorChip(f.c_bg3.value, f.c_fg3.value);
  selectedText = "fg4";
  showColorChip(f.c_bg4.value, f.c_fg4.value);
  selectedText = "fg5";
  showColorChip(f.c_bg5.value, f.c_fg5.value);
  selectedText = "bodytext";
  showColorChip(f.c_bgcolor.value, f.c_bodytext.value);
  selectedText = "link";
  showColorChip(f.c_bgcolor.value, f.c_link.value);
  selectedText = "alink";
  showColorChip(f.c_bgcolor.value, f.c_alink.value);
  selectedText = "vlink";
  showColorChip(f.c_bgcolor.value, f.c_vlink.value);
  selectedText = "h1";
  showColorChip(f.c_bgcolor.value, f.c_h1.value);
  selectedText = "h2";
  showColorChip(f.c_bgcolor.value, f.c_h2.value);
  selectedText = "h3";
  showColorChip(f.c_bgcolor.value, f.c_h3.value);
  selectedText = "h4";
  showColorChip(f.c_bgcolor.value, f.c_h4.value);
  selectedText = "navtext";
  showColorChip(f.c_bgcolor.value, f.c_navtext.value);
  selectedText = "navlink";
  showColorChip(f.c_bgcolor.value, f.c_navlink.value);
  selectedText = "navalink";
  showColorChip(f.c_bgcolor.value, f.c_navalink.value);
  selectedText = "navvlink";
  showColorChip(f.c_bgcolor.value, f.c_navvlink.value);
  selectedText = "sidefg";
  showColorChip(f.c_sidebg.value, f.c_sidefg.value);
  selectedText = "sidelink";
  showColorChip(f.c_sidebg.value, f.c_sidelink.value);
  selectedText = "sidealink";
  showColorChip(f.c_sidebg.value, f.c_sidealink.value);
  selectedText = "sidevlink";
  showColorChip(f.c_sidebg.value, f.c_sidevlink.value);
}

onload=initScreen;
</script>
<?php
}
?>

<?php
include("includes/footer.inc");
?>
