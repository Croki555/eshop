<?
require "secure/session.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Форма добавления товара в каталог</title>
</head>
<body>
	<a href="/eshop/admin/">Вернуться назад</a>
	<form action="save2cat.php" method="post" enctype="multipart/form-data">
		<p>Название: <input type="text" name="title" size="50">
		<p>Автор: <input type="text" name="author" size="50">
		<p>Год издания: <input type="text" name="pubyear" size="4">
		<p>Цена: <input type="text" name="price" size="6"> руб.
		<p>Описание товара: <textarea type="text" name="descr" rows="10" cols="45"></textarea></p>
		<p>Добавить изображение:<input type="file" name="userfile"></p>
		<p><input type="submit" value="Добавить">
	</form> 
</body>
</html>