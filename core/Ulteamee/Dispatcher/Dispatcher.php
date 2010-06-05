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
 * @subpackage  Ulteamee_Dispatcher
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id: $
 */
/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Dispatcher
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
define('APPS_PATH', BASE_PATH . '/apps');

class Ulteamee_Dispatcher_Dispatcher {
	/**
	 *
	 * @var unknown_type
	 */
	CONST ROOT_KEY = '/';
	
	/**
	 *
	 * @var unknown_type
	 */
	CONST DEFAULT_MODULE = 'default';
	
	/**
	 *
	 * @var unknown_type
	 */
	CONST DEFAULT_ACTION = 'index';
	
	/**
	 *
	 * @var unknown_type
	 */
	CONST DEFAULT_404_ACTION = '404';
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_uri;
	
	/**
	 * @var unknown_type
	 */
	protected $_module;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_action;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_parameters;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_segments;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_request;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_application;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected static $_registry;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected static $_routes = array ();
	
	/**
	 * Instance of the class
	 * @var void
	 */
	private static $_instance = null;
	
	/**
	 * Constructor
	 *
	 * @param string $uri
	 */
	public function __construct() {
		self::$_registry = Ulteamee_Registry_Registry::getInstance();
		$this->_uri = $_SERVER['REQUEST_URI'];
	}
	
	/**
	 *
	 * @param string $application
	 * @return unknown_type
	 */
	public function init($application = null) {
		echo '<pre>';
		if (null === $application) {
			throw new Exception('No application (frontend, backend) defined');
		}
		
		$this->_application = $application;
		$params = array ();
		$regex = '/[^a-zA-Z0-9]+/i';
		$routes = parse_ini_file(APPS_PATH . '/' . $application . '/config/routes.ini', true);
		self::$_registry->set('apps.' . $application . '.routes', $routes);
		$module = self::DEFAULT_MODULE;
		$action = self::DEFAULT_ACTION;
		
		// Strip slash from the beginning and end of a string
		$this->_uri = trim($this->_uri, '/');
		
		// explode all URI slashes if is not empty
		$uriArray = !empty($this->_uri) ? explode('/', $this->_uri) : '';
		
		if (is_array($uriArray)) {
			// evaluate each $uriArray values
			$this->_segments = array_filter($uriArray);
			
			// build controller, action & parameters
			switch (count($this->_segments)) {
				// we may not have this case
				case 0 :
				case 1 :
					// we have a controller, but no action
					$module = $this->_segments[0];
					break;
				case 2 :
					// we have a controller and an action
					$module = $this->_segments[0];
					$action = $this->_segments[1];
					break;
				default :
					// build parameters
					$parameters = array_slice($this->_segments, 2, count($this->_segments));
					
					// clean
					$parameters = preg_replace($regex, "", array_values($parameters));
					$cntParameters = count($parameters);
					
					for ($i = 0; $i < $cntParameters; $i++) {
						if (($i % 2) == 0) {
							$params[$parameters[$i]] = (!empty($parameters[$i + 1])) ? $parameters[$i + 1] : null;
						}
					}
					break;
			}
		}
		
		$requirements = $matched = array ();
		
		//print_r($routes);
		print $this->_uri . "<br>";
		print_r($routes);
		
		foreach ($routes as $route => $config) {
			if (isset($config['requirements']) && !empty($config['requirements'])) {
				$requirements = $this->_getRequirements($config['requirements']);
			}
			
			if (isset($config['params']) && !empty($config['params'])) {
				$params = $this->_getParams($config['params']);
			}
			
			if (isset($config['url']) && !empty($config['url'])) {
				foreach ($requirements as $key => $value) {
					$config['url'] = str_replace($key, '(' . $value . ')', $config['url']);
				}
			}
			
			$pattern = '/^' . str_replace('/', '\/', (trim(str_replace('*', '?(.*)', $config['url']), '/'))) . '$/';
			
			if (preg_match($pattern, $this->_uri)) {
				$module = $params['module'];
				$action = $params['action'];
			}
		}
		
		unset($config);
		unset($routes);
		
		$$module = (isset($module) && !empty($module)) ? preg_replace($regex, "", $module) : null;
		$action = (isset($action) && !empty($action)) ? preg_replace($regex, "", $action) : null;
		$parameters = (isset($params) && !empty($params)) ? $params : null;
		
		print 'BEFORE module: ' . $module . ' && action: ' . $action . '<br>';
		
		
		// no module exists
		// use default module & action
		if (null === $module) {
			$module = self::DEFAULT_MODULE;
			
			if (null === $action) {
				$action = self::DEFAULT_ACTION;
			} else {
				$action = self::DEFAULT_404_ACTION;
			}
			
			$class = $module . 'Actions';
			$fileToInclude = CORE_PATH . '/controller/modules/' . $module . '/' . $class . '.class.php';
			
		} else {
			$class = $module . 'Actions';
			if (null === $action) {
				$action = self::DEFAULT_ACTION;
			}
			
			$fileToInclude = APPS_PATH . '/' . $application . '/modules/' . $module . '/' . $class . '.class.php';
		}
		
		print $fileToInclude.'<br>';
		
		// checks if $fileToInclude exists and readable otherwise use our default module class and 404 action
		if (!is_file($fileToInclude) or !is_readable($fileToInclude)) {
			$module = self::DEFAULT_MODULE;
			$action = self::DEFAULT_404_ACTION;
			$class = $module . 'Actions';
			$fileToInclude = CORE_PATH . '/controller/modules/' . $module . '/' . $class . '.class.php';
		}
		
		print 'AFTER module: ' . $module . ' && action: ' . $action . '<br>';
		
		
		// include module class file
        require_once $fileToInclude;

        // instantiate controller class
        if(!class_exists($class) && !interface_exists($class)) {
            throw new Exception(sprintf(__NKO_FRONT_CONTROLLER_CLASS_NOT_FOUND__, $class, $fileToInclude));
        } else { 
            $this->_action_controller =& new $class;
        }
		
		
		self::$_registry->set('apps.' . $application . '.module', $module);
		self::$_registry->set('apps.' . $application . '.action', $action);
		self::$_registry->set('apps.' . $application . '.parameters', $parameters);
		//echo '<pre>';
		//print count($this->_segments) . ' ' . $this->_module . ' ' . $this->_action . "<br>";
		//print_r($this);
		exit();
	}
	
