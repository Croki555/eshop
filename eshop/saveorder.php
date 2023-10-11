<?php
session_start();
require "inc/lib.inc.php";
require "inc/config.inc.php";
global $basket;
// $customer = clearStr($_POST['name']);
// $email = clearStr($_POST['email']);
$id = clearStr($_SESSION['user']['id']);
$user = getUserData($id);
$fullName = $user['lastName'] . ' ' . $user['firstName'] . ' ' . $user['surnName'];

$phone = clearStr($_POST['phone']);
$address = clearStr($_POST['address']);
$orderId = $basket['orderid'];
$date = time();
$path = ORDERS_LOG;	

$order = "$id|$fullName|{$user['email']}|$phone|$address|$orderId|$date\n";
file_put_contents("./admin/$path", $order, FILE_APPEND);
saveOrder($id, $date);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Сохранение данных заказа</title>
</head>
<body>
	<p>Ваш заказ принят.</p>
	<p><a href="catalog.php">Вернуться в каталог товаров</a></p>
</body>
</html>