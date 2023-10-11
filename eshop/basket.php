<?php
// подключение библиотек
require "inc/lib.inc.php";
require "inc/config.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Корзина пользователя</title>
</head>
<style>
	
</style>
<body>
	<h1>Ваша корзина</h1>
<?php
global $basket;
if(!$count) {
	echo "<p>Корзина пуста! Вернитесь в <a href='catalog.php'>каталог</a></p>";
	exit;
} else {
	echo "<p>Вернуться в<a href='catalog.php'> каталог</a></p>";
}

$goods = myBasket();
	if(!is_array($goods)) {echo 'ERROR!'; exit;};
	if(!count($goods)) {echo 'EMPTY!'; exit;};
?>
<p>Вернуться на <a href='/index.php'>главную</a></p>
<table border="1" cellpadding="5" cellspacing="0" width="100%" style="text-align: center;">
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
	<th>Удалить</th>
</tr>
<?php
$i = (int) 1;
$sum = 0;
foreach($goods as $item) {
	$sum += $item['price'] * $item['quantity'];
	?>
	<tr>
		<td><?= $i++?></td>
		<td><?= $item['title']?></td>
		<td><?= $item['author']?></td>
		<td><?= $item['pubyear']?></td>
		<td><?= $item['price']?></td> 
		<td>
			<form action="price_reload.php" method="post">
				<input type="number" name="cou" value="<?=$item['quantity']?>">
				<button type="submit" name="btn" value="<?=$item['id']?>">Пересчитать</button>
			</form> 
		</td>
		<td><a href="delete_from_basket.php?id=<?=$item['id']?>">Удалить</a></td>
	</tr>
	<? 
}
?>
</table>
<p>Всего товаров в корзине на сумму: <?= $sum?>руб.</p>

<div align="center">
	<input type="button" value="Оформить заказ!" onClick="location.href='orderform.php'" />
</div>

</body>
</html>







