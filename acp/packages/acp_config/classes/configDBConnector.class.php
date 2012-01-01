<?php
require_once 'configConnector.class.php';
class configDBConnector extends configConnector{
	private $_dbName = '';
	private $_elements = array();
	private $_elementID = 0;
	private $_cache = array();
	private $_saveCache = array();
	public function __construct($saveName, $elements, $elementID = 0){
		if(!is_array($elements))
			return;
		$this->_dbName = $saveName;
		$this->_configObject = new config($this->getData());
		$this->_elements = $elements;
		$this->_elementID = (int)$elementID;
		if(!$this->_cacheData())
			return;
		$this->_initialized = true;
	}
	public function __destruct(){
		
	}
	private function _flushCache(){
		if(!$this->_initialized)
			return false;
		
	}
	private function _cacheData(){
		$result = package::$db->Execute("SELECT * FROM `lttx".package::$dbn."_" . $this->_dbName . " WHERE `ID` = ? LIMIT 1", array($this->_elementID));
		if(!$result)
			return false;
		if(!isset($result->fields[0]))
			return false;
		$this->_cache = $result->fields;
		return true;
	}
	public function getData($key){
		if(!$this->_initialized)
			return false;
		return (isset($this->_cache[$key]))?$this->_cache[$key]:false;
	}
	public function saveData($key, $value){
		
	}
	public function getForm(){
		
	}
}