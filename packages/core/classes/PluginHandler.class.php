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

class PluginHandler {

    protected $_cache = array();
    protected $_name;
    protected $_location;
    protected $_pluginCacheExpire = 0;
    protected $_cacheLocation;
    protected $_currentFile = '';

    public final function __construct($name = false, $location = false, $cacheLocation = false, $currentFile = false) {
        if ($name)
            $this->_name = $name;
        if ($location)
            $this->_location = $location;
        if ($cacheLocation)
            $this->_cacheLocation = $cacheLocation;
        if ($currentFile)
            $this->_currentFile = $currentFile;
        $this->_loadCache();
    }

    protected final function _checkCacheExpire($cacheTime) {
        if (($cacheTime + $this->_pluginCacheExpire) > time())
            return true;
        return false;
    }

    protected final function _loadCache() {
        if (!file_exists(dirname($this->_currentFile) . '/' . $this->_cacheLocation)) {
            return $this->generatePluginCache();
        }
        $cache = file_get_contents(dirname($this->_currentFile) . '/' . $this->_cacheLocation);
        $cache = explode(';', $cache, 1);
        if (!$this->_checkCacheExpire($cache[0])) {
            return $this->generatePluginCache();
        }
        return ($this->_cache = unserialize($cache[1]));
    }

    public final function generatePluginCache() {
        if (!is_dir(LITO_PLUGIN_ROOT . $this->_location))
            throw new LitotexFatalError('Could not find plugin directory in ' . LITO_PLUGIN_ROOT . $this->_location);
        $dir = opendir(LITO_PLUGIN_ROOT . $this->_location);
        $this->_location = preg_replace("!/$!", '', $this->_location);
        $this->_location .= '/';
        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..')
                continue;
            if (!preg_match("/.*.plugin.php$/", $file))
                continue;
            $pluginname = preg_replace("/.plugin.php$/", '', $file);
            include_once(LITO_PLUGIN_ROOT . $this->_location . $file);
            if ($this->checkPluginValid($pluginname)) {
                $classname = 'plugin_' . $pluginname;
                $prop = get_class_vars($classname);
                $this->_cache[$prop['name']] = array($classname, $pluginname, $prop['availableFunctions']);
            } else {
                trigger_error("The plugin " . $pluginname . " for " . $this->_name . ' seems to be invalid, it is recommended to delete it in order to speed up liototex. If you are not sure why the plugin does not work, please enable DEVDEBUG in global.php', E_USER_NOTICE);
            }
        }
        return $this->_flushCache();
    }

    protected final function checkPluginValid($pluginname) {
        $classname = 'plugin_' . $pluginname;
        if (!class_exists($classname)) {
            if (DEVDEBUG)
                trigger_error("The plugin '" . $pluginname . "' was not found. Please check if the file contains the " . $classname . ' class', E_USER_NOTICE);
            return false;
        }
        if (get_parent_class($classname) != 'Pugin') {
            if (DEVDEBUG)
                trigger_error("The plugin '" . $pluginname . "' was found but does not extend the Plugin class", E_USER_NOTICE);
            return false;
        }
        $prop = get_class_vars($classname);
        if ($prop['handlerName'] != $this->_name) {
            if (DEVDEBUG)
                trigger_error("The plugin '" . $pluginname . "' was found but does not use this class as its handler", E_USER_NOTICE);
            return false;
        }
        return true;
    }

    public final function callPluginFunc($pluginName, $pluginFunc, $params = array()) {
        if (!isset($this->_cache[$pluginName]))
            return false;
        $this->_location = preg_replace("!/$!", '', $this->_location);
        $this->_location .= '/';
        if (!file_exists(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.plugin.php'))
            return false;
        include_once(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.plugin.php');
        if (!in_array($pluginFunc, $this->_cache[$pluginName][2])) {
            return false;
        }
        return call_user_func_array(array($this->_cache[$pluginName][0], $pluginFunc), $params);
    }

    private final function _flushCache() {
        $file = fopen(dirname($this->_currentFile) . '/' . $this->_cacheLocation, 'w');
        if (!$file)
            return false;
        fwrite($file, time() . ';' . serialize($this->_cache));
        return fclose($file);
    }

    public function getLangVar($pluginName, $varName){
        $langCache = new Smarty();
        if (!isset($this->_cache[$pluginName]))
            return false;
        $this->_location = preg_replace("!/$!", '', $this->_location);
        $this->_location .= '/';
        if (!file_exists(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.' . Package::getLanguage() . '.lang.php'))
            return false;
        $langCache->configLoad(LITO_PLUGIN_ROOT . $this->_location . $this->_cache[$pluginName][1] . '.' . Package::getLanguage() . '.lang.php');
        return $langCache->getConfigVariable($varName);
    }

    public final function pluginExists($plugin){
        return isset($this->_cache[$plugin]);
    }

    public final function getPluginList(){
        $return = array();
        foreach ($this->_cache as $name => $data){
            $return[] = $name;
        }
        return $return;
    }

}