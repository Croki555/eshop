<?php
// подключение библиотек
require "secure/session.inc.php";
require "../inc/lib.inc.php";
require "../inc/config.inc.php";
require "../admin/secure/secure.inc.php";

$title = clearStr($_POST['title']);
$author = clearStr($_POST['author']);
$pubyear = clearInt($_POST['pubyear']);
$price = clearInt($_POST['price']);
$descr = clearStr($_POST['descr']);

echo "<pre>";
var_dump($_FILES);

list($img, $type) = explode("/",$_FILES['userfile']['type']);
echo $type;
$hash = md5($_FILES["userfile"]["name"] . "$type");
$str = "$hash.$type";
if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	// echo "Файл ". $_FILES['userfile']['name'] ." успешно загружен.\n";
	move_uploaded_file($_FILES["userfile"]["tmp_name"], "images/$hash.$type");
} else {
	echo "Возможная атака с участием загрузки файла: ";
	echo "файл '". $_FILES['userfile']['tmp_name'] . "'.";
}

if(!addItemToCatalog($title, $author, $pubyear, $price, $descr, $str)){
	echo 'Произошла ошибка при добавлении товара в каталог';
}	else {
	header("Location: add2cat.php");
	echo $imagetmp;
	exit;
}



