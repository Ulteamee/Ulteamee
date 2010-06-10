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
 * @subpackage  Ulteamee_Controller
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id:$
 */
/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Controller
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
abstract class Ulteamee_Controller {
	/**
	 * 
	 * @var unknown_type
	 */
	protected static $_application;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_vars = array ();
	
	/**
	 * 
	 * @param $action
	 * @return unknown_type
	 */
	public function execute() {
		self::$_application = Ulteamee_Registry::get('app');
		
		// action
		$action = 'execute' . ucfirst(Ulteamee_Registry::get('app.' . self::$_application . '.action'));
		
		if (!is_callable(array ($this, $action))) {
			$this->_doError404();
		} else {
			$this->$action(Ulteamee_Registry::get('app.' . self::$_application . '.parameters'));
		}
	}
	
	/**
	 * 
	 */
	private function _doError404() {
		include_once ULTEAMEE_CORE_PATH . '/controller/modules/' . Ulteamee_Registry::get('app.' . self::$_application . '.module') . '/templates/' . strtolower(Ulteamee_Registry::get('app.' . self::$_application . '.action')) . 'View.php';
	}
	
	/**
	 * Sets a new variable
	 *
	 * @param string $name the name of the variable
	 * @param mixed $value the value
	 */
	public function set($name, $value) {
		$this->_vars[$name] = Ulteamee_Util::clean($value);
	}
	
	/**
	 * Sets a new variable (magic method)
	 *
	 * @param string $name the name of the variable
	 * @param mixed $value the value
	 */
	public function __set($name, $value) {
		$this->_vars[$name] = Util::clean($value);
	}
	
	/**
	 * Destructor
	 */
	public function __destruct() {
		
		$content = Ulteamee_View::render($this->_vars);
		
		$this->_vars = array_merge($this->_vars, array ('raw_content' => $content));
		
		extract($this->_vars);
		
		$layout = APPS_PATH . '/' . self::$_application . '/templates/layout.php';
		
		ob_start();
		
		// @todo add some checks in this block and return throws Exception ?!
		if (file_exists($layout) || is_readable($layout)) {
			include $layout;
		}
		
		$_parsed = ob_get_contents();
		
		ob_end_clean();
		
		eval("?>" . $_parsed . "<?");
	}
	
	/**
	 * abstract function 
	 */
	abstract public function executeIndex();
}

