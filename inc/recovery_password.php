<?php
session_start();
require_once "../eshop/admin/secure/secure.inc.php";
require "../eshop/inc/lib.inc.php";
require "../eshop/inc/config.inc.php";
$title = 'Восстановление пароля';

@$data = clearStr($_POST['text']) ?? '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(recoveryPassword($data)) {
    $email = recoveryPassword($data);

    $to = $email;
    $subject = 'the subject';
    $message = 'ТУТ сформированная ссылка для восстановления пароля';
    $headers = array(
        'From' => 'webmaster@example.com',
        'Reply-To' => 'webmaster@example.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );
    
    mail($to, $subject, $message, $headers);
    $result = "<p><b>На e-mail-адрес, указанный Вами при регистрации, было отправлено уведомление для генерации нового пароля.</b></p>";
  } else {
    $result = "<p><b>Пользователь с таким именем или e-mail не найден в базе данных</b></p>";
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
<a href="login_user.php">Вернуть назад</a>
<?= $result ?? '' ?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
  <p>
    <label for="txtUser">Ваш логин или Email на сайте</label>
    <input id="txtUser" type="text" name="text">
    <button type="submit">Отправить</button>
  </p>
</form>
</body>
</html> 