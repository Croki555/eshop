<?
$dt = time();
@$page = $_SERVER['REQUEST_URI'];
@$ref = $_SERVER['HTTP_REFERER'];
$path = "Время и дата: ". date('h:i:s d.m.y',$dt) . " URI: $page Страница с которой перешёл: $ref\n";

$p = PATH_LOG;
$f = fopen("log/$p", "a+") or die("не удалось открыть файл");
fwrite($f, $path);
fclose($f);