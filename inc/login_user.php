<?php
session_start();
require_once "../eshop/admin/secure/secure.inc.php";
require "../eshop/inc/lib.inc.php";
require "../eshop/inc/config.inc.php";

if(isset($_SESSION['user']) || isset($_SESSION['admin'])) header("Location: /");
$title = 'Авторизация';
$result = '';
$login = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $login = clearStr($_POST['login']);
  $pass = clearStr($_POST['password']);
  if(!checkUserPass($login, $pass)) {
    $result = "<p><b>Не правильное имя пользователя или пароль!</b></p>";
  } 
  else {
    setcookie('login', $login, strtotime("+1 week"));
    $_SESSION['user'] = [
      'id' => getIdUser($login),
      'login' => $login
    ];
    header("Location: " . $_SERVER['HTTP_REFERER']);
  }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=$title?></title>
</head>
<body> 
<a href="/index.php">Вернуть домой</a>
<?=$result?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
  <p>
    <label for="txtUser">Логин</label>
    <input id="txtUser" type="text" name="login" value="<?=$login?>">
  </p>
  <p>
    <label for="txtString">Пароль</label>
    <input id="txtString" type="password" name="password">
  </p>
  <a href="recovery_password.php">Забыли пароль?</a>
  <p>
    <button type="submit">Войти</button>
  </p>
</form>
</body>
</html> 

