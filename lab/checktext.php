<?php
include('path.inc');
include("$PATH/includes/lib.inc");

$bugger = chr(146);
printf("Character 146: %s<br />", chr(146));
$sql = "SELECT ms_content FROM site_mailing_sections " .
       "WHERE ms_mailid=28;";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  printf("<p>Content: %s</p>", $row->ms_content);
  $n = 51;
  printf("Character %d: %s (%02x)<br /><br /><br />", $n, substr($row->ms_content, $n-1, 1), ord(substr($row->ms_content, $n-1, 1)));
  if (preg_match("/$bugger/", $row->ms_content)) {
    printf("<b>Contains apostrophe</b><br /><br />");
  }
}
?>
