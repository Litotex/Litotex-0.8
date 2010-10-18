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
	private $_nodeName = '';
	private $_settings = array();
	public function __construct($type, $name, $settings){
		$this->_type = $type;
		$this->_nodeName = $name;
		$this->_settings = $settings;
	}
	public function setHTML($code){
		$this->_HTML = $code;
	}
	public function getHTML(){
		return $this->_HTML;
	}
	public function getSaveValue(configPluginHandler $pluginHandler){
		$value = $this->_getSystemSaveValue();
		if(!$pluginHandler->callPluginFunc($this->_type, 'checkInput', array($value, $this->_settings)))
			return false;
		return $pluginHandler->callPluginFunc($this->_type, 'cleanInput', array($value, $this->_settings));
	}
	private function _getSystemSaveValue(){
		if(!isset($_POST[$this->_nodeName]))
			throw new lttxFatalError('Unable to fetch value for ' . $this->_nodeName . '. Use the predefined form!');
		return $_POST[$this->_nodeName];
	}
	public function getName(){
		return $this->_nodeName;
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
		$formCode = '';
		foreach($this->_elements as $element){
			$formCode .= $element->getHTML();
		}
		package::$tpl->assign('formCode', $formCode);
		package::loadLang(package::$tpl, 'acp_config');
		$return = package::$tpl->fetch(package::getTplDir('acp_config') . 'defaultConfig.tpl');
		return $return;
	}
	public function getData(){
		if(!isset($_POST['lttxForm']))
			return false;
		$return = array();
		foreach($this->_elements as $element){
			$return[$element->getName()] = $element->getSaveValue($this->_pluginHandler);
		}
		return $return;
	}
}