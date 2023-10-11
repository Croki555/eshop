<?php
session_start();
require_once "../eshop/admin/secure/secure.inc.php";
require "../eshop/inc/lib.inc.php";
require "../eshop/inc/config.inc.php";
if(isset($_SESSION['admin'])) header("Location: /");
$user = getUserData($_SESSION['user']['id']);

$result = '';
$id = clearStr($_SESSION['user']['id']);
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $login = clearStr($_POST['login']);
  $id = $_SESSION['user']['id'];
  @$oldPass = clearStr($_POST['oldPassword']);
  @$newPass = clearStr($_POST['newPassword']);
  @$newPass2 = clearStr($_POST['newPassword2']);
  
  $email = clearStr($_POST['email']);
  $firstName = clearStr($_POST['firstName']);
  $lastName = clearStr($_POST['lastName']);
  $surnName = clearStr($_POST['surnName']);

  $dateOfBirth = clearStr($_POST['dateOfBirth']) ?? $user['dateOfBirth'];
  
  if(!$user = searchUser($login) || $login == $user['login']) {
    $result ="<p><b>Логин занят</b></p>";
  } else {
    setcookie('login', $login, strtotime("+1 week"));
    updateUserLogin($id, $login);
    $_SESSION['user'] = [
      'id' => getIdUser($login),
      'login' => $login
    ];
  } 
    if(strlen($login) < 5) {
    $result = "<p><b>Минимальная длина Логина должна быть от 4 символов у вас " . strlen($login) . "</b></p>"; 
  } 
    else if ($newPass == $login) {
    $result = "<p><b>Пароль похож на логин!</b></p>"; 
  } else if ($newPass !== $newPass2) {
    $result = "<p><b>Пароли не совпадают</b></p>";
  }
  if(empty($oldPass) && !empty($newPass)) {
    $result = '';
  } else {
    if(!checkUserPass($login, $oldPass)) {
      $result = "<p><b>Неверный старый пароль!</b></p>";
    } else {
      if ($newPass == '') {
        $result =  "<p><b>Введите новый пароль</b></p>";
      } else if ($newPass == $oldPass) {
        $result = "<p><b>Пароль похож на старый!</b></p>"; 
      } else if ($newPass == $login) {
        $result = "<p><b>Пароль похож на логин!</b></p>"; 
      } else if ($newPass !== $newPass2) {
        $result = "<p><b>Пароли не совпадают</b></p>";
      } else {
        $hash = getHash($newPass2);
        updateUserPassword($id, $hash);
        $result = "<p><b>Пароль обновлён</b></p>"; 
      }
    } 
  }
  
  if($_POST['firstName']) updateUserFirstName($id, $firstName);
  if($_POST['lastName']) updateUserLastName($id, $lastName);
  if($_POST['surnName']) updateUserSurnName($id, $surnName);
  if($_POST['dateOfBirth']) updateUserDate($id, $dateOfBirth);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Личный кабинет: <?=$user['login'] ?? $login ?></title>
</head>
<body>
  <style>
    .container {
      display: grid;
      grid-template-areas: "info orders";
    }
    .container form {
      grid-area: info;
    }
    .container .myOrders {
      grid-area: orders;
    }
    table {
      text-align: center;
    }

    .title-order {
      text-decoration: underline;
      cursor: pointer;
      display: inline-block;
      margin-right: 10px;
    }
    .descr-data {
      display: inline-block;
    }

    .no-active {
      display: none;
    }

    .active {
      display: table;
    }

    table {
      width: 70%;
      border: 1px solid #000;
      border-collapse: collapse;
    }

    table td,
    table th,
    table tr {
      padding: 10px;
      border: 1px solid #000;
    }

    .show {
      display: block;
    }
  </style>
