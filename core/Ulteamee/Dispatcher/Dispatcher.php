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
class Ulteamee_Dispatcher {
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
	CONST CONTROLLER_CLASS_NOT_FOUND = 'Unable to find class "%s" in file "%s"';
	
	/**
	 * 
	 * @var unknown_type
	 */
	CONST CONTROLLER_METHOD_NOT_FOUND = 'Unable to find method (action) "%s" in file "%s"';
	
	protected $_request;
	
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
	protected $_application;
	
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
		$this_request = new Ulteamee_Request();
		$this->_uri = $this_request->getUri();
	}
	
	/**
	 *
	 * @param string $application
	 * @return unknown_type
	 */
	public function init($application = null) {
		
		if (null === $application) {
			throw new Exception('No application (frontend, backend, test) defined');
		}
		
		$this->_application = $application;
		$params = array ();
		$routes = parse_ini_file(APPS_PATH . '/' . $application . '/config/routes.ini', true);
		Ulteamee_Registry::set('apps.' . $application . '.routes', $routes);
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
					//$parameters = preg_replace($regex, "", array_values($parameters));
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
		
		$module = (isset($module) && !empty($module)) ? $module : null;
		$action = (isset($action) && !empty($action)) ? $action : null;
		$parameters = (isset($params) && !empty($params)) ? $params : null;
		
		// no module exists
		// use default module & action
		$class = $module . 'Actions';
		
		if (null === $module) {
			$module = self::DEFAULT_MODULE;
			
			if (null === $action) {
				$action = self::DEFAULT_ACTION;
			} else {
				$action = self::DEFAULT_404_ACTION;
			}
			
			$fileToInclude = CORE_PATH . '/controller/modules/' . $module . '/' . $class . '.php';
		
		} else {
			if (null === $action) {
				$action = self::DEFAULT_ACTION;
			}
			
			$fileToInclude = APPS_PATH . '/' . $application . '/modules/' . $module . '/' . $class . '.php';
		}
		
		// checks if $fileToInclude exists and readable otherwise use our default module class and 404 action
		if (!is_file($fileToInclude) && !is_readable($fileToInclude)) {
			$module = self::DEFAULT_MODULE;
			$action = self::DEFAULT_404_ACTION;
			$class = $module . 'Actions';
			$fileToInclude = ULTEAMEE_CORE_PATH . '/controller/modules/' . $module . '/' . $class . '.php';
		}
		
		Ulteamee_Registry::set('app', $application);
		Ulteamee_Registry::set('app.' . $application . '.module', $module);
		Ulteamee_Registry::set('app.' . $application . '.action', $action);
		Ulteamee_Registry::set('app.' . $application . '.parameters', $parameters);
		
		// include module class file
		require_once $fileToInclude;
		
		// check and instantiate controller class
		if (!class_exists($class) && !interface_exists($class)) {
			throw new Ulteamee_Exception(sprintf(self::CONTROLLER_CLASS_NOT_FOUND, $class, $fileToInclude));
		}
		$moduleClass = & new $class();
		
		$moduleClass->execute();
		
		/*
		 * if (!method_exists($moduleClass, $action)) {
			throw new Ulteamee_Exception(sprintf(self::CONTROLLER_METHOD_NOT_FOUND, $action, $fileToInclude));
		}		
		 */	
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
}