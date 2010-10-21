<?php
require_once "configConnector.class.php";
abstract class configConnector{
	protected $_configElements = array();
	public function __construct(){} //Do some handshake... what ever...
	public function __destruct(){} //Save some changes... etc
	abstract public function getData();
	abstract public function saveData();
	public final function registerConfig(config $config){
		$this->_configElements[] = $config;
		return true;
	}
}