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
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package     Ulteamee
 * @subpackage  Ulteamee_Registry
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id: $
 */

/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Registry
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
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
	 *
	 * @return void
	 */
	private function __construct() {
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
	 * Stores a new variable
	 *
	 * @param string $name The name of the variable
	 * @param mixed $value The value to store
	 */
	public function set($name, $value) {
		self::$_instance->_variables[$name] = $value;
	}
	
	/**
	 * Retrieves a variable's value
	 *
	 * @param string $name The name of the variable
	 * @return mixed
	 */
	public function get($name, $default = null) {
		if (array_key_exists($name, self::$_instance->_variables)) {
			$default = self::$_instance->_variables[$name];
		}
		return $default;
	}
	
	/**
	 * Checks if a variable exists
	 *
	 * @param string $name The name of the variable
	 * @return boolean
	 */
	public function has($name) {
		if (array_key_exists($name)) {
			return $name;
		}
		return false;
	}
	
	/**
	 * Retrieves all stored variables
	 *
	 * @return array
	 */
	public function get_variables() {
		if (self::$_instance->_variables) {
			return true;
		}
		return false;
	}
	
	/**
	 * Deletes a variable
	 *
	 * @param string $name The variable's name to delete
	 */
	public function delete($name) {
		if (self::$_instance->_variables[$name]) {
			unset(self::$_instance->_variables[$name]);
		}
	}
	
	/**
	 * Erases all stored variables
	 */
	public function clear() {
		self::$_instance->_variables = array ();
	}
}