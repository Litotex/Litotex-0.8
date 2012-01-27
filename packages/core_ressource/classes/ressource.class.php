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

/**
 * This class handles all ressource specific actions
 * @author Jonas Schwabe <j.s@cascaded-web.com>
 * @todo: Time to have x ressources function
 */
class ressource{
	/**
	 * Was construct called successfully?
	 * @var bool
	 */
	private $_initialized = false;
	/**
	 * RaceID of the item
	 * @var int
	 */
	private $_race;
	/**
	 * Ressources (cached)
	 * @var array
	 */
	private $_res = array();
	private $_resFormula = array();
	private $_saveChanges = false;
	/**
	 * Source id (userid, buildingid...)
	 * @var int
	 */
	private $_src;
	/**
	 * Name of the table to be used (user, building...)
	 * @var str
	 */
	private $_table;
	/**
	 * Which res was changed? Look here!
	 * @var array
	 */
	private $_changed = array();
	/**
	 * Cache of names (race and res saved)
	 * @var array
	 */
	static private $_nameCache;
	/**
	 * Was the name of all ressources fetched?
	 * @var bool
	 */
	private $_resNameFetched = false;
	private $_limit = false;
	private $_useIncreaseFormula = false;
	private $_useLimit = array();
	/**
	 * Loads the data from database and pre caches them therefor
	 * @param int $race ID of race to get information from
	 * @param str $table Tablename lttx[n]_[$table]Ressource
	 * @param int $id ID to cover
	 * @param bool $edit Should any manipulation be written to the database automaticly
	 * @param bool $increaseFormula Should the class use a manipulate formula
	 * @param bool $limit Should the class use the saved limit value?
	 * @return void
	 */
	public function  __construct($race, $table, $id, $edit, $increaseFormula = false, $limit = false) {
		$id *= 1;
		$race *= 1;
		$add = '';
		if($increaseFormula)
			$add .= ', `increaseFormula`';
		if($limit)
			$add .= ', `limit`';
		$res = Package::$pdb->prepare("SELECT `resID`, `resNum`" . $add . " FROM `lttx1_" . $table . "_ressources` WHERE `sourceID` = ? AND `raceID` = ?");
		$res->execute(array($id, $race));
		if($res->rowCount() < 1)
			throw new Exception('Selected ressource table "' . $table . '" was not found or incompatible');
		foreach($res as $element){
			$i = 2;
			$this->_res[$element[0]] = (float)$element[1];
			if($increaseFormula){
				$this->_resFormula[$element[0]] = $element[$i++];
			}
			if($limit){
				$this->_limit[$element[0]] = $element[$i++];
			}
		}
		$checkRes = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_ressources` WHERE `raceID` = ?");
		$checkRes->execute(array($race));
		foreach($checkRes as $element){
			if(isset($this->_res[$element[0]])){
				continue;
			}
			Package::$pdb->prepare("INSERT INTO `lttx1_".$table."Ressources` (`resID`, `raceID`, `sourceID`) VALUES (?, ?, ?)")->execute(array($element[0], $race, $id));
			$this->_res[$element[0]] = 0;
		}
		$this->_race = $race;
		$this->_src = $id;
		$this->_table = $table;
		$this->_initialized = true;
		$this->_saveChanges = (bool)$edit;
		$this->_useIncreaseFormula = (bool)$increaseFormula;
		$this->_useLimit = (bool)$limit;
		return;
	}
	/**
	 * Calls flush basicly
	 * @return void
	 */
	public function __destruct(){
		if($this->_saveChanges){
			$this->flush();
		}
		return;
	}
	public function useFormula($x){
		foreach($this->_resFormula as $id => $formula){
			if(!Math::verifyFormula($formula))
				return false;
			$formula = Math::replaceX($formula, $x);
			$this->_res[$id] = Math::calculateString($formula);
		}
		return true;
	}
	/**
	 * Makes an addition of res1 and res2, res2 is not changed
	 * @param ressource $res1 ressource which schould be changed
	 * @param ressource $res2 ressource to add
	 * @return bool
	 */
	public static function add(ressource $res1, ressource $res2) {
		if(!$res1->initialized() || !$res2->initialized()){
			return false;
		}
		if($res1->getRace() != $res2->getRace())
		return false;
		$res1n = $res1->getAllRess();
		$res2n = $res2->getAllRess();
		foreach($res1n as $key => $value){
			$res1->setRess($key, $value+$res2n[$key]);
		}
		return true;
	}
	/**
	 * makes a subtraction where res1 is changed in the end if there are more ressources than in res2
	 * @param ressource $res1 resources to change
	 * @param ressource $res2 subtraction by this...
	 * @return bool
	 */
	public static function subtract(ressource $res1, ressource $res2) {
		if(!$res1->initialized() || !$res2->initialized()){
			return false;
		}
		if($res1->getRace() != $res2->getRace())
		return false;
		$res1n = $res1->getAllRess();
		$res2n = $res2->getAllRess();
		//We will check if there are enough ressources first (kinda dry run)
		foreach($res1n as $key => $value){
			if($value-$res2n[$key] < 0)
			return false;
		}
		//Then save the new values...
		foreach($res1n as $key => $value){
			$res1->setRess($key, $value-$res2n[$key]);
		}
		return true;
	}
	/**
	 * Returns the numerb of a specific ressource
	 * @param int $resID ID of the ressource
	 * @return int number
	 */
	public function getRess($resID){
		if(!$this->_initialized){
			return false;
		}
		if(!isset($this->_res[$resID]))
		return false;
		return floor($this->_res[$resID]);
	}
	/**
	 * Sets the number of a specific ressource
	 * @param int $resID ID of ressource to set
	 * @param int $newValue New number of available ressource
	 */
	public function setRess($resID, $newValue){
		if(!$this->_initialized){
			return false;
		}
		if(isset($this->_limit[$resID]) && $newValue > $this->_limit[$resID])
			$newValue = $this->_limit[$resID];
		$this->_res[$resID] = (float)$newValue;
		if(!in_array($resID, $this->_changed)){
			$this->_changed[] = $resID;
		}
	}
	public function setLimit($resID, $newValue){
		$newValue = (int)$newValue;
		if(!isset($this->_limit[$resID]))
			return false;
		$this->_limit[$resID] = $newValue;
		Package::$pdb->prepare("UPDATE `lttx1_" . $this->_table . "Ressources` SET `limit` = ? WHERE `resID` = ?, `raceID` = ?, `sourceID` = ?")->execute(array($newValue, $resID, $this->_race, $this->_src));
		return true;
	}
	/**
	 * Returns the ressource instance of a user
	 * @param user $user
	 * @return ressource
	 */
	public static function getTerritoryRess(territory $territory){
		$ID = $territory->getID();
		if(!$ID){
			return false;
		}
		return new ressource($territory->getUser()->getData('race'), 'territory', $ID, true, false, true);
	}
	/**
	 * Returns all ressources as an array
	 * @return array
	 */
	public function getAllRess(){
		if(!$this->_initialized){
			return false;
		}
		return $this->_res;
	}
	/**
	 * Returns the name of a specific res (must exist for the selected race)
	 * @param int $id ID of ress
	 * @return str name
	 */
	public function getRessName($id){
		if(!$this->_ressNameFetched)
		$this->getAllRessName();
		if(!isset(self::$_nameCache[$this->_race . '.' . $id]))
		return false;
		return self::$_nameCache[$this->_race . '.' . $id];
	}
	/**
	 * Returns alle ressources and it's names
	 * @return array
	 */
	public function getAllRessName(){
		$names = Package::$pdb->prepare("SELECT `ID`, `name` FROM `lttx1_ressources` WHERE `raceID` = ?");
		$names->execute(array($this->_race));
		$return = array();
		foreach($names as $name){
			$return[$name[0]] = $name[1];
			self::$_nameCache[$this->_race . '.' . $name[0]] = $name[1];
		}
		$this->_ressNameFetched = true;
		return $return;
	}
	/**
	 * Was the construct function successfull?
	 * @return bool
	 */
	public function initialized(){
		return (bool)$this->_initialized;
	}
	/**
	 * Returns the ID of the race wich was passed to the construct function
	 * @return false on failure | int ID
	 */
	public function getRace(){
		if($this->_initialized)
		return false;
		return $this->_race;
	}
	/**
	 * Writes all changed values to the database
	 * @return bool
	 */
	public function flush(){
		foreach($this->_changed as $value){
			Package::$pdb->prepare('UPDATE `lttx".package::$pdbn."_' . $this->_table . 'Ressources` SET `resNum` = ? WHERE `sourceID` = ? AND `resID` = ? AND `raceID` = ?')->execute(array($this->_res[$value], $this->_src, $value, $this->_race));
		}
		$this->_changed = array();
		return true;
	}
	public function update(){
		return $this->__construct($this->_race, $this->_table, $this->_src, $this->_saveChanges, $this->_useIncreaseFormula, $this->_useLimit);
	}
	public function simpleAddition($resID, $addValue, $numberOfAdditions = 1, $update = false){
		if($update)
			$this->update();
		$addValue *= $numberOfAdditions;
		return $this->setRess($resID, $this->getRess($resID) + $addValue);
	}
	public static function checkFit(ressource $res1, ressource $res2){
		if(!$res1->initialized() || !$res2->initialized()){
			return false;
		}
		if($res1->getRace() != $res2->getRace())
		return false;
		$res1n = $res1->getAllRess();
		$res2n = $res2->getAllRess();
		foreach($res1n as $key => $item){
			if($item < $res2n[$key])
				return false;
		}
		return true;
	}
}