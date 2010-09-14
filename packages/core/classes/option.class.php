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
/**
 * This class provides functions to controll options of packages
 * Options are saved in lttxn_options as blob values
 */
class option{
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
    public function  __construct($package) {
    	if(isset(self::$_cache[$package]))
    		return;
        if(!packages::exists($package))
            return false;
        $this->_package = $package;
        $cache = package::$db->Execute("SELECT `key`, `value`, `default` FROM `lttx_options` WHERE `package` = ?", array($package));
        if(!$cache){
            throw new Exception('Options of ' . $package . ' could not be found');
            return;
        }
        while(!$cache->EOF){
            self::$_cache[$package][$cache->fields[0]] = array($cache->fields[1], $cache->fields[2]);
            $cache->MoveNext();
        }
        return;
    }
    /**
     * This will return the blob from options table
     * @param string $key key to get value from
     * @return bool on failure | mixed
     */
    public function get($key){
        return (isset(self::$_cache[$this->_package][$key][0])) ? self::$_cache[$this->_package][$key][0] : false;
    }
    /**
     * This function saves a new value for an existing key
     * @param string $key key to set value to
     * @param string $value value to set for this key
     * @return bool
     */
    public function set($key, $value){
        if(!isset($this->_cache[$key][0]))
                return false;
        package::$db->Execute("UPDATE `lttx_options` SET `value` = ? WHERE `package` = ? AND `key` = ?", array($value, $this->_package, $key));
        if(package::$db->Affected_Rows() <= 0)
                return false;
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
        package::$db->Execute("INSERT INTO `lttx_options` (`package`, `key`, `value`, `default`) VALUES (?, ?, ?, ?)", array($this->_package, $key, $value, $default));
        if(package::$db->Affected_Rows() <= 0)
                return false;
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
        package::$db->Execute("UPDATE `lttx_options` SET `value` = `default` WHERE `package` = ? AND `key` = ?", array($this->_package, $key));
        if(package::$db->Affected_Rows() <= 0)
                return false;
        self::$_cache[$this->_package][$key][0] = self::$_cache[$this->_package][$key][1];
        return true;
    }
    public function addIfNExists($key, $value, $default){
    	if($this->get($key))
    	return $this->set($key, $value);
    	else
    	return $this->add($key, $value, $default);
    }
}
?>