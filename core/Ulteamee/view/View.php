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
 * @subpackage  Ulteamee_View
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 * @link		http://www.ulteamee-project.org
 * @version		$Id:$
 */
/**
 * @package     Ulteamee
 * @subpackage  Ulteamee_View
 * @author      el.iterator <el.iterator@ulteamee-project.org>
 * @copyright	Copyright (c) 2010 Ulteamee project
 * @license		http://www.ulteamee-project.org/user_guide/license.html
 */
class Ulteamee_View {
	protected static $_vars = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected static $_application;
	
	/**
	 * Renders the template
	 *
	 * @param array $parameters
	 *
	 * @return string
	 */
	public static function render($vars) {
		self::$_application = Ulteamee_Registry::get('app');
		self::$_vars = $vars;
		
		// app template
		$tpl = APPS_PATH . '/' . self::$_application . '/modules/' . Ulteamee_Registry::get('app.' . self::$_application . '.module') . '/templates/' . strtolower(Ulteamee_Registry::get('app.' . self::$_application . '.action')) . 'View.php';
		
		// default template
		// @todo add some checks in this block and return throws Exception ?!
		if (!file_exists($tpl) || !is_readable($tpl)) {
			$tpl = ULTEAMEE_CORE_PATH . '/controller/modules/' . Ulteamee_Registry::get('app.' . self::$_application . '.module') . '/templates/' . strtolower(Ulteamee_Registry::get('app.' . self::$_application . '.action')) . 'View.php';
		}
		
		return self::_return_content($tpl);
	}
	
	/**
	 *
	 * @param <type> $tpl
	 * @return <type> 
	 */
	static private function _return_content($tpl) {
		$output = file_get_contents($tpl);
		
		return $output;
	}
	
	/**
	 *
	 * @param <type> $partial
	 * @param Request $request
	 * @return <type> 
	 */
	static public function partial($partial) {
		// app template
		$tpl = APPS_PATH . '/' . self::$_application . '/modules/' . Ulteamee_Registry::get('app.' . self::$_application . '.module') . '/templates/' . $partial;
		array_map(null, self::$_vars);
		
		extract(self::$_vars);
		
		// @todo add some checks in this block and return throws Exception ?!
		if (file_exists($tpl) || is_readable($tpl)) {
			include $tpl;
		}
	
	}
}