<?php
ini_set('error_report', true);
ini_set('display_errors', true);

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(dirname(__FILE__) . DS . '..'));
define('APPS_PATH', BASE_PATH . DS . 'apps');
define('CORE_PATH', BASE_PATH . DS . 'core');
define('ULTEAMEE_CORE_PATH', CORE_PATH . DS . 'Ulteamee');

// loader
require_once ULTEAMEE_CORE_PATH . '/Loader/Loader.php';
Ulteamee_Loader::register();

// dispatcher
$dispatcher = new Ulteamee_Dispatcher();
$dispatcher->init('frontend');

// templater
require_once CORE_PATH . '/vendors/Twig/Autoloader.php';
Twig_Autoloader::register();

?>
