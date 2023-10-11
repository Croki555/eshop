<?
$visitCounter = 1;
if(isset($_COOKIE['visitCounter'])) {
  $visitCounter = $_COOKIE['visitCounter'];
  $visitCounter++;
}

$lastVisit = "";
if(isset($_COOKIE['lastVisit'])) {
  $lastVisit = date("d-m-Y", $_COOKIE['lastVisit']);
}

setcookie('visitCounter', $visitCounter, 0x7FFFFFFF);
setcookie('lastVisit', time(), 0x7FFFFFFF);

if(date('d-m-Y', $_COOKIE['lastVisit']) != date('d-m-Y'));

