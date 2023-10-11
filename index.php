<?php
session_start();
include 'inc/headers.inc.php';
include 'inc/cookie.inc.php';
// Имя файла журнала
define('PATH_LOG', 'path.log');
include 'inc/log.inc.php';
if(isset($_GET['logout'])){
	session_destroy();
  header("Location: /index.php");
  exit;
}
@$user = $_SESSION['user'];
@$admin = $_SESSION['admin'];
@$login = $_SESSION['user']['login'];
// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>

<head>
  <title>
    <?=$title?>
  </title>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="inc/style.css" /> 
</head>
<!-- <style>
  .wrapper {
    display: grid;
    grid-template:
      "hd hd hd hd   hd   hd   hd   hd   hd" minmax(100px, auto)
      "nv nv main main main main main main main" minmax(100px, auto)
      "ft ft ft ft   ft   ft   ft   ft   ft" minmax(100px, auto)
      / 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr ;
  }

  #header {
    grid-area: hd;
  }
  #footer {
    grid-area: ft;
  }
  #content {
    grid-area:  main;
  }
  #nav {
    grid-area: nv;
  }
</style> -->
<body>
  <div class="wrapper">
    <div id="header">
      <img src="logo.gif" width="187" height="29" alt="Наш логотип" class="logo" />
      <span class="slogan">обо всём сразу</span>
    </div>
    <div id="content">
      <h1><?= $header?></h1>
      <blockquote>
        <?php
        if($visitCounter == 1) {
          echo "Спасибо, что зашли на огонек!";
        } else {
          echo "Вы зашли к нам $visitCounter раз<br>Последнее посещение:$lastVisit";
        }
        ?>
      </blockquote>
      <!-- Область основного контента -->
      <?php 
        include 'inc/routing.inc.php'; 
      ?>
      <!-- Область основного контента -->
    </div>
    <div id="nav">
    <!-- Навигация -->
      <h2>Навигация по сайту</h2>
      <ul>
        <li>
          <a href='index.php?id=about'>О нас</a>
        </li>
        <li>
          <a href='index.php?id=contact'>Контакты</a>
        </li>
        <li>
          <a href='index.php?id=info'>Информация</a>
        </li>
        <li>
          <a href='test/index.php'>Он-лайн тест</a>
        </li>
        <li>
          <a href='index.php?id=gbook'>Гостевая книга</a>
        </li>
        <li>
          <a href='eshop/catalog.php'>Магазин</a>
        </li>
        <li>
          <a href='index.php?id=log'>Журнал посещений</a>
        </li>
          <?php 
          if(isset($_SESSION['admin'])) {
            ?>
            <li><a href='/eshop/admin/'>Управление админкой</a></li>
            <?
          } elseif(!isset($_SESSION['user'])) {
            ?>
            <li><a href='/eshop/admin/'>Войти в админку</a></li>
            <?
          } 
          ?>
        </li>
        <?php
        if(isset($_SESSION['user'])) {
          ?>
          <li><a href='/inc/private_office.php'>Личный кабинет</a></li>
          <?
        }
        ?>
        <li>
          <a href='inc/regist_user.php'>Регистрация</a> 
        </li>
        <li>
        <?php
          if(!isset($user) xor $admin == true) {
            echo "<a href='inc/login_user.php'>Авторизация</a>";
          } else {
            echo "<a href='index.php?logout'>Выйти</a>";
          }
          ?>
        </li>
      </ul>
    <!-- Навигация -->
    </div>
    <div id="footer">
    <!-- Нижняя часть страницы -->
      &copy; Супер-мега сайт, 2000 &ndash; <?= date('Y')?>
      <!-- Нижняя часть страницы -->
    </div>
  </div>
</body>

</html>