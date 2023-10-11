<?php
function clearStr ($data) {
	global $link;
	@$data = trim(strip_tags($data));
	return mysqli_real_escape_string($link, $data);
}

function clearInt ($data) {
	return abs((int) $data);
}

function addItemToCatalog($title, $author, $pubyear, $price, $descr, $img) {
  $sql = "INSERT INTO catalog (title, author, pubyear, price, column_description, image) VALUES (?, ?, ?, ?, ?, ?)";
  global $link;
  if (!$stmt = mysqli_prepare($link, $sql))
    return false;
  mysqli_stmt_bind_param($stmt, "ssiiss", $title, $author, $pubyear, $price, $descr, $img);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  return true;
}

function selectAllItimes() {
  global $link;
  $sql = 'SELECT id, title, author, pubyear, price, column_description, image FROM catalog';
  if(!$result = mysqli_query($link, $sql)) return false;
  $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  return $items;
}

function basketInit() {
  global $basket, $count;
  if(!isset($_COOKIE['basket'])) {
    $basket =['orderid' => uniqid()];
    saveBasket();
  } else {
    $basket = unserialize(base64_decode($_COOKIE['basket']));
    $count = count($basket) - 1;
  }
}
function saveBasket() {
  global $basket;
  $basket = base64_encode((serialize($basket)));
  setcookie('basket', $basket, 0x7FFFFFFF);
}

function myBasket() {
  global $link, $basket;
  $goods = array_keys(($basket));
  array_shift($goods);
  if(!$goods) return false;
  $ids = implode(",", $goods);
  $sql = "SELECT id, author, title, pubyear, price FROM catalog WHERE id IN ($ids)";
  if(!$result = mysqli_query($link, $sql)) return false;
  $items = result2Array($result);
  mysqli_free_result($result);
  return $items;
}


function add2Basket($id) {
  global $basket;
  $basket[$id] = 1;
  saveBasket();
}

function result2Array($data) {
  global $basket;
  $arr = [];
  while($row = mysqli_fetch_assoc($data)) {
    $row['quantity'] = $basket[$row['id']];
    $arr[] = $row;
  }
  return $arr;
}

function deleteItemFromBasket($id) {
  global $basket, $goods;
  unset($basket[$id]);
  saveBasket();
}

function removeBasket() {
  setcookie('basket', 'deleted', time()-3600);
}

function saveOrder($id,$datetime) {
  
  global $link, $basket; 
  $goods = myBasket();
  $stmt = mysqli_stmt_init($link);
  $sql = 'INSERT INTO orders (
    userid,
    title,
    author,
    pubyear,
    price,
    quantity,
    orderid,
    datetime)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    if (!mysqli_stmt_prepare($stmt, $sql)) return false;
  foreach($goods as $item){
    mysqli_stmt_bind_param($stmt, "sssiiisi",
    $id,
    $item['title'], $item['author'],
    $item['pubyear'], $item['price'],
    $item['quantity'],
    $basket['orderid'],
    $datetime);
    mysqli_stmt_execute($stmt);
  }
  mysqli_stmt_close($stmt);
  removeBasket();
  return true;
}

function getOrders() {
  global $link;
  if(!is_file(ORDERS_LOG)) return false;
  
    $allorders = [];
    $orders = file(ORDERS_LOG);
    foreach($orders as $order) {
      list($userId, $name, $email, $phone, $address, $orderid, $date) = explode("|", $order);
      $orderinfo = [];
      $orderinfo["userId"] = $userId;
      $orderinfo["name"] = $name;
      $orderinfo["email"] = $email;
      $orderinfo["phone"] = $phone;
      $orderinfo["address"] = $address;
      $orderinfo["orderid"] = $orderid;
      $orderinfo["date"] = $date;
  
      $sql = "SELECT userid, title, author, pubyear, price, quantity 
            FROM orders
            WHERE userid = '$userId' AND datetime = $date"; 
      if(!$result = mysqli_query($link, $sql)) return false;
  
      $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
  
      $orderinfo["goods"] = $items;
  
      $allorders[] = $orderinfo;  
    }
  
  return $allorders;
}

// function getOrdersUser($idUser) {
//   global $link; 
//   if(!$Idorders = getIdOrderUser($idUser)) return false;
//   $ordersUser = [];
//   foreach($Idorders as $item) {
//     $sql = "SELECT title, author, pubyear, price, quantity
//     FROM orders WHERE orderid = '{$item['orderid']}' AND UserID = '$idUser'";
//     if (!$result = mysqli_query($link, $sql)) return false;
//     $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     mysqli_free_result($result);
//     $ordersUser[$item['orderid']] = $orders;
//   }
//   return $ordersUser;
// }

function getIdOrderUser($idUser) {
  global $link;
  $sql = "SELECT  DISTINCT orderid, datetime
  FROM orders 
  WHERE UserID =?";
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  
  mysqli_stmt_bind_param($stmt, "s", $idUser);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id, $datetime);

  $idOrders = [];
  while(mysqli_stmt_fetch($stmt)) {
    $idOrders[] = [
      'orderid' => $id,
      'datetime' => $datetime
    ];
  }
  return $idOrders;
}

