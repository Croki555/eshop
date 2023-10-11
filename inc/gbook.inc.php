<?php
/* Основные настройки */
define('DB_HOST', 'localhost');
define('DB_LOGIN','root');
define('DB_PASSWORD','');
define('DB_NAME','gbook');
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
if(!$link) {
  echo 'Ошибка: № ' . mysqli_connect_errno() . ':' . mysqli_connect_error();
}
/* Основные настройки */

/* Сохранение записи в БД */
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = strip_tags($_POST['name']);
  $email = strip_tags($_POST['email']);
  $msg = strip_tags($_POST['msg']);
  $sql = "INSERT INTO msgs(name, email, msg) VALUES ('$name', '$email', '$msg')";
  mysqli_query($link, $sql);
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit;
}
/* Сохранение записи в БД */

/* Удаление записи из БД */
if(isset($_GET['del'])) {
  $id = abs((int)$_GET['del']);
  if($id) {
    $sql = "DELETE FROM msgs WHERE id = $id";
    mysqli_query($link, $sql);
  }
}
/* Удаление записи из БД */
?>
<h3>Оставьте запись в нашей Гостевой книге</h3>

<form method="post" action="<?= $_SERVER['REQUEST_URI']?>">
Имя: <br /><input type="text" name="name" /><br />
Email: <br /><input type="text" name="email" /><br />
Сообщение: <br /><textarea name="msg"></textarea><br />

<br />

<input type="submit" value="Отправить!" />

</form>
<?php
/* Вывод записей из БД */
$output = "SELECT id, name, email, msg, UNIX_TIMESTAMP(datetime) as dt FROM msgs ORDER BY id DESC";

$result = mysqli_query($link, $output);
$row_count = mysqli_num_rows($result);


if(!$result) { 
  echo 'Ошибка: № ' .  mysqli_errno($link) . ':' . mysqli_error($link);
} else {
  echo "<p>Всего записей в гостевой книге: $row_count</p>";
  while ($row = mysqli_fetch_assoc($result)) {
    $date = date("d-m-Y", $row['dt']) . " в " .  date("H:i", $row['dt']);
    echo "<p><a href=\"mailto:{$row['email']}\">{$row['name']}</a>" . " $date написал<br>{$row['msg']}</p>";
    echo "<p align=\"right\"><a href=\"http://mysite.local/index.php?id=gbook&del={$row['id']}\">Удалить</a></p>";
  }
}

mysqli_close($link);
/* Вывод записей из БД */
?>