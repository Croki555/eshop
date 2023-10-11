<?
session_start();
require "inc/lib.inc.php";
require "inc/config.inc.php";

if($_SERVER['REQUEST_METHOD'] = 'post') {
  $idUser = $_SESSION['user']['id'];
  $idProduct = $_SESSION['user']['idProduct'];
  @$rating = clearInt($_POST['rating']);
  $comment = clearStr($_POST['comment']);
  if(!empty($comment)) {
    if(addCommentToProduct($idProduct, $idUser, $comment, $rating)) {
      header("Location: " . $_SERVER['HTTP_REFERER']);
    };
  } else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
  }
}