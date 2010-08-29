<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
class package_timer extends package{
	protected $_availableActions = array();
	private static $_startTime = false;
	public function __action_main(){
		return true;
	}
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'displayTimer', 0);
		self::_registerHook(__CLASS__, 'loadCore', 0);
		return true;
	}
	public static function __hook_displayTimer(){
		echo round(microtime(true) - self::$_startTime, 5);
	}
	public static function __hook_loadCore(){
		self::$_startTime = microtime(true);
	}
}