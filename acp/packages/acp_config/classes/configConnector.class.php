<?php
abstract class configConnector{
	/**
	 * Stores the config object
	 * @var config
	 */
	protected $_configObject = array();
	protected $_initialized = false;
	abstract public function __construct($saveName, $elements, $elementID = 0); //Do some handshake... what ever...
	abstract public function getData($key);
	abstract public function saveData($key, $value);
	abstract public function getForm();
	public final function registerConfig(config $config){
		$this->_configElements[] = $config;
		return true;
	}
}