<?php
// подключение библиотек
require "inc/lib.inc.php";
require "inc/config.inc.php";
$goods = selectAllItimes();
if(!is_array($goods)) {echo 'ERROR!'; exit;};
if(!count($goods)) {echo 'EMPTY!'; exit;};
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Каталог товаров</title>
</head>
<body>
<style>
	ul {
		padding: 0;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 270px));
    gap: 40px;
		grid-auto-flow: dense;
	}
	li {
		list-style-type: none;
	}
	.container {
		max-width: 1200px;
		margin: 0 auto;
	}
	form {
		margin-bottom: 25px;
		text-align: center;
	}
	form input {
		width: 25%;
	}
	.res {
		text-align: center;
	}
</style>
<p><a href="/index.php">Домой</a></p>
<p>Товаров в <a href="basket.php">корзине</a>: <?= $count?></p>
<div class="container">

<form action="<?=$_SERVER['PHP_SELF']?>" method="get">
	<input type="text" name="search" placeholder="Введите автора книги или название книги">
	<button type="submit">Найти</button>
</form>
<?php
if(@$_GET['search']) {
	@$str = clearStr($_GET['search']);
	
	include 'search_products.php';
} 
?>
<p style="text-align: center;"><?=$result ?? ''?></p>
<?php 
if(@$_GET['search']) {
	echo "<p style=\"text-align: center\"><a href=\"catalog.php\">Обратно в каталог</a></p>";
} elseif(isset($_GET['search'])) {
	echo "<p style=\"text-align: center\"><b>Вы ввели пустой поисковой запрос</b>.</p>";
}

?>

<ul>
<?php
if(empty($_GET['search'])) {
	foreach($goods as $item) {
		?>
		<li style="display: flex; flex-direction: column;">
			<img src = "./admin/images/<?=$item['image']?>" width = "100%" height = "100px"/>
			<h2><a href="card_product.php?id=<?=$item['id']?>"><?= $item['title']?></a></h2>
			<span><b>Автор: </b><?= $item['author']?></span>
			<span><b>Год издания: </b><?= $item['pubyear']?></span>
			<span><b>Цена: </b>₽<?= $item['price']?></span>
			<a href="add2basket.php?id=<?=$item['id']?>"> 
				<?php
					$str = isset($basket[$item['id']]) ? "<b>в корзине</b>" : 'в корзину';
					echo $str;
				?>
			</a>
		</li>
		<? 
	}
}
?>
</ul>
</div>
</body>
</html>