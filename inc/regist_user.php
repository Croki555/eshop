<?php
require_once "../eshop/admin/secure/secure.inc.php";
require "../eshop/inc/lib.inc.php";
require "../eshop/inc/config.inc.php";
$title = 'Регистрация';
$pass = '';
$login = '';
$result = '';
$firstName = '';
$lastName = '';
$surnName = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $login = clearStr($_POST['login']);
  $pass = clearStr($_POST['password']);
  $pass2 = clearStr($_POST['password2']);
  $firstName = clearStr($_POST['firstName']);
  $lastName = clearStr($_POST['lastName']);
  $surnName = clearStr($_POST['surnName']); 
  $dateUser = clearStr($_POST['dateUser']);
  $email = clearStr($_POST['email']);

  if(!$user = searchUser($_POST['login'])) {
    $result ="<p><b>Логин занят</b></p>";
  } else if ($login == '') {
    $result = "<p><b>Введите логин</b></p>";
  } else if(strlen($login) < 5) {
    $result = "<p><b>Минимальная длина Логина должна быть от 4 символов у вас " . strlen($login) . "</b></p>"; 
  } else if ($pass == '') {
    $result =  "<p><b>Введите пароль</b></p>";
  } else if ($pass == $login) {
    $result = "<p><b>Пароль похож на логин!</b></p>"; 
  } else if ($pass !== $pass2) {
    $result = "<p><b>Пароли не совпадают</b></p>";
  } else {
    $hash = getHash($pass2);
    $id = uniqid();
    if(addNewUser($id, $login, $hash, $email, $firstName, $lastName, $surnName, $dateUser)) {
      header("Location: login_user.php");
      exit;
    };
  }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=$title?></title>
</head>
<style>
  form p {
    display: flex;
    align-items: center;
  }
  form p input {
    width: 250px;
  }
</style>
<body>
<p><a href="/index.php">Вернуть домой</a></p>
<?=$result?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
  <p>
    <label for="txtUser">Логин*</label>
    <input id="txtUser" pattern="[A-Za-z]{4,}[0-9]{1,}" title="[A-Za-z](4 и более символов)(одна и более цифра)" type="text" name="login"  value="<?=$login?>">
  </p>
  <p>
    <label for="txtEmai">E-mail*</label>
    <input id="txtEmail" type="email" name="email">
  </p>
  <p>
    <label for="txtFirstName">Имя*</label>
    <input id="txtFirstName" pattern="[А-Яа-яЁё]{5,}" title="[А-Яа-яЁё](5 и более символов)" type="text" name="firstName">
  </p>
  <p>
    <label for="txtLastName">Фамилия*</label>
    <input id="txtLastName" pattern="[А-Яа-яЁё]{5,}" title="[А-Яа-яЁё](5 и более символов)" type="text" name="lastName">
  </p>
  <p>
    <label for="txtSurnName">Отчество*</label>
    <input id="txtSurnName" pattern="[А-Яа-яЁё]{5,}" title="[А-Яа-яЁё](5 и более символов)" type="text" name="surnName">
  </p>
  <p>
    <label for="txtDateName">Дата рождения*</label>
    <input id="txtDateName" require type="date" name="dateUser">
  </p>
  <p>
    <label for="txtString">Пароль*</label>
    <input id="txtString" type="password" name="password" <?=$pass?>>
  </p>
  <p>
    <label for="txtString">Подтверждение пароля*</label>
    <input id="txtString" type="password" name="password2"/>
  </p>
  <p>
    <button type="submit">Зарегистрироваться</button>
  </p>
</form>
</body>
</html>

