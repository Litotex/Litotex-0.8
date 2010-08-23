<?php
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
	public static function getUserTerritories(user $user){
		$result = package::$db->Execute("SELECT `ID` FROM `lttx_territory` WHERE `userID` = ?", array($user->getUserID()));
		if(!$result)
		return false;
		$return = array();
		while(!$result->EOF){
			$return[] = new territory($result->fields[0]);
			$result->MoveNext();
		}
		return $return;
	}
	private function _getData(){
		if($this->_getCachedData())
			return true;
		$result = package::$db->Execute("SELECT `userID`, `name` FROM `lttx_territory` WHERE `ID` = ?", array($this->_ID));
		if($result->RecordCount() == 0)
			return false;
		$this->_data['name'] = $result->fields[1];
		$buildings = package::$db->Execute("SELECT `ID`, `buildingID`, `level` FROM `lttx_territoryBuildings` WHERE `territoryID` = ?", array($this->_ID));
		while(!$buildings->EOF){
			try {
				$this->_data['buildings'][$buildings->fields[0]] = array($buildings->fields[2], new building($buildings->fields[1]));
			} catch (Exception $e) {
				//TODO: Debug...
			}
			$buildings->MoveNext();
		}
		$explores = package::$db->Execute("SELECT `ID`, `buildingID`, `level` FROM `lttx_territoryExplores` WHERE `territoryID` = ?", array($this->_ID));
		while(!$explores->EOF){
			try {
				$this->_data['explores'][$explores->fields[0]] = array($explores->fields[2], new building($explores->fields[1]));
			} catch (Exception $e) {
				//TODO: Debug...
			}
			$explores->MoveNext();
		}
		try {
			$user = new user($result->fields[0]);
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
		package::$db->Execute("UPDATE `lttx_territory` SET `name` = ? WHERE `ID` = ?", array($newName, $this->_ID));
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
			$result = package::$db->Execute("INSERT INTO `lttx_territoryBuildings` (`territoryID`, `buildingID`, `level`) VALUES (?, ?, ?)", array($this->_ID, $buildingID, $newLevel));
		}else{
			$result = package::$db->Execute("UPDATE `lttx_territoryBuildings` SET `level` = ? WHERE `territoryID` = ? AND `buildingID` = ?", array($newLevel, $this->_ID, $buildingID));
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