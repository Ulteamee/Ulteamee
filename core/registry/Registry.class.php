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
 * @subpackage  Registry
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @since		Version 0.1b
 *
 */

/**
 * Ulteamee_Registry_Registry
 *
 * @author el.iterator <el.iterator@ulteamee-project.org>
 */
class Ulteamee_Registry_Registry {
	/**
	 * Array of variables
	 * @var array
	 */
	private static $_variables = array ();
	
	/**
	 * Instance of the class
	 * @var void
	 */
	private static $_instance = null;
	
	/**
	 * instantiate this class is not allowed
	 */
	private function __construct() {
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
	 * Stores a new variable
	 *
	 * @param string $name The name of the variable
	 * @param mixed $value The value to store
	 */
	public function set($name, $value) {
		self::$_instance->_variables [$name] = $value;
	}
	
	/**
	 * Retrieves a variable's value
	 *
	 * @param string $name The name of the variable
	 * @return mixed
	 */
	public function get($name) {
		return (array_key_exists ( $name, self::$_instance->_variables )) ? self::$_instance->_variables [$name] : null;
	}
	
	/**
	 * Checks if a variable exists
	 *
	 * @param string $name The name of the variable
	 * @return boolean
	 */
	public function has($name) {
		return array_key_exists ( $name );
	}
	
	/**
	 * Retrieves all stored variables
	 *
	 * @return array
	 */
	public function get_variables() {
		return self::$_instance->_variables;
	}
	
	/**
	 * Deletes a variable
	 *
	 * @param string $name The variable's name to delete
	 */
	public function delete($name) {
		unset ( self::$_instance->_variables [$name] );
	}
	
	/**
	 * Erases all stored variables
	 */
	public function clear() {
		self::$_instance->_variables = array ();
	}
}