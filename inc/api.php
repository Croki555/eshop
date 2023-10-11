<?
require "../eshop/inc/lib.inc.php";
require "../eshop/inc/config.inc.php";

if($_GET['id']) {
  $order = getOrder($_GET['id']);
  echo json_encode($order, JSON_UNESCAPED_UNICODE);
}
