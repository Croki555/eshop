<?php
session_start();
require "inc/lib.inc.php";
require "inc/config.inc.php";
$id = clearInt($_GET['id']);
$itemCard = getCardcatalog($id);
if(empty($id) || empty($itemCard)) header("Location: /eshop/");
if(isset($_SESSION['user'])) {
  $_SESSION['user']['idProduct'] = $id;
}
$comments = getComments($id);
$result = '';
if(!count($comments) > 0) $result = "<p>Комментарии отсутствуют</p>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Карточка товара</title>
</head>
<style>
ul {
  padding: 0;
}
li {
  list-style-type: none;
  border-bottom: 1px solid #808080;
}

li:not(:first-child) {
  border: none;
}
.container {
  max-width: 1800px;
  margin: 0 auto;
  display: flex;
  justify-content: space-around;
}
.product {
  display: flex;
  gap: 15px;
  align-items: center;
  flex-direction: column;
  max-width: 350px;
  border: 1px solid #000;
  padding: 20px;
}
.comments {
  width: 50%;
}
form {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}
.rating-area {
  display: flex;
  width: 100%;
  justify-content: space-evenly;
  flex-direction: row-reverse;
}
.rating-user {
  display: inline-flex;
  flex-direction: row-reverse;
}

.rating-area:not(:checked) > input,
.rating-user:not(:checked) > input {
	display: none;
}
.rating-area:not(:checked) > label,
.rating-user:not(:checked) > label {
	cursor: pointer;
	font-size: 32px;
	line-height: 32px;
	color: lightgrey;
	text-shadow: 1px 1px #bbb;
}
.rating-area label:before,
.rating-user label:before {
	content: '★';
}
.rating-area > input:checked ~ label,
.rating-user > input:checked ~ label {
	color: gold;
	text-shadow: 1px 1px #c60;
}
.rating-area:not(:checked) > label:hover,
.rating-area:not(:checked) > label:hover ~ label {
	color: gold;
}
.rating-area > input:checked + label:hover,
.rating-area > input:checked + label:hover ~ label,
.rating-area > input:checked ~ label:hover,
.rating-area > input:checked ~ label:hover ~ label,
.rating-area > label:hover ~ input:checked ~ label {
	color: gold;
}
.rate-area > label:active {
	position: relative;
}
</style>
<body>
<div class="container">
  <div style="display: flex;flex-direction: column;align-items: center;">
    <p style="align-self: start;"><a href="/eshop/catalog.php">Обрато в каталог</a></p>
    <div class="product">
      <h1>Название: <?= $itemCard['title']?></h1>
      <img src = "./admin/images/<?=$itemCard['image']?>" width = "100%" height = "250px"/>
      <span><b>Автор: </b><?= $itemCard['author']?></span>
      <span><b>Год издания: </b><?= $itemCard['pubyear']?></span>
      <span><b>Цена: </b>₽<?= $itemCard['price']?></span>
      <p><?= $itemCard['column_description']?></p>
    </div>
    <div>
      <?php
      if(isset($_SESSION['user'])) {
        ?>
        <h2>Оставьте отзыв о товаре</h2>
        <form action="comment_product.php" method="post">
              <div class="rating-area">
                <input type="radio" id="star-5" name="rating" value="5">
                <label for="star-5" title="Оценка «5»"></label>	
                <input type="radio" id="star-4" name="rating" value="4">
                <label for="star-4" title="Оценка «4»"></label>    
                <input type="radio" id="star-3" name="rating" value="3">
                <label for="star-3" title="Оценка «3»"></label>  
                <input type="radio" id="star-2" name="rating" value="2">
                <label for="star-2" title="Оценка «2»"></label>    
                <input type="radio" id="star-1" name="rating" value="1">
                <label for="star-1" title="Оценка «1»"></label>
              </div>
          <textarea name="comment" cols="30" rows="10"></textarea>
          <button type="submit">Отправить</button>
        </form>
        <?
      } elseif(!isset($_SESSION['admin'])) {
        ?>
        <?php $url ="http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";?>
        <p>Для того, чтобы оставлять комментарий необходимо <a href="../inc/login_user.php?url=<?=$url?>">авторизоваться</a><p>
        <?
      }
      ?>
    </div>
  </div>
  <div class="comments">
    <h2>Комментарии</h2>
    <?=$result?>
    <ul>
    <?php
    foreach ($comments as $item) {
      ?>
      <li>
        <span style="display: block;"><?= date("Y-m-d H:i:s",$item['datetime'])?></span>
        <span>Пользователь:</span>
        <h3 style="display: inline;"> <?=$item['login']?></h3>
        <div class="rating-user">
          <input type="radio" id="star-5" value="5" disabled <?php if($item['rating'] == 5) echo 'checked';?>>
            <label for="star-5" title="Оценка «5»"></label>	
          <input type="radio" id="star-4" value="4" disabled <?php if($item['rating'] == 4) echo 'checked';?>>
            <label for="star-4" title="Оценка «4»"></label>    
          <input type="radio" id="star-3" value="3" disabled <?php if($item['rating'] == 3) echo 'checked';?>>
            <label for="star-3" title="Оценка «3»"></label>  
          <input type="radio" id="star-2" value="2" disabled <?php if($item['rating'] == 2) echo 'checked';?>>
            <label for="star-2" title="Оценка «2»"></label>    
          <input type="radio" id="star-1" value="1" disabled <?php if($item['rating'] == 1) echo 'checked';?>>
            <label for="star-1" title="Оценка «1»"></label>
        </div>
        <p><?=$item['comment']?></p>
      </li>
      <?
    }
    ?>
    </ul>
  </div>
</div>
</body>
</html>