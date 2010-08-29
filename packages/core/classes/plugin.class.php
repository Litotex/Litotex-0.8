<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
abstract class plugin_handler{
	protected $_cache = array();
	protected $_name;
	protected $_location;
	protected $_pluginCacheExpire = 0;
	protected $_cacheLocation;
	protected $_currentFile = '';
	public final function __construct(){
		$this->_loadCache();
	}
	protected final function _checkCacheExpire($cacheTime){
		if(($cacheTime + $this->_pluginCacheExpire) > time())
			return true;
		return false;
	}
	protected final function _loadCache(){
		if(!file_exists(dirname($this->_currentFile) . '/' . $this->_cacheLocation)){
			return $this->generatePluginCache();
		}
		$cache = file_get_contents(dirname($this->_currentFile) . '/' . $this->_cacheLocation);
		$cache = explode(';', $cache, 1);
		if(!$this->_checkCacheExpire($cache[0])){
			return $this->generatePluginCache();
		}
		return ($this->_cache = unserialize($cache[1])); 
	}
	public final function generatePluginCache(){
		if(!is_dir(LITO_PLUGIN_ROOT . $this->_location))
			return false;
		$dir = opendir(LITO_PLUGIN_ROOT . $this->_location);
		$this->_location = preg_replace("!/$!", '', $this->_location);
		$this->_location .= '/';
		while($file = readdir($dir)){
			if($file == '.' || $file == '..')
				continue;
			if(!preg_match("/.*.plugin.php$/", $file))
				continue;
			$pluginname = preg_replace("/.plugin.php$/", '', $file);
			include_once(LITO_PLUGIN_ROOT . $this->_location . $file);
			if($this->checkPluginValid($pluginname)){
				$classname = 'plugin_' . $pluginname;
				$prop = get_class_vars($classname);
				$this->_cache[$prop['name']] = array($classname, $pluginname, $prop['availableFunctions']);
			} else {
				trigger_error("The plugin " . $pluginname . " for " . $this->_name . ' seems to be invalid, it is recommended to delete it in order to speed up liototex. If you are not sure why the plugin does not work, please enable DEVDEBUG in global.php', E_USER_NOTICE);
			}
		}
		return $this->_flushCache();
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
		$prop = get_class_vars($classname);
		if($prop['handlerName'] != $this->_name){
			if(DEVDEBUG)
				trigger_error("The plugin " . $pluginname . " was found but does not use this class as it's handler. Please check toe documentation to get more information about the plugin system.", E_USER_NOTICE);
			return false;
		}
		return true;
	}
	public final function callPluginFunc($pluginName, $pluginFunc, $params){
		if(!isset($this->_cache[$pluginName]))
			return false;
		$this->_location = preg_replace("!/$!", '', $this->_location);
		$this->_location .= '/';
		if(!file_exists(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.plugin.php'))
			return false;
		include_once(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.plugin.php');
		if(!in_array($pluginFunc, $this->_cache[$pluginName][2])){
			return false;
		}
		return call_user_func_array(array($this->_cache[$pluginName][0], $pluginFunc), $params);
	}
	private final function _flushCache(){
		$file = fopen(dirname($this->_currentFile) . '/' . $this->_cacheLocation, 'w');
		if(!$file) return false;
		fwrite($file, time().';'.serialize($this->_cache));
		return fclose($file);
	}
}

abstract class plugin{
	public static $handlerName;
	public static $name;
	public static $availableFunctions = array();
}