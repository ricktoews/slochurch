<?php
include("path.inc");
include("includes/lib_admin.inc");

$today = date("Y-m-d H:i:s");
$new = $_REQUEST["new"];

header("Content-type: text/tab-delimited\nContent-disposition: filename=maillist.csv");

$delim = ",";
$quote = '"';

//echo "Last Name" . $delim;
//echo "First Name" . $delim;
echo "Email Address" . $delim;
echo "Email Format" . $delim;
echo "Signup Date" . $delim;
//echo "Address 1" . $delim;
//echo "Address 2" . $delim;
//echo "City" . $delim;
//echo "State" . $delim;
//echo "Zip" . $delim;
//echo "Phone" . $delim;
echo "\r\n";

$sql = "SELECT * FROM site_maillist " .
       "ORDER BY ml_last, ml_first";
$result = mysql_query($sql, $connect);
while ($row = mysql_fetch_object($result)) {
  $ml_first = $row->ml_first;
  $ml_last = $row->ml_last;
  $ml_email = trim($row->ml_email);
  $ml_emailformat = $row->ml_emailformat;
  $ml_address1 = $row->ml_address1;
  $ml_address2 = $row->ml_address2;
  $ml_city = $row->ml_city;
  $ml_state = $row->ml_state;
  $ml_zip = $row->ml_zip;
  $ml_phone = format_phone($row->ml_phone);
  $ml_date = $row->ml_date;

//  echo $quote . $ml_last . $quote . $delim;
//  echo $quote . $ml_first . $quote . $delim;
  echo $quote . $ml_email . $quote . $delim;
  echo $quote . $ml_emailformat . $quote . $delim;
  echo $quote . $ml_date . $quote . $delim;
//  echo $quote . $ml_address1 . $quote . $delim;
//  echo $quote . $ml_address2 . $quote . $delim;
//  echo $quote . $ml_city . $quote . $delim;
//  echo $quote . $ml_state . $quote . $delim;
//  echo $quote . $ml_zip . $quote . $delim;
//  echo $quote . $ml_phone . $quote . $delim;
  echo "\r\n";
  
}
?>
