<?php
class configPluginHandler extends plugin_handler{
	protected $_name = "config";
	protected $_location = "config";
	protected $_cacheLocation = "../cache/config.cache.php";
	protected $_currentFile = __FILE__;
}
class configElement{
	private $_HTML = '';
	private $_type = false;
	public function __construct($type){
		$this->_type = $type;
	}
	public function setHTML($code){
		$this->_HTML = $code;
	}
	public function getHTML(){
		return $this->_HTML;
	}
}
class config{
	private $_defaultData = array();
	private $_initialized = false;
	/**
	 * Plugin Handler for actions
	 * @var configPluginHandler
	 */
	private $_pluginHandler = false;
	private $_elements = array();
	public function __construct($defaultData = array()){
		package::addJsFile('main.js', 'acp_config');
		if(!is_array($defaultData)){
			throw new lttxFatalError('Default config data need to be passed as an array.');
		}
		$this->_defaultData = $defaultData;
		$this->_pluginHandler = new configPluginHandler();
		$this->_initialized = true;
	}
	public function addElement($name, $type, $settings){
		$exists = $this->_pluginHandler->callPluginFunc($type, 'exists');
		if(!$exists)
			throw new lttxFatalError('Config plugin ' . $name . ' could not be found within the plugin directory.');
		$settings = $this->_pluginHandler->callPluginFunc($type, 'cleanSettings', array($settings));
		$return = $this->_pluginHandler->callPluginFunc($type, 'registerElement', array($name, $settings));
		if(!is_a($return, 'configElement'))
			throw new lttxFatalError('Config plugin ' . $name . ' could not initialize a new element, it returned an undefined problem.');
		$this->_elements[] = $return;
	}
	public function getHTML(){
		$return = '';
		foreach($this->_elements as $element){
			$return .= $element->getHTML();
		}
		return $return;
	}
}