function getCardcatalog($id) {
  global $link;
  $sql = "SELECT title, author, pubyear, price, column_description, image
  FROM catalog
  WHERE id = ?";
  if (!$stmt= mysqli_prepare($link, $sql)) return false;
  mysqli_stmt_bind_param($stmt,'i', $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $title, $author, $pubyear, $price, $column_description, $image);
  while(mysqli_stmt_fetch($stmt)) {
    $card = [
    'title' => $title,
    'author' => $author,
    'pubyear' => $pubyear,
    'price' => $price,
    'column_description' => $column_description,
    'image' => $image
    ];
  }
  return $card;
}

function addCommentToProduct($idProduct, $idUser, $comment, $rating) {
  global $link;
  $timeNow = time();
  $sql = "INSERT INTO comments (id_product, id_user, comment, rating, datetime) VALUES (?, ?, ?, ?, ?)";
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  mysqli_stmt_bind_param($stmt, "issii", $idProduct, $idUser, $comment, $rating, $timeNow);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  return true;
} 

function getComments($idProduct) {
  global $link;
  $sql = "SELECT comments.id, login, comment, rating, datetime FROM `Comments` 
  JOIN users on Comments.id_user = users.id
  WHERE id_product =?
  ORDER BY comments.id DESC";
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  $comments = [];
  mysqli_stmt_bind_param($stmt, "i", $idProduct);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id, $login, $comment, $rating, $datetime);

  while(mysqli_stmt_fetch($stmt)) {
    $comments[] = [
      'id' =>$id,
      'login' => $login,
      'comment' => $comment,
      'rating' => $rating,
      'datetime' => $datetime
    ];
  }
  return $comments;
}

function getAllComments() {
  global $link;
  $sql = "SELECT id, id_product, id_user, rating, comment, datetime FROM comments";
  if (!$result = mysqli_query($link, $sql)) return false;
  $comments =mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  return $comments;
}

function getOrder($id) {
  global $link;
  $data = [];
  $sql = "SELECT title, author, pubyear, price, quantity FROM orders WHERE orderid =?";
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  mysqli_stmt_bind_param($stmt, 's', $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $title, $author, $pubyear, $price, $quantity);
  while(mysqli_stmt_fetch($stmt)) {
    $data[] = [
      'title' => $title,
      'author' => $author,
      'pubyear' => $pubyear,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
  mysqli_stmt_close($stmt);
  return $data;
}

function getUserData($id) {
  global $link;
  $user = [];
  $sql = "SELECT id, login, password, email, firstName, lastName, surnName, dateOfBirth 
  FROM users
  WHERE id =?";
  if(!$stmt = mysqli_prepare($link, $sql)) return false;
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $idUser, $login, $password, $email, $firstName, $lastName, $surnName, $dateOfBirth);
  while(mysqli_stmt_fetch($stmt)) {
    $user = [
      'id' => $idUser,
      'login' => $login,
      'password' => $password,
      'email' => $email,
      'firstName' => $firstName,
      'lastName' => $lastName,
      'surnName' => $surnName,
      'dateOfBirth'=> $dateOfBirth
    ];
  }
  mysqli_stmt_close($stmt);
  return $user;
}



function updateUserData($id, $login, $email, $firstName, $lastName, $surnName, $dateOfBirth) {
  global $link;
  $sql = "UPDATE `users` SET `login` = '$login',
  `email` = '$email',
  `firstName` = '$firstName',
  `lastName` = '$lastName',
  `surnName` = '$surnName',
  `dateOfBirth` = '$dateOfBirth'
  WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserLogin($id, $login) {
  global $link;
  $sql = "UPDATE `users` SET `login` = '$login' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserFirstName($id, $firstName) {
  global $link;
  $sql = "UPDATE `users` SET `firstName` = '$firstName' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserLastName($id, $lastName) {
  global $link;
  $sql = "UPDATE `users` SET `lastName` = '$lastName' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserSurnName($id, $surnName) {
  global $link;
  $sql = "UPDATE `users` SET `surnName` = '$surnName' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserDate($id, $dateOfBirth) {
  global $link;
  $sql = "UPDATE `users` SET `dateOfBirth` = '$dateOfBirth' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function updateUserPassword($id, $password) {
  global $link;
  $sql = "UPDATE `users` SET `password` = '$password' WHERE `id` = '$id'";
  if (!$result = mysqli_query($link, $sql)) return false;
  return $result;
}

function searchProducts($data) {
  global $link;
  $products = [];
  $sql = "SELECT id, title, author, pubyear, price, column_description, image FROM `catalog` WHERE author LIKE CONCAT('%', ?, '%') OR title LIKE CONCAT('%', ?, '%')";
  if (!$stmt = mysqli_prepare($link, $sql)) return false;
  /* связываем параметры с метками */
  mysqli_stmt_bind_param($stmt, "ss", $data, $data);
  /* запускаем запрос */
  mysqli_stmt_execute($stmt);
  /* связываем переменные с результатами запроса */
  mysqli_stmt_bind_result($stmt, $id, $title, $author, $pubyear, $price, $descr, $image);
  /* получаем значения */

  while(mysqli_stmt_fetch($stmt)) {
    $products[] = [
      'id' =>$id,
      'title' => $title,
      'author' => $author,
      'pubyear' => $pubyear,
      'price' => $price,
      'column_description' => $descr,
      'image'=> $image
    ];
  }
  return $products;
  
}