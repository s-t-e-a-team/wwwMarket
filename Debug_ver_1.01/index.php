<?php
//Конфиг
if (is_file('config.php')) {
	require_once('config.php');
}

//Вспомогательные библиотечки папки library
function library($class) {
	$file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

	if (is_file($file)) {
		include_once($file);

		return true;
	} else {
		return false;
	}
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

//Базисное двигло под mvc :) В принципе по названиям - понятно, кто, что делает
require_once(DIR_SYSTEM . 'engine/action.php');
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/event.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/loader.php');
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');
require_once(DIR_SYSTEM . 'engine/proxy.php');

//Вспомогательные штуки для конвертов (json, utf8 и т.д.)
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');

//Для доступа синглтонов "отовсюду"
$registry = new Registry();

//События
$event = new Event($registry);
$registry->set('event', $event);

//Синглтон загрузчика
$loader = new Loader($registry);
$registry->set('load', $loader);

//Синглтон запроса
$registry->set('request', new Request());

//Синглтон ответа
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

//БД
$registry->set('db', new DB('mysqli', DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT));

//Сессия
$session = new Session();
$session->start();
$registry->set('session', $session);

//Кэш - а че бы нет? - пусть тоже будет
$registry->set('cache', new Cache('file', 3600));

//Синглтон для построения url-ок
$registry->set('url', new Url('http://wwwmarket.elank.ru/', 'https://wwwmarket.elank.ru/'));

//Для тайтлов, метатегов и прочей лабуды :)
$registry->set('document', new Document());

//Идем в контроллер :)
$controller = new Front($registry);
$controller->addPreAction(new Action('startup/session'));
$controller->addPreAction(new Action('startup/error'));
$controller->addPreAction(new Action('startup/seo_url'));

$controller->dispatch(new Action('startup/router'), new Action('error/not_found'));

$response->output();
