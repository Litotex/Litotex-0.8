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
class plugin_dependencyBuilding extends plugin{
	public static $handlerName = 'dependencies';
	public static $name = 'Ressource';
	public static $availableFunctions = array('checkDependency');
	public static function checkDependency(territory $territory, $preferences){
		if(!self::checkPreferences($preferences))return false;
		$level = $territory->getBuildingLevel($preferences['sourceID']);
		if($level >= $preferences['minLevel'])
			return true;
		return false;
	}
	public static function checkPreferences($preferences){
		if(!isset($preferences['sourceID']) || !isset($preferences['minLevel']))
			return false;
		return true;
	}
}