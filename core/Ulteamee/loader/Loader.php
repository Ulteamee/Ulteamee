<?php
if ( ! defined('BASE_PATH'))
	exit('No direct script access allowed');
/**
 * This file is part of the Ulteamee project package.
 *
 * Ulteamee
 *
 * An open source Clan Management System for PHP 5.2+ and newer
 *
 * @package     Ulteamee
 * @subpackage  Ulteamee_Autoload
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id: $
 */

/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Autoload
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
class Ulteamee_Loader_Loader {
	/**
	 * Instance of the class
	 * @var void
	 */
	private static $_instance = null;
	
	/**
	 * Instantiate this class is not allowed
	 *
	 * @return void
	 */
	private function __construct() {
		// nully existing autoload
		spl_autoload_register(null, false);
		
		// allowed extensions to autoload
		spl_autoload_extensions('.php, .class.php');
	}
	
	/**
	 * Cloning is not allowed
	 *
	 * @return void
	 */
	private function __clone() {
	}
	
	/**
	 * Singleton method used to access the object
	 *
	 * @return object Object of the class
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * autoload method
	 *
	 * @param string $class
	 * @return boolean
	 */
	private function __autoload($class) {
		if ( ! isset($class)) {
			return false;
		}
		
		$classPath = CORE_PATH . str_replace('_', '/', $class) . 'class.php';
		
		// is class path exist?
		if ( ! file_exists($classPath)) {
			return false;
		}
		
		require $classPath;
		
		// Check if class/interface has already been declared
		if ( ! class_exists($class) &&  ! interface_exists($class)) {
			return false;
		}
		
		return true;
	}
}