<?php
ini_set('error_report', true);
ini_set('display_errors', true);

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(dirname(__FILE__) . DS . '..'));
define('CORE_PATH', BASE_PATH . DS . 'core');

// loader
require_once CORE_PATH . DS . 'Ulteamee/Loader/Loader.php';
Ulteamee_Loader_Loader::register();

// dispatcher
$dispatcher = new Ulteamee_Dispatcher_Dispatcher();
$dispatcher->init('frontend');

echo '<pre>';
print_r($dispatcher);

// templater
require_once CORE_PATH . DS . 'vendors/Twig/Autoloader.php';
Twig_Autoloader::register();

?>
