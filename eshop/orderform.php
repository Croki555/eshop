<?php
session_start();
require "inc/lib.inc.php";
require "inc/config.inc.php";
$id = clearStr($_SESSION['user']['id']);
$user = getUserData($id);
$fullName = $user['lastName'] . ' ' . $user['firstName'] . ' ' . $user['surnName'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Форма оформления заказа</title>
</head>
<body>
	<h1>Оформление заказа</h1>
	<form action="saveorder.php" method="post">
		<p>Заказчик:<?=$fullName?>
		<p>Email заказчика: <?= $user['email']?>
		<p>Телефон для связи: <input type="text" name="phone" size="50" />
		<p>Адрес доставки: <input type="text" name="address" size="100" />
		<p><input type="submit" value="Заказать" />
	</form>
</body>
</html>