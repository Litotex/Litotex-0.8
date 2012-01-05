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

class buildingPluginHandler extends plugin_handler{
	protected $_name = "buildings";
	protected $_location = "buildings";
	protected $_cacheLocation = "../cache/buildings.cache.php";
	protected $_currentFile = __FILE__;
}

class buildingDependencyPluginHandler extends plugin_handler{
	protected $_name = 'dependencies';
	protected $_location = 'dependencies';
	protected $_cacheLocation = "../cache/buildingDependencies.cache.php";
	protected $_currentFile = __FILE__;
}

class building{
	private $_ID;
	private $_data;
	private $_changed = false;
	private $_initialized = false;
	private $_pluginHandler = false;
	private $_plugins = array();
	private $_timeFormula = '';
	private $_pointsFormula = '';
	private $_dependencyPluginHandler = false;
	public function __construct($buildingID){
		$data = package::$db->Execute("SELECT `name`, `race`, `plugin`, `pluginPreferences`, `timeFormula`, `pointsFormula` FROM `lttx_buildings` WHERE `ID` = ?", array($buildingID));
		if(!isset($data->fields[0]))
			throw new Exception('Building ' . $buildingID .' was not found');
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
		$this->_ID = $buildingID;
		$this->_pluginHandler = new buildingPluginHandler();
		$this->_dependencyPluginHandler = new buildingDependencyPluginHandler();
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
		$resource = new ressource($this->_data['race'], 'building', $this->_ID, false, true);
		$resource->useFormula($level);
		package::$packages->callHook('manipulateBuildingCost', array(&$resource));
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
	public function increaseBuildingLevel($level, territory $territory){
		return $this->castFunction('increaseLevel', array($territory, $level, '$preferences', $this->_ID));
	}
	public function getDependencies($level){
		$dep = package::$db->Execute("SELECT `plugin`, `pluginPreferences` FROM `lttx_building_dependencies` WHERE `sourceID` = ? AND `level` <= ?", array($this->_ID, (int)$level));
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
		$result = package::$db->Execute("SELECT `ID` FROM `lttx_buildings` WHERE `race` = ?", array($race));
		return self::_getByQuery($result);
	}
	public static function getAll(){
		$result = package::$db->Execute("SELECT `ID` FROM `lttx_buildings`");
		return self::_getByQuery($result);
	}
	private static function _getByQuery($result){
		$return = array();
		if(!$result)
		return false;
		while(!$result->EOF){
			$return[] = new building($result->fields[0]);
			$result->MoveNext();
		}
		return $return;
		//TODO: Cache to make no extra Database connections
	}
	public function addBuilding(){
		
	}
	public function flush(){
		
	}
	
	public function getRace(){
		return new race($this->data['race']);
	}
}