	/**
	 *
	 * @param $string
	 * @return unknown_type
	 */
	private function _getRequirements($string) {
		$formattedRequirementsArray = array ();
		if (!empty($string)) {
			$requirements = explode(',', $string);
			foreach ($requirements as $r) {
				list ($key, $value) = explode(' ', trim($r));
				$key = ':' . str_replace(':', '', $key);
				$formattedRequirementsArray[$key] = $value;
			}
		}
		return $formattedRequirementsArray;
	}
	
	/**
	 *
	 * @param $string
	 * @return unknown_type
	 */
	private function _getParams($string) {
		$formattedParamsArray = array ();
		if (!empty($string)) {
			$params = explode(',', $string);
			foreach ($params as $p) {
				list ($key, $value) = explode(' ', trim($p));
				$key = str_replace(':', '', $key);
				$formattedParamsArray[$key] = $value;
			}
		}
		return $formattedParamsArray;
	}
	
	function invoke($application, $config) {
		if (!isset($config['controller'])) {
			trigger_error("No controller configured for the application " . $application, E_USER_ERROR);
			exit();
		}
		echo '<pre>';
		print_r($config);
		$this->_params($config, $this->_getValues($application));
		/*require_once (CONTROLLERS . $config['controller'] . CLASS_FILE);
		 $class = basename($config['controller']);
		 $controller = &new $class();

		 if (is_subclass_of($controller, 'Controller')) {
		 switch ($_SERVER['REQUEST_METHOD']) {
		 case 'GET':
		 $controller->doGet();
		 break;
		 case 'POST':
		 $controller->doPost();
		 break;
		 default:
		 trigger_error('Unhandled request method: ' . $_SERVER['REQUEST_METHOD'], E_USER_ERROR);
		 }
		 }
		 */
	}
	
	/**
	 * Parse the URI
	 *
	 * @param string $uri
	 *
	 * @return boolean
	 */
	protected function parseUri($uri) {
		// Strip whitespace (or other characters) from the beginning and end of a string
		$this->uri = trim($this->uri, '/');
		// explode all URI slashes if is not empty
		$uriArray = !empty($this->uri) ? explode('/', $this->uri) : '';
		// evaluate each $uriArray values
		$this->segments = array_filter($uriArray);
		// construct module, action & parameters
		switch (count($this->segments)) {
			// we may not have this case
			case 0 :
				break;
			case 1 :
				// no module & action, use default module & action
				$this->request['module'] = $this->module;
				$this->request['action'] = $this->action;
				break;
			case 2 :
				// we have a module, but no action
				$this->request['module'] = $this->segments[1];
				$this->request['action'] = $this->action;
				break;
			case 3 :
				// we have a module and an action
				$this->request['module'] = $this->segments[1];
				$this->request['action'] = $this->segments[2];
				break;
			default :
				$this->request['module'] = $this->segments[1];
				$this->request['action'] = $this->segments[2];
				// build parameters
				$parameters = array_slice($this->segments, 3, count($this->segments));
				$parameters = array_values($parameters);
				$cntParameters = count($parameters);
				for ($i = 0; $i < $cntParameters; $i++) {
					if (!empty($parameters[$i])) {
						$this->request['parameters'][$parameters[$i]] = (!empty($parameters[$i + 1])) ? $parameters[$i + 1] : null;
						array_shift($parameters);
					}
				}
				break;
		}
		Ulteamee_Registry_Registry::set('module', $this->request['module']);
		Ulteamee_Registry_Registry::set('action', $this->request['action']);
		return false;
	}
}