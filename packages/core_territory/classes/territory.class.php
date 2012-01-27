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

class territory{
	private $_ID = false;
	private $_initialized = false;
	private static $_cache = array();
	private $_data = array('buildings' => false, 'explores' => false, 'ressources' => false, 'user' => false, 'name' => '');
	public function __construct($ID){
		$this->_ID = (int)$ID;
		if(!$this->_getData())
			throw new Exception('Territory ' . $ID . ' was not found');
		$this->_initialized = true;
	}
	public function __destruct(){
		return;
	}
	public static function getUserTerritories(User $user){
		$result = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_territory` WHERE `userID` = ?");
		$result->execute(array($user->getUserID()));
		
		
		$return = array();
		foreach($result as $territory){
			$return[] = new territory($territory[0]);
		}
		return $return;
	}
	private function _getData(){
		if($this->_getCachedData())
			return true;
		$result = Package::$pdb->prepare("SELECT `userID`, `name` FROM `lttx1_territory` WHERE `ID` = ?");
		$result->execute(array($this->_ID));
		if($result->rowCount() == 0)
			return false;
		
		$result = $result->fetch();
		$this->_data['name'] = $result[1];
		$buildings = Package::$pdb->prepare("SELECT `ID`, `buildingID`, `level` FROM `lttx1_territory_buildings` WHERE `territoryID` = ?");
		$buildings->execute(array($this->_ID));
		foreach($buildings as $building){
			try {
				$this->_data['buildings'][$building[0]] = array($building[2], new building($building[1]));
			} catch (Exception $e) {
				//TODO: Debug...
			}
		}
		$explores = Package::$pdb->prepare("SELECT `ID`, `buildingID`, `level` FROM `lttx1_territory_explores` WHERE `territoryID` = ?");
		$explores->execute(array($this->_ID));
		foreach($explores as $explore){
			try {
				$this->_data['explores'][$explore[0]] = array($explore[2], new building($explore[1]));
			} catch (Exception $e) {
				//TODO: Debug...
			}
		}
		try {
			$user = new User($result[0]);
			$this->_data['user'] = $user;
		} catch (Exception $e){
			throw new Exception('The territory ' . $this->_ID . ' does not exist, the player wich is connected to it was not found');
		}
		$ressources = ressource::getTerritoryRess($this);
		$this->_data['ressources'] = $ressources;
		$this->_writeCache();
		return true;
	}
	private function _getCachedData(){
		if(isset(self::$_cache[$this->_ID])){
			$this->_data = self::$_cache[$this->_ID];
			return true;
		} else return false;
	}
	private function _writeCache(){
		self::$_cache[$this->_ID] = $this->_data;
		return true;
	}
	public function setName($newName){
		if(strlen($newName) > 100)
			throw new Exception('The new name has too many signs, the limit is 100');
		Package::$pdb->prepare("UPDATE `lttx1_territory` SET `name` = ? WHERE `ID` = ?")->execute(array($newName, $this->_ID));
		$this->_data['name'] = $newName;
		self::$_cache[$this->_ID]['name'] = $newName;
		return true;		
	}
	public function getBuildings(){
		return $this->_data['buildings'];
	}
	public function getExplores(){
		return $this->_data['explores'];
	}
	public function getUser(){
		return $this->_data['user'];
	}
	public function getRessources(){
		return $this->_data['ressources'];
	}
	public function getName(){
		return $this->_data['name'];
	}
	public function getID(){
		return $this->_ID;
	}
	public function increaseBuildingLevel($buildingID){
		if(!isset($this->_data['buildings'][$buildingID])){
			try {
				$building = new building($buildingID);
			}catch (Exception $e){
				return false;
			}
		}else
			$building = $this->_data['buildings'][$buildingID][1];
		$newLevel = $this->getNextBuildingLevel($buildingID);
		if(!$building->checkDependencies($this, $newLevel))
			return false;
		if(!$this->checkBuildingResources($buildingID, $newLevel)){
			return false;
		}
		$this->_decreaseBuildingRessources($buildingID, $newLevel);
		return $this->_addNewBuildingJob($buildingID, $newLevel);
	}
	public function checkBuildingResources($buildingID, $level){
		if(!isset($this->_data['buildings'][$buildingID])){
			try {
				$building = new building($buildingID);
			}catch (Exception $e){
				return false;
			}
		}else
			$building = $this->_data['buildings'][$buildingID][1];
		$needed = $building->getCost($level);
		$needed->useFormula($level);
		return ressource::checkFit($this->getRessources(), $needed);
	}
	private function _addNewBuildingJob($buildingID, $newLevel){
		$buildingID = (int)$buildingID;
		$newLevel = (int)$newLevel;
		if(!isset($this->_data['buildings'][$buildingID])){
			try {
				$building = new building($buildingID);
			}catch (Exception $e){
				return false;
			}
		}else
			$building = $this->_data['buildings'][$buildingID][1];
		$time = $this->_getNextBuildingSlotTime();
		return cron::add('buildingQueue' . $this->_ID . '_' . $buildingID . '_' . $newLevel, $time + $building->getBuildTime($newLevel), 0, 'setBuildingLevel', array($buildingID, $newLevel), array('core_territory'), $this->getUser(), array($this->getUser()), $this);
	}
	private function _getNextBuildingSlotTime(){
		$queue = $this->getBuildingWorkQueue();
		if(count($queue) == 0)
			return time();
		$highest = time();
		foreach ($queue as $item){
			if($item[1] > $highest)
				$highest = $item[1];
		}
		return $highest;
	}
	public function setBuildingLevel($buildingID, $newLevel){
		$buildingID = (int)$buildingID;
		$newLevel = (int)$newLevel;
		$new = false;
		if(!isset($this->_data['buildings'][$buildingID])){
			try {
				$building = new building($buildingID);
			}catch (Exception $e){
				return false;
			}
			$new = true;
		}else
			$building = $this->_data['buildings'][$buildingID][1];
		$building->increaseBuildingLevel($newLevel, $this);
		if($new){
			$result = Package::$pdb->prepare("INSERT INTO `lttx1_territory_buildings` (`territoryID`, `buildingID`, `level`) VALUES (?, ?, ?)")->execute(array($this->_ID, $buildingID, $newLevel));
		}else{
			$result = Package::$pdb->prepare("UPDATE `lttx1_territory_buildings` SET `level` = ? WHERE `territoryID` = ? AND `buildingID` = ?")->execute(array($newLevel, $this->_ID, $buildingID));
		}
		unset(self::$_cache[$this->_ID]);
		$this->_getData();
		return true;
	}
	private function _decreaseBuildingRessources($buildingID, $level){
		if(!isset($this->_data['buildings'][$buildingID])){
			try {
				$building = new building($buildingID);
			}catch (Exception $e){
				return false;
			}
		}else
			$building = $this->_data['buildings'][$buildingID][1];
		$needed = $building->getCost($level);
		$needed->useFormula($level);
		ressource::subtract($this->getRessources(), $needed);
	}
	public function getNextBuildingLevel($buildingID){
		if(!isset($this->_data['buildings'][$buildingID]))
			return false;
		$queue = $this->getBuildingWorkQueue($buildingID);
		if(count($queue) == 0){
			return $this->getBuildingLevel($buildingID)+1;
		}
		$highest = $this->getBuildingLevel($buildingID);
		foreach($queue as $item){
			if($item[2] > $highest)
				$highest = $item[2];
		}
		return $highest + 1;
	}
	public function getBuildingLevel($buildingID){
		if(!isset($this->_data['buildings'][$buildingID]))
			return 0;
		return (int)$this->_data['buildings'][$buildingID][0];
	}
	public function getBuildingWorkQueue($buildingID = false){
		$return = array();
		$searchString = 'buildingQueue' . $this->_ID . '_';
		if($buildingID)
			$searchString .= (int)$buildingID . '_%';
		else
			$searchString .= '%';
		$tasks = cron::searchByTextID($searchString);
		if(!$tasks)
			return array();
		foreach($tasks as $task){
			$return[] = array($this, $task['nextInt'], (int)preg_replace('/buildingQueue[0-9]*_[0-9]*_([0-9]*)/', "$1", $task['textID']), new building(preg_replace('/buildingQueue[0-9]*_([0-9]*)_[0-9]*/', "$1", $task['textID'])));
		}
		return $return;
	}
}