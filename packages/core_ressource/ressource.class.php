<?php
/**
 * This class handles all ressource specific actions
 * @author Jonas Schwabe <j.s@cascaded-web.com>
 * @todo: Time to have x ressources function, are there enough res? If not wich lacks?
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
	/**
	 * Loads the data from database and pre caches them therefor
	 * @param $race
	 * @param $table
	 * @param $id
	 * @return void
	 */
	public function  __construct($race, $table, $id) {
		$id *= 1;
		$race *= 1;
		$res = package::$db->Execute("SELECT `resID`, `resNum` FROM `lttx_" . $table . "Ressources` WHERE `sourceID` = ? AND `raceID` = ?", array($id, $race));
		if(!$res)
		return;
		while(!$res->EOF){
			$this->_res[$res->fields[0]] = (int)$res->fields[1];
			$res->MoveNext();
		}
		$this->_race = $race;
		$this->_src = $id;
		$this->_table = $table;
		$this->_initialized = true;
		return;
	}
	/**
	 * Calls flush basicly
	 * @return void
	 */
	public function __destruct(){
		$this->flush();
		return;
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
		return $this->_res[$resID];
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
		$this->_res[$resID] = (int)$newValue;
		if(!in_array($resID, $this->_changed)){
			$this->_changed[] = $resID;
		}
	}
	/**
	 * Returns the ressource instance of a user
	 * @param user $user
	 * @return ressource
	 */
	public static function getUserRess(user $user){
		if(!is_a($user)){
			return false;
		}
		$ID = $user->getUserID();
		if(!$ID){
			return false;
		}
		return new ressource($user->getRace, 'user', $ID);
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
		$names = package::$db->Execute("SELECT `ID`, `name` FROM `lttx_ressources` WHERE `raceID` = ?", $this->_race);
		$return = array();
		while(!$names->EOF){
			$return[$names->fields[0]] = $names->fields[1];
			self::$_nameCache[$this->_race . '.' . $names->fields[0]] = $names->fields[1];
			$names->MoveNext();
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
			package::$db->Execute('UPDATE `lttx_' . $this->_table . 'Ressources` SET `resNum` = ? WHERE `sourceID` = ? AND `resID` = ? AND `raceID` = ?', array($this->_res[$value], $this->_src, $value, $this->_race));
		}
		$this->_changed = array();
		return true;
	}
}