<?
require "../inc/lib.inc.php";
require "../admin/secure/secure.inc.php";
require "../inc/config.inc.php";
$id = clearInt($_GET['id']);
if(delComment($id)) header("Location: /eshop/admin/comments.php");