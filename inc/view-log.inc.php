<?
$f = fopen("log/".PATH_LOG, 'r');
$lines = [];
if($f) {
  while ( $line = fgets($f) ){
    $lines[] = $line;
  }
}
fclose($f);
foreach($lines as $row) {
  echo "$row<br>";
}
