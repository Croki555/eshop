<?php
	require "secure/session.inc.php";
	require "../inc/lib.inc.php";
	require "../inc/config.inc.php";
  $comments = getAllComments();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Просмотр комментариев пользователей</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <a href="index.php">Вернуться назад</a>
    <h1>Комментарии пользователей</h1>
    <ul>
    <?php
    foreach ($comments as $item) {
      ?>
      <li>
        <a href="delete_comment.php?id=<?=$item['id']?>">Удалить комментарий</a>
        <h2>ID товара: <a href="/eshop/card_product.php?id=<?= $item['id_product']?>"><?= $item['id_product']?></a></h2>
        <span><b>Время:</b> <?= date("Y-m-d | | H:i:s",$item['datetime'])?></span>
        <span><b>ID пользователя:</b> <?=$item['id_user']?></span>
        <div class="rating-user">
          <span><b>Оценка товара:</b></span>
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
        <span><b>Комментарий:</b></span>
        <p><?=$item['comment']?></p>
      </li>
      <?
    }
    
    ?>
    </ul>
  </div>
</body>
</html>