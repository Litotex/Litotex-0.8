<?php
require_once "configConnector.class.php";
require_once "configDBConnector.class.php";
/**
 * This class is a standard plugin handler
 * This one handles config elements
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class configPluginHandler extends plugin_handler{
	/**
	 * Handlername
	 * @var string
	 */
	protected $_name = "config";
	/**
	 * Name of directory in plugins/
	 * @var string
	 */
	protected $_location = "config";
	/**
	 * Cache is located HERE
	 * @var string
	 */
	protected $_cacheLocation = "../cache/config.cache.php";
	/**
	 * I hope you get this yourself... seriously!
	 * @var string
	 */
	protected $_currentFile = __FILE__;
}
/**
 * This class handles all information and settings needed to work with a SINGlE form element,
 * check config class for multi element handling
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class configElement{
	/**
	 * Code to be published as HTML form
	 * @var string
	 */
	private $_HTML = '';
	/**
	 * Type this object handles (pluginname somehow)
	 * @var string
	 */
	private $_type = '';
	/**
	 * Name of this config element (used everywere e.g. HTML output)
	 * @var string
	 */
	private $_nodeName = '';
	/**
	 * Settings array (check the plugin source or documentation)
	 * @var array of mixed values
	 */
	private $_settings = array();
	/**
	 * initializes a new config element, doesn't load plugin or what ever
	 * @param string $type name of the plugin
	 * @param string $name handler name (HTML POST etc)
	 * @param array $settings Settings for the plugin (check plugin source or documentation)
	 * @return void
	 */
	public function __construct($type, $name, $settings){
		$this->_type = $type;
		$this->_nodeName = $name;
		$this->_settings = $settings;
		return;
	}
	/**
	 * Plugin use this function to send generated HTML back
	 * Do not use this by other means, it may distroy forms...
	 * @param string $code HTML code to set
	 * @return bool
	 */
	public function setHTML($code){
		$this->_HTML = $code;
		return true;
	}
	/**
	 * Simply returns the HTML code pushed back by the plugin
	 * @return string
	 */
	public function getHTML(){
		return $this->_HTML;
	}
	/**
	 * Returns saved value after making it kinda "clean"
	 * @param configPluginHandler $pluginHandler Pluginhandler to be used to load the plugin
	 * @return mixed
	 */
	public function getSaveValue(configPluginHandler $pluginHandler){
		$value = $this->_getSystemSaveValue();
		if(!$pluginHandler->callPluginFunc($this->_type, 'checkInput', array($value, $this->_settings)))
			return false;
		return $pluginHandler->callPluginFunc($this->_type, 'cleanInput', array($value, $this->_settings));
	}
	/**
	 * Returns the value send by form uncleaned or checked
	 * @throws lttxFatalError if there is no data to get for this name (means wrong or manipulated form in general)
	 * @return mixed
	 */
	private function _getSystemSaveValue(){
		if(!isset($_POST[$this->_nodeName]))
			throw new lttxFatalError('Unable to fetch value for ' . $this->_nodeName . '. Use the predefined form!');
		return $_POST[$this->_nodeName];
	}
	/**
	 * Returns the name of the element used in HTML or POST to save or load values
	 * @return string
	 */
	public function getName(){
		return $this->_nodeName;
	}
}
/** 
 * This class handles all data which is used in order to organize a form within using configElement objects
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class config{
	/**
	 * Default data for single elements
	 * @var array of strings
	 */
	private $_defaultData = array();
	/**
	 * Plugin Handler for actions
	 * @var configPluginHandler
	 */
	private $_pluginHandler = false;
	/**
	 * List of all elements in this config object
	 * @var array of configElements
	 */
	private $_elements = array();
	/**
	 * Doing usual handshakes with pluginsystem etc.
	 * Initialize default Values for every element.
	 * Does not generate a form!
	 * @param array $defaultData strings for default values
	 * @throws lttxFatalError on error
	 * @return void
	 */
	public function __construct($defaultData = array()){
		package::addJsFile('main.js', 'acp_config');
		if(!is_array($defaultData)){
			throw new lttxFatalError('Default config data need to be passed as an array.');
		}
		$this->_defaultData = $defaultData;
		$this->_pluginHandler = new configPluginHandler();
		return;
	}
	/** 
	 * Adds a new configElement to the config object
	 * @param string $name name to be used to save and display (check, this have to match defaultValues)
	 * @param string $type type (plugin) name of the element to display/work with in other ways
	 * @param array $settings settings for the plugin (check references in plugin code or further documentation)
	 * @param string $default Default value if NON other was set in __construct.
	 * @param string $label Label to display in HTML
	 * @throws lttxFatalError on plugin problems
	 * @return bool
	 */
	public function addElement($name, $type, $settings, $default, $label){
		$exists = $this->_pluginHandler->callPluginFunc($type, 'exists');
		if(!$exists)
			throw new lttxFatalError('Config plugin ' . $name . ' could not be found within the plugin directory.');
		$settings = $this->_pluginHandler->callPluginFunc($type, 'cleanSettings', array($settings));
		$return = $this->_pluginHandler->callPluginFunc($type, 'registerElement', array($name, $settings, $default, $label));
		if(!is_a($return, 'configElement'))
			throw new lttxFatalError('Config plugin ' . $name . ' could not initialize a new element, it returned an undefined problem.');
		$this->_elements[] = $return;
		return true;
	}
	/**
	 * This will render the full form as HTML and return it's code
	 * @return string
	 */
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
	/**
	 * This returns saved data (if the form was saved by the user, else returns false)
	 * @return array of values | bool if none was saved
	 */
	public function getData(){
		if(!isset($_POST['lttxForm']))
			return false;
		$return = array();
		foreach($this->_elements as $element){
			$return[$element->getName()] = $element->getSaveValue($this->_pluginHandler);
		}
		return $return;
	}
	
	public function getElementNames(){
		$return = array();
		foreach($this->_elements as $element){
			$return[] = $element->getName();
		}
		return $return;
	} 
}