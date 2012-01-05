<?php
/*
 * Copyright (c) 2010 Litotex
 * 
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software and
 * associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
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

define('RESSOURCE_UPDATE_INTERVAL', 10);

class plugin_buildingRessource extends plugin{
	public static $handlerName = 'buildings';
	public static $name = 'Ressource';
	public static $availableFunctions = array('increaseLevel', 'checkPrefs');
	public static function increaseLevel(territory $territory, $newLevel, $pluginPrefs, $buildingID){
		if(!self::checkPrefs($pluginPrefs))
			return false;
		if(!math::verifyFormula($pluginPrefs['formula']))
		return false;
		$formula = math::replaceX($pluginPrefs['formula'], $newLevel);
		$valuePerHour = math::calculateString($formula);
		$value = $valuePerHour / 3600 * RESSOURCE_UPDATE_INTERVAL;
		cron::addIfNotExists('ressource' . $territory->getID() . '_' . $buildingID . '_' . $pluginPrefs['resID'], time(), RESSOURCE_UPDATE_INTERVAL, 'simpleAddition', array($pluginPrefs['resID'], $value, '$intNum', true), array('core_buildings'), $territory->getUser()->getUserID(), false, $territory->getRessources());
		return true;
	}
	public static function checkPrefs($pluginPrefs){
		if(!isset($pluginPrefs['resID']) || !isset($pluginPrefs['formula']))
		return false;
		return true;
	}
}