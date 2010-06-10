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
 * @subpackage  Ulteamee_Request
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id: $
 */
/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_Request
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
class Ulteamee_Request {
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_uri;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_cookie;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_post;
	
	/**
	 * @var unknown_type
	 */
	protected $_get;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMethod() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getUri() {
		$uri = $_SERVER['REQUEST_URI'];
		$regex = '/[^a-zA-Z0-9]+/i';
		$cleanedUri = preg_replace($regex, '', $uri);
		
		return $uri;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFiles() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCookie() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSession() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getQueryString() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function geGet() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPost() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isPost() {
	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isGet() {
	
	}
}