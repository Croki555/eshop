<?
@$products = searchProducts($str);

if(!empty($products) && !empty($str)) {
  echo "<ul>";
  foreach($products as $item) {
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
  echo "</ul>";
} else {
  $result = "К сожалению, по вашему запросу «<b>$str</b>» мы ничего не нашли.";
}
