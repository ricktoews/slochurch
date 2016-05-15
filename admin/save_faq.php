<?php
include("path.inc");
include("includes/lib_admin.inc");

$arg = $_REQUEST["arg"];
$id = $_REQUEST["id"];
$question = $_REQUEST["question"];
$answer = $_REQUEST["answer"];
$followid = $_REQUEST["followid"];

// Add
if ($arg == "2") {
  if ($id) {
    $sql = "UPDATE site_faq " .
           "SET faq_question='$question', " .
           "    faq_answer='$answer' " .
           "WHERE faq_id='$id'";
    mysql_query($sql, $connect);
  }
  else {
    $sql = "INSERT INTO site_faq " .
           "SET faq_question='$question', " .
           "    faq_answer='$answer'";
    mysql_query($sql, $connect);
    $id = mysql_insert_id($connect);
  }
// Get the sequence in which the items are to be displayed.
  $idlist = array();
  if ($followid == 0) {
    $idlist[] = $id;
  }
  $sql = "SELECT * FROM site_faq " .
         "WHERE faq_id <> '$id' " .
         "ORDER BY faq_seq";
  $result = mysql_query($sql, $connect);
  while ($row = mysql_fetch_object($result)) {
    $idlist[] = $row->faq_id;
    if ($followid == $row->faq_id) {
      $idlist[] = $id;
    }
  }
// Update the sequence numbers.
  while (list($s, $i) = each($idlist)) {
    $sql = "UPDATE site_faq " .
           "SET faq_seq='$s' " .
           "WHERE faq_id='$i'";
    mysql_query($sql, $connect);
  }

}
// Delete 
elseif ($arg == "4") {
  $sql = "DELETE FROM site_faq " .
         "WHERE faq_id='$id'";
  mysql_query($sql, $connect);
}

header("Location: faq.php");
?>