<h1>Личный кабинет</h1>
<a href="/">Домой</a>
<div class="container">
  <div>
  <form action="<?= $_SERVER['PHP_SELF']?>" method="post">
    <h2>Личная информация</h2>
    <?=$result?>
    <p>
      <label for="txtUser">Логин*</label>
      <input id="txtUser" pattern="[A-Za-z]{4,}[0-9]{1,}" title="[A-Za-z](4 и более символов)(одна и более цифра)" type="text" name="login"  value="<?=$user['login'] ?? $login?>">
    </p>
    <p>
      <label for="txtEmai">E-mail*</label>
      <input id="txtEmail" type="email" name="email" value="<?=$user['email'] ?? $email?>">
    </p>
    <p>
      <label for="txtFirstName">Имя*</label>
      <input id="txtFirstName" pattern="[А-Яа-яЁё]{4,}" title="[А-Яа-яЁё](4 и более символов)" type="text" name="firstName" value="<?=$user['firstName'] ?? $firstName?>">
    </p>
    <p>
      <label for="txtLastName">Фамилия*</label>
      <input id="txtLastName" pattern="[А-Яа-яЁё]{4,}" title="[А-Яа-яЁё](4 и более символов)" type="text" name="lastName" value="<?=$user['lastName'] ?? $lastName?>">
    </p>
    <p>
      <label for="txtSurnName">Отчество*</label>
      <input id="txtSurnName" pattern="[А-Яа-яЁё]{4,}" title="[А-Яа-яЁё](4 и более символов)" type="text" name="surnName" value="<?=$user['surnName'] ?? $surnName?>">
    </p>
    <p>
      <label for="txtDateName">Дата рождения*</label>
      <input id="txtDateName" require type="date" name="dateOfBirth" value="<?=$dateOfBirth ?? $user['dateOfBirth']?>">
    </p>
    <p>
      <label for="txtString">Старый пароль*</label>
      <input id="txtString" type="password" name="oldPassword" value="<?=$oldPass ?? ''?>">
    </p>
    <p>
      <label for="txtString">Новый пароль*</label>
      <input id="txtString" type="password" name="newPassword" value="<?=$newPass ?? ''?>"/>
    </p>
    <p>
      <label for="txtString">Повторите пароль*</label>
      <input id="txtString" type="password" name="newPassword2"/>
    </p>
    <p>
      <button type="submit">Обновить данные</button>
    </p>
  </form>
  </div>
  <div class="myOrders">
    <h2>Мои заказы</h2>
    <div class="orders">
    <?php
    $IdOrders = getIdOrderUser($id);
    if(empty($IdOrders)) {
      ?>
      <p>Заказы отсутсвуют, перейти в <a href="../eshop/catalog.php">каталог</a></p>
      <?
    } else
    foreach($IdOrders as $order) {
      ?>
        <div class="order-info">
          <h2 class="title-order" count="1" data-order="<?= $order['orderid'] ?>">Заказ: № <?= $order['orderid'] ?></h2>
          <p class="descr-data"><b>Дата размещения заказа</b>: <?= date("d-m-Y H:i:s",$order["datetime"]) ?></p>
        </div>
      <?
    }
    ?>
    </div>
    <script>
      let count = false;
      const order = document.querySelector(".orders");
      const titleOr = document.querySelector('.title-order');
      let str = null;
      order.addEventListener('click', (ev) => {
        let title = 'title-order';
        if(ev.target.classList.contains(title)) {
          let orderId = ev.target.getAttribute('data-order');
          const url = `http://mysite.local/inc/api.php?id=${orderId}`;
          fetch(url)
          .then((response) => response.json())
          .then((date) => {
              str = date;
              if(ev.target.getAttribute('count') == '1') {
                
                newTable = document.createElement('table');
                newTable.classList.add('active');
                tableInf = `
                <tr>
                  <th>N п/п</th>
                  <th>Название</th>
                  <th>Автор</th>
                  <th>Год издания</th>
                  <th>Цена, руб.</th>
                  <th>Количество</th>
                </tr>`;

                newTable.innerHTML = tableInf;
                
                ev.target.parentElement.append(newTable);
                
                table = ev.target.parentElement.lastElementChild; 
                sum = 0;
                  for(i = 0; i < str.length; i++) {
                    sum += str[i]['price'] * str[i]['quantity'];
                    tr = document.createElement('tr');
                    
                    table.appendChild(tr);
                    td = document.createElement('td');
                    td.innerHTML = 1 + i;
                    tr.append(td);

                    td = document.createElement('td');
                    td.innerHTML = str[i]['title'];
                    tr.append(td);

                    td = document.createElement('td');
                    td.innerHTML = str[i]['author'];
                    tr.append(td);

                    td = document.createElement('td');
                    td.innerHTML = str[i]['pubyear'];
                    tr.append(td);

                    td = document.createElement('td');
                    td.innerHTML = str[i]['price'];
                    tr.append(td);

                    td = document.createElement('td');
                    td.innerHTML = str[i]['quantity'];
                    tr.append(td);
                  }
                  let p = document.createElement('p');
                  p.classList.add('sum');
                  p.innerHTML = `Всего товаров в заказе на сумму: ${sum}руб`;
                  ev.target.parentElement.append(p);
                  ev.target.setAttribute('count', '0');
                  console.log(ev.target);
              } else {
                ev.target.setAttribute('count', 1);
                ev.target.closest('.order-info').querySelector('.sum').remove();
                ev.target.closest('.order-info').querySelector('table').remove();
              }
            })
        }
      })  
    </script>
  </div>
</body>
</html>