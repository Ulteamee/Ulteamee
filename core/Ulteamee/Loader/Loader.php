<?php
if (!defined('BASE_PATH'))
	exit('No direct script access allowed');
/**
 * This file is part of the Ulteamee project package.
 *
 * Ulteamee
 *
 * An open source Content Management System for PHP 5.2+ and newer
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
 * @subpackage  Ulteamee_Loader
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id: $
 */
/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Loader
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
class Ulteamee_Loader {
	/**
	 * SPL configs and register
	 *
	 * @return void
	 */
	public static function register() {
		// SPL nullify existing autoload
		spl_autoload_register(null, false);
		
		// SPL allowed extensions to autoload
		spl_autoload_extensions('.php, .class.php');
		
		// SPL autoload register
		spl_autoload_register(array (new self(), 'autoload'));
	}
	
	/**
	 * Autoloads class
	 *
	 * @param string $class
	 * @return boolean
	 */
	public static function autoload($class) {
		if (0 !== strpos($class, 'Ulteamee')) {
			return false;
		}
		
		if (!isset($class)) {
			return false;
		}
		
		list($dir, $file) = explode('_', $class);
		$classPath = CORE_PATH . DS .  $dir . '/' . $file. '/' . $file .  '.php';	
		
		// is class path exist?
		if (!file_exists($classPath)) {
			return false;
		}
		
		require $classPath;
		
		// Check if class/interface has already been declared
		if (!class_exists($class) && !interface_exists($class)) {
			return false;
		}
		
		return true;
	}
}