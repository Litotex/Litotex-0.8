<?php
class race{
	private $_id;
	private $_name;
	private $_image;
	private $_description;
	public function __construct($raceID){
		self::_getRaceData($raceID);
	}
	public function getName(){
		return $this->_name;
	}
	public function getID(){
		return (int)$this->_id;
	}
	public function getImage(){
		return $this->_image;
	}
	public function getDescription(){
		return $this->_description;
	}
	
	public function setName($name){
		package::$db->AutoExecute('lttx_races', array('name' => $name), 'UPDATE', '`id` = ' . $this->getID());
		$this->_name = $name;
	}
	public function setImage($image){
		package::$db->AutoExecute('lttx_races', array('image' => $image), 'UPDATE', '`id` = ' . $this->getID());
		$this->_image = $image;
	}
	public function setDescription($description){
		package::$db->AutoExecute('lttx_races', array('description' => $description), 'UPDATE', '`id` = ' . $this->getID());
		$this->_description = $description;
	}
	private function _getRaceData($raceID){
		$result = package::$db->Execute("SELECT `id`, `name`, `image`, `description` FROM `lttx".package::$dbn."_races` WHERE `id` = ?", array($raceID));
		if(!isset($result->fields[0]))
			throw new lttxError('E_race_not_found', $raceID);
		$this->_id = $result->fields[0];
		$this->_name = $result->fields[1];
		$this->_image = $result->fields[2];
		$this->_description = $result->fields[3];
	}
}