<?php
if (! defined ( 'BASE_PATH' ))
	exit ( 'No direct script access allowed' );
/**
 * This file is part of the Ulteamee project package.
 *
 * Ulteamee
 *
 * An open source Clan Management System for PHP 5.2+ and newer
 *
 * @package     Ulteamee
 * @subpackage  Autoload
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @since		Version 0.1b
 *
 */

/**
 * Ulteamee_Autoload_Autoload
 *
 * @author el.iterator <el.iterator@ulteamee-project.org>
 */
class Ulteamee_Autoload_Autoload {
	/**
	 * Instance of the class
	 * @var void
	 */
	private static $_instance = null;
	
	/**
	 * instantiate this class is not allowed
	 */
	private function __construct() {
		// nully existing autoload
		spl_autoload_register(null, false);
		
		// allowed extensions to autoload
		spl_autoload_extensions('.php, .class.php');
	}
	
	/**
	 * Cloning is not allowed
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
			self::$_instance = new self ( );
		}
		
		return self::$_instance;
	}
	
	/**
	 * autoload method
	 * 
	 * @param string $class
	 * @return unknown_type
	 */
	private function __autoload($class) {
		
		if(!isset($class)) {
			return false;
		}
		
		$classPath = BASE_PATH . str_replace('_', '/',$class);
		
		
		//if(!file_exists($class))
	}
	
	
	
}