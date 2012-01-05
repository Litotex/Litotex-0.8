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
class explorePluginHandler extends plugin_handler{
	protected $_name = "explores";
	protected $_location = "explores";
	protected $_cacheLocation = "../cache/explore.cache.php";
	protected $_currentFile = __FILE__;
}

class exploreDependencyPluginHandler extends plugin_handler{
	protected $_name = 'dependencies';
	protected $_location = 'dependencies';
	protected $_cacheLocation = "../cache/exploreDependencies.cache.php";
	protected $_currentFile = __FILE__;
}

class explore{
	private $_ID;
	private $_data;
	private $_changed = false;
	private $_initialized = false;
	private $_pluginHandler = false;
	private $_plugins = array();
	private $_timeFormula = '';
	private $_pointsFormula = '';
	private $_dependencyPluginHandler = false;
	public function __construct($exploreID){
		$data = package::$db->Execute("SELECT `name`, `race`, `plugin`, `pluginPreferences`, `timeFormula`, `pointsFormula` FROM `lttx".package::$dbn."_explores` WHERE `ID` = ?", array($exploreID));
		if(!isset($data->fields[0]))
			throw new Exception('Explore ' . $exploreID .' was not found');
		$plugin = $data->fields[2];
		$pluginPreferences = $data->fields[3];
		if(($plugin = unserialize($plugin)) === false)
			return;
		if(($pluginPreferences = unserialize($pluginPreferences)) === false)
			return;
		foreach($plugin as $i => $pluginName){
			if(!isset($pluginPreferences[$i]))
				return;
			$this->_plugins[$pluginName] = $pluginPreferences[$i];
		}
		$this->_initialized = true;
		$this->_data['name'] = $data->fields[0];
		$this->_data['race'] = $data->fields[1];
		$this->_ID = $exploreID;
		$this->_pluginHandler = new explorePluginHandler();
		$this->_dependencyPluginHandler = new exploreDependencyPluginHandler();
		$this->_timeFormula = $data->fields[4];
		$this->_pointsFormula = $data->fields[5];
	}
	public function __destruct(){
		$this->flush();
	}
	public function getName(){
		if(!$this->initialized())
			return false;
		return $this->_data['name'];
	}
	public function getCost($level){
		if(!$this->_initialized)
			return false;
		$resource = new ressource($this->_data['race'], 'explore', $this->_ID, false, true);
		$resource->useFormula($level);
		package::$packages->callHook('manipulateExploreCost', array(&$resource));
		return $resource;
	}
	public function initialized(){
		return (bool)$this->_initialized;
	}
	public function castFunction($function, $params){
		$return = true;
		$replaceKeys = array();
		foreach($params as $i => $param){
			if($param == '$preferences')
				$replaceKeys[] = $i;
		}
		foreach($this->_plugins as $pluginName => $pluginPreferences){
			foreach($replaceKeys as $replaceKey){
				$params[$replaceKey] = $pluginPreferences;
			}
			if(!$this->_pluginHandler->callPluginFunc($pluginName, $function, $params))
				$return = false;
		}
		return $return;
	}
	public function getBuildTime($level){
		if(!math::verifyFormula($this->_timeFormula))
			return false;
		$formula = math::replaceX($this->_timeFormula, (int)$level);
		return math::calculateString($formula);
	}
	public function getPoints($level){
		if(!math::verifyFormula($this->_pointsFormula))
			return false;
		$formula = math::replaceX($this->_pointsFormula, (int)$level);
		return math::calculateString($formula);
	}
	public function increaseExploreLevel($level, territory $territory){
		return $this->castFunction('increaseLevel', array($territory, $level, '$preferences', $this->_ID));
	}
	public function getDependencies($level){
		$dep = package::$db->Execute("SELECT `plugin`, `pluginPreferences` FROM `lttx".package::$dbn."_explore_dependencies` WHERE `sourceID` = ? AND `level` <= ?", array($this->_ID, (int)$level));
		$return = array();
		if(!$dep)
			return false;
		while(!$dep->EOF){
			$return[] = array($dep->fields[0], unserialize($dep->fields[1]));
			$dep->MoveNext();
		}
		return $return;
	}
	public function checkDependencies(territory $territory, $level){
		$depList = $this->getDependencies($level);
		$return = true;
		foreach($depList as $dep){
			if(!$this->_dependencyPluginHandler->callPluginFunc($dep[0], 'checkDependency', array($territory, $dep[1])))
				$return = false;
		}
		return $return;
	}
	public static function getAllByRace($race){
		$result = package::$db->Execute("SELECT `ID` FROM `lttx".package::$dbn."_explores` WHERE `race` = ?", array($race));
		$return = array();
		if(!$result)
			return false;
		while(!$result->EOF){
			$return[] = new explore($result->fields[0]);
			$result->MoveNext();
		}
		return $return;
		//TODO: Cache to make no extra Database connections
	}
	public function addExplore(){
		
	}
	public function flush(){
		
	}
}