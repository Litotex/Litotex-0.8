<?php
define("DEVDEBUG", true);
abstract class plugin_handler{
	protected $_cache = array();
	protected $_name;
	protected $_location;
	protected $_pluginCacheExpire = 86400;
	protected $_cacheLocation;
	public final function __construct(){
		$this->_loadCache();
	}
	protected final function _checkCacheExpire($cacheTime){
		if(($cacheTime + $this->_pluginCacheExpire) > time())
			return true;
		return false;
	}
	protected final function _loadCache(){
		if(!file_exists($this->_cacheLocation)){
			return $this->generatePluginCache();
		}
		$cache = file_get_contents($this->_cacheLocation);
		$cache = explode(';', $cache, 1);
		if(!$this->_checkCacheExpire($cache[0])){
			return $this->generatePluginCache();
		}
		return ($this->_cache = unserialize($cache[1])); 
	}
	public final function generatePluginCache(){
		if(!is_dir($this->_location))
			return false;
		$dir = opendir($this->_location);
		$this->_location = preg_replace("!/$!", '', $this->_location);
		$this->_location .= '/';
		while($file = readdir($dir)){
			if($file == '.' || $file == '..')
				continue;
			if(!preg_match("/.*.plugin.php$/", $file))
				continue;
			$pluginname = preg_replace("/.plugin.php$/", '', $file);
			include_once($this->_location . $file);
			if($this->checkPluginValid($pluginname)){
				$classname = 'plugin_' . $pluginname;
				$this->_cache[$classname::$name] = array($classname, $classname::$availableFunctions);
			} else {
				trigger_error("The plugin " . $pluginname . " for " . $this->_name . ' seems to be invalid, it is recommended to delete it in order to speed up liototex. If you are not sure why the plugin does not work, please enable DEVDEBUG in global.php', E_USER_NOTICE);
			}
		}
	}
	protected final function checkPluginValid($pluginname){
		$classname = 'plugin_' . $pluginname;
		if(!class_exists($classname)){
			if(DEVDEBUG)
				trigger_error("The plugin " . $pluginname . " was not found. Please check if it's file contains the " . $classname . ' class', E_USER_NOTICE);
			return false;
		}
		if(get_parent_class($classname) != 'plugin'){
			if(DEVDEBUG)
				trigger_error("The plugin " . $pluginname . " was found but does not extend plugin. Please check toe documentation to get more information about the plugin system.", E_USER_NOTICE);
			return false;
		}
		if($classname::$handlerName != $this->_name){
			if(DEVDEBUG)
				trigger_error("The plugin " . $pluginname . " was found but does not use this class as it's handler. Please check toe documentation to get more information about the plugin system.", E_USER_NOTICE);
			return false;
		}
		return true;
	}
	public final function callPluginFunc($pluginName, $pluginFunc, $params){
		
	}
	private final function flushCache(){
		
	}
}

abstract class plugin{
	public static $handlerName;
	public static $name;
	public static $availableFunctions = array();
}

class handler extends plugin_handler{
	protected $_name = "test";
	protected $_location = "test";
	protected $_cacheLocation = "test/test.cache.php";
}
$handel = new handler();