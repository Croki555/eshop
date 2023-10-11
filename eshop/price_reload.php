<?php
require "inc/lib.inc.php";
require "inc/config.inc.php";
global $basket, $goods,$priceC;
$priceC = clearInt($_POST['cou']);
$id = clearInt($_POST['btn']);
$basket[$id] = $priceC;
saveBasket();
header("Location: basket.php");
exit;


