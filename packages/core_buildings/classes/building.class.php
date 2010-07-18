<?php
class building{
	private $_ID;
	private $_data;
	private $_changed = false;
	private $_initialized = false;
	
	public function __construct($buildingID){
		$data = package::$db->Execute("SELECT `name`, `hooks`, `dependencies` FROM `lttx_buildings` WHERE `ID` = ?", array($buildingID));
		if(!isset($data->fields[0]))
			return;
		$this->_initialized = true;
		$this->_data['name'] = $data->fields[0];
		$this->_data['hooks'] = explode(";", $data->fields[1]);
		$this->_data['dependencies'] = explode(";", $data->fields[2]);
		$this->_ID = $buildingID;
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
		
	}
	public function initialized(){
		return (bool)$this->_initialized;
	}
	public function castFunction(){
		
	}
	public function getBuildTime($level){
		
	}
	public function getPoints($level){
		
	}
	public function getDependencies($level){
		
	}
	public function checkDependencies(territory $territory, $level){
		
	}
	public static function getAllByRace($race){
		
	}
	public function flush(){
		
	}
}