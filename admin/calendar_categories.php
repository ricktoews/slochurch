<?php
include("path.inc");
include("includes/header.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$new = $_REQUEST["new"];
$inuse = $_REQUEST["inuse"];
$subid = $_REQUEST["subid"];
$followid = $_REQUEST["followid"];


// Retrieve a category.
if ($arg == "1") {
  $sql = "SELECT * FROM site_calendar_categories " .
         "WHERE cc_id='$id'";
  $result = check_mysql($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $category = $row->cc_category;
    $background_color = $row->cc_background_color;
    $color = $row->cc_color;
    $subid = $row->cc_subid;
    $seq = $row->cc_seq;
  }
  $sql = "SELECT MAX(cc_seq) AS follow FROM site_calendar_categories " .
         "WHERE cc_seq < '$seq' " .
         "  AND cc_subid='$subid'";
  $result = mysql_query($sql, $connect);
  if ($row = mysql_fetch_object($result)) {
    $follow = $row->follow;
  }
}
?>
<script language="JavaScript">
categories = new Array();
<?php
$ndx1 = -1;
$ndx2 = 0;

$sql = "SELECT * FROM site_calendar_categories " .
       "ORDER BY cc_subid, cc_seq ";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->cc_subid != $ndx1) {
    $ndx1 = $row->cc_subid;
    echo "categories[$ndx1] = new Array();\n";
    $ndx2 = 0;
  }
  echo "categories[$ndx1][$ndx2] = '" . $row->cc_id . "|" . addslashes($row->cc_category) . "';\n";
  $ndx2++;
}
?>

function followList() {
  var f = document.forms[0];
  var ndx1 = f.subid.options[f.subid.selectedIndex].value;
  var cat = new Array();
  f.followid.options.length = 1;
  if (categories[ndx1]) {
    for (var i = 0; i < categories[ndx1].length; i++) {
      cat = categories[ndx1][i].split("|");
      f.followid.options[i+1] = new Option(cat[1], cat[0]);
    }
  }
}

function checkCategory() {
  var f = document.forms[0];
  if (f.subid.options[f.subid.selectedIndex].value == f.id.options[f.id.selectedIndex].value) {
    alert("A category cannot be a subcategory within itself.");
    f.subid.selectedIndex = 0;
  }
}

function getCategory() {
  var f = document.forms[0];
  f.arg.value = "1";
  f.action = "calendar_categories.php";
  f.submit();
}

function newCategory() {
  location = "calendar_categories.php";
}

function updateCategory() {
  var f = document.forms[0];
  f.arg.value = "2";
  f.submit();
}

function deleteCategory() {
  if (confirm("Are you sure you want to delete this?")) {
    var f = document.forms["data"];
    f.arg.value = "4";
    f.submit();
  }
}


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
  var bg = f.elements["cc_" + selectedBg].value;
  var c = f.elements["cc_" + selectedText].value;
  showColorChip(bg, c);
}


function initFocus() {
  var f = document.forms["data"];
  f.category.focus();
}

onload=initFocus;
</script>
<form name="data" method="post"  action="save_calendar_categories.php">
<input type="hidden" name="arg" value="" />
<?php if ($new) { ?>
<input type="hidden" name="new" value="1" />
<?php } ?>
<table bgcolor="<?php echo $borcol; ?>" border="0" cellpadding="3" cellspacing="1" width="100%">
  <tr bgcolor="<?php echo $bg1; ?>">
    <th class="fg1" colspan="3">Categories</th>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <th class="fg5" colspan="3">
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
<?php if ($inuse) { ?>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      The category you selected is in use and so cannot be deleted.
    </td>
  </tr>
<?php } ?>
<?php if (!$new) { ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2" colspan="3">Calendar Categories</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5" colspan="3">
      <select name="id" onchange="getCategory()">
      <option value="">Select</option>
<?php
$match = array($id => 1);
show_subs(0, "", true);
?>
      </select>
      <input type="button" value="New" onclick="newCategory()">
    </td>
  </tr>
<?php } ?>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2">Category</td>
    <td class="fg2">Categorize Within</td>
    <td class="fg2">Category This Should Follow</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" name="category" value="<?php echo $category; ?>" size="20" /></td>
    <td class="fg5">
      <input type="hidden" name="oldsubid" value="<?php echo $subid; ?>">
      <select name="subid" onchange="checkCategory(); followList()">
      <option value="0">Top Level</option>
<?php
$match = array($subid => 1);
show_subs(0, "", true);
?>
      </select>
    </td>
    <td class="fg5">
      <select name="followid">
      <option value="0">Top of the list</option>
<?php
$sql = "SELECT * FROM site_calendar_categories " .
       "WHERE cc_subid='$subid' " .
       "ORDER BY cc_seq";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  if ($row->cc_id == $id) {
    continue;
  }
  $selected = $row->cc_seq == $follow ? "selected" : "";
  echo "<option $selected value='$row->cc_id'>$row->cc_category</option>\n";
}
?>
      </select>

    </td>
  </tr>
  <tr bgcolor="<?php echo $bg2; ?>">
    <td class="fg2"><?php echo help_tag("Background Color Code"); ?></td>
    <td class="fg2"><?php echo help_tag("Font Color Code"); ?></td>
    <td class="fg2">Sample</td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td class="fg5"><input type="text" id="cc_background_color" name="cc_background_color" value="<?php echo $background_color; ?>" onfocus="selectBgText(this, 'background_color', 'color')" onchange="updateColors()" size="6" /></td>
    <td class="fg5"><input type="text" id="cc_color" name="cc_color" value="<?php echo $color; ?>" onfocus="selectBgText(this, 'background_color', 'color')" onchange="updateColors()" size="6"></td>
    <td id="sample_color" class="fg5"></td>
  </tr>
  <tr bgcolor="<?php echo $bg5; ?>">
    <td align="center" class="fg5" colspan="3">
<?php if (!$arg) { ?>
      <input type=button value="Add" onclick="updateCategory()">
<?php } else { ?>
      <input type=button value="Update" onclick="updateCategory()">
      <input type=button value="Delete" onclick="deleteCategory()">
<?php } ?>
    </td>
  </tr>
</table>
</form>
</div>
<div id="chip_color" style="position:absolute; visibility:visible"></div>
<?php
if ($arg) {
?>
<script language="JavaScript">
function initScreen() {
  var f = document.forms["data"];
  selectedText = "color";
  showColorChip(f.cc_background_color.value, f.cc_color.value);
}

onload=initScreen;
</script>
<?php
}
?>
</body>
</html>

