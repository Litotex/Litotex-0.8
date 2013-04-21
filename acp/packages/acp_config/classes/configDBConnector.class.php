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
		$result = Package::$pdb->prepare("SELECT * FROM `lttx1_" . $this->_dbName . " WHERE `ID` = ? LIMIT 1");
		$result->execute(array($this->_elementID));
		if($result->rowCount() < 0)
			return false;
		
		$this->_cache = $result->fetch();
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