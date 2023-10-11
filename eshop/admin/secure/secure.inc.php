<?
define('FILE_NAME', '.htpasswd');

function getHash($password) {
  $hash = password_hash($password, PASSWORD_BCRYPT);
  return $hash;
}

function checkHash($password, $hash) {
  return password_verify($password, $hash);
}

function saveUser($login, $hash) {
  $str = "$login:$hash\n";
  if(file_put_contents(FILE_NAME, $str, FILE_APPEND))
  return true;
  else
  return false;
}

function userExists($login) {
  if(!is_file(FILE_NAME)) return false;
  $users = file(FILE_NAME);
  foreach ($users as $user) {
    if(strpos($user, $login.':') !== false) return $user;
  }
  return false;
}

function logOut(){
  session_destroy();
  header("Location: secure/login.php");
  exit;
}

function searchUser($login) {
  global $link;
  $login = clearStr($login);
  $sql = "SELECT login FROM users WHERE login = '$login'";
  $result = mysqli_query($link, $sql);
  if(mysqli_num_rows($result) > 0) return false;
  return true;
}

function addNewUser($id, $login, $password, $email, $firstName, $lastName, $surnName, $dateUser) {
  global $link;
  $sql = "INSERT INTO users (id, login, password, email, firstName, lastName, surnName, dateOfBirth) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  global $link;
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  mysqli_stmt_bind_param($stmt, "ssssssss", $id, $login, $password, $email, $firstName, $lastName, $surnName, $dateUser);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  return true;
}

function checkUserPass($login, $password) {
  global $link;
  $login = clearStr($login);
  $sql = "SELECT password FROM users WHERE login = '$login'";
  if(!$result = mysqli_query($link, $sql)) return false;
  $row = mysqli_fetch_assoc($result);
  if(!$row) {
    return false;
  } else {
    if(!checkHash($password, $row['password'])) return false;
  }
  return true;
}

function getIdUser($login) {
  global $link;
  $sql = "SELECT id FROM users WHERE login = '$login'";
  if(!$result = mysqli_query($link, $sql)) return false;
  $row = mysqli_fetch_assoc($result);
  if(!$row) {
    return false;
  } else {
    return $row['id'];
  }
}

function delComment($id) {
  global $link;
  $sql = "DELETE FROM comments WHERE comments.id = $id";
  if(!$result = mysqli_query($link, $sql)) return false;
  return true;
}

function recoveryPassword($data) {
  global $link;
  $sql = "SELECT email FROM users WHERE (`login` = '$data') OR (`email` = '$data')";
  if(!$result = mysqli_query($link, $sql)) return false;
  $row = mysqli_fetch_row($result) ?? [];
  return implode($row);
}