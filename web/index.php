<?php
ini_set('error_report', true);
ini_set('display_errors', true);

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(dirname(__FILE__) . DS . '..'));
define('CORE_PATH', BASE_PATH . DS . 'core');

// loader 
require_once CORE_PATH . DS . 'Ulteamee/Loader/Loader.php';
Ulteamee_Loader_Loader::register();

require_once CORE_PATH . DS . 'vendors/Twig/Autoloader.php';
Twig_Autoloader::register();

$registry = Ulteamee_Registry_Registry::getInstance();
$registry->set('toto', 2);

print_r($registry->get('toto'));
?>
