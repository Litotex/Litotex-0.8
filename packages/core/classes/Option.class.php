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

/**
 * This class provides functions to controll options of packages
 * Options are saved in lttxn_options as blob values
 */
class Option{
	/**
	 * Name of the package
	 * @var string
	 */
	private $_package = '';
	/**
	 * Cached options
	 * @var array
	 */
	private static $_cache = array();
	/**
	 * This will load the cache and initialize the class
	 * @param string $package packagename
	 * @return void
	 */
	public function  __construct($package, $allowOtherPackageSpace = false) {
		if(isset(self::$_cache[$package])){
			$this->_package = $package;
			return;
		}
		if($allowOtherPackageSpace){
			if(PACKAGE_PREFIX == 'acp')
			$otherSpace = new PackageManager('', false, MODULES_FRONTEND_DIRECTORY, TEMPLATE_FRONTEND_DIRECTORY);
			else
			$otherSpace = new PackageManager('acp', false, MODULES_BACKEND_DIRECTORY, TEMPLATE_BACKEND_DIRECTORY);
		}
		if(!Package::$packages->exists($package) && !($allowOtherPackageSpace && $otherSpace->exists($package)))
		return false;
		$this->_package = $package;
		$cache = Package::$pdb->prepare("SELECT `key`, `value`, `default` FROM `lttx1_options` WHERE `package` = ?");
		$cache->execute(array($package));
		if($cache->rowCount() < 1){
			throw new LitotexFatalError('Options of ' . $package . ' could not be found');
			return;
		}
		foreach($cache as $element){
			self::$_cache[$package][$element[0]] = array($element[1], $element[2]);
		}
	}
	/**
	 * This will return the blob from options table
	 * @param string $key key to get value from
	 * @return bool on failure | mixed
	 */
	public function get($key, $default = false){
		$value = (isset(self::$_cache[$this->_package][$key][0])) ? self::$_cache[$this->_package][$key][0] : false;
		if($value === false && $default !== false){
			$this->add($key, $default, $default);
			$value = $default;
		}
		return $value;
	}
	/**
	 * This function saves a new value for an existing key
	 * @param string $key key to set value to
	 * @param string $value value to set for this key
	 * @return bool
	 */
	public function set($key, $value){
		if(!isset(self::$_cache[$this->_package][$key][0]))
		return false;
		$result = Package::$pdb->prepare("UPDATE `lttx1_options` SET `value` = ? WHERE `package` = ? AND `key` = ?");
		$result->execute(array($value, $this->_package, $key));
		if($result->rowCount() <= 0){
			return false;
		}
		self::$_cache[$this->_package][$key][0] = $value;
		return true;
	}
	/**
	 * This function creates a new key for the given module
	 * @param string $key Key to set
	 * @param string $value value to save for this new key (not default)
	 * @param string $default default value (for reset)
	 * @return bool
	 */
	public function add($key, $value, $default){
		if(isset(self::$_cache[$this->_package][$key][0]))
		return false;
		$result = Package::$pdb->prepare("INSERT INTO `lttx1_options` (`package`, `key`, `value`, `default`) VALUES (?, ?, ?, ?)");
		$result->execute(array($this->_package, $key, $value, $default));
		if($result->rowCount() <= 0){
			return false;
		}
		self::$_cache[$this->_package][$key][0] = $value;
		self::$_cache[$this->_package][$key][1] = $default;
		return true;
	}
	/**
	 * This will set the value of a given key to default value saved in options table
	 * @param string $key key to reset
	 * @return bool
	 */
	public function reset($key){
		if(!isset(self::$_cache[$this->_package][$key][0]))
		return false;
		$result = Package::$pdb->prepare("UPDATE `lttx1_options` SET `value` = `default` WHERE `package` = ? AND `key` = ?");
		$result->execute(array($this->_package, $key));
		if($result->rowCount() <= 0)
		return false;
		self::$_cache[$this->_package][$key][0] = self::$_cache[$this->_package][$key][1];
		return true;
	}
	/**
	 * This will check wheather or not an option exists, if so update it else add a new option and set it
	 * @param string $key option to be saved
	 * @param mixed $value value to be saved
	 * @param mixed $default value to reset to
	 * @return bool
	 */
	public function addIfNExists($key, $value, $default){
		if($this->get($key))
		return $this->set($key, $value);
		else
		return $this->add($key, $value, $default);
	}
}
?>
