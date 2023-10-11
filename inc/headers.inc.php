<?
$title = 'Супер-мега сайт';
$header = "Добро пожаловать на наш сайт!";
// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
@$id = strtolower(strip_tags(trim($_GET['id'])));
@$user = strtolower(strip_tags(trim($_GET['user'])));
// Инициализация заголовков страницы
switch($id){
	case 'contact': 
		$title = 'Контакты';
		$header = 'Обратная связь';
		break;
	case 'about': 
		$title = 'О нас';
		$header = 'О нашем сайте';
		break;
	case 'info': 
		$title = 'Информация';
		$header = 'Информация';
		break;
	case 'log': 
		$title = 'Журнал посещений';
		$header = 'Журнал посещений';
		break;
	case 'gbook': 
		$title = 'Гостевая книга';
		$header = 'Наша гостевая книга';
		break;
	case 'login':
		$title = 'Авторизация';
		$header = 'Вход в личный кабинет';
		break;
}