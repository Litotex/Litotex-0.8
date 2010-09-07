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
 * Packagemanager class to organize packages to be loaded etc.
 */
class packages{
	/**
	 * File hook cached is saved in
	 * @var string
	 */
	private $_hookCacheFile = HOOK_CACHE;
	/**
	 * Time (sec) hook cached is saved
	 * @var int
	 */
	private $_hookCacheExpire = 0;
	/**
	 * Time (sec) packages cache is saved
	 * @var int
	 */
	private $_dependencyCacheExpire = 0;
	/**
	 * File package cache is saved in
	 * @var string
	 */
	private $_dependencyCacheFile = PACKAGE_CACHE;
	private $_tplModificationCacheFile = TPLMOD_CACHE;
	private $_tplModificationCacheExpire = 0;
	private $_tplModificationCache = array();
	/**
	 * Cache hooks are saved in
	 * @var array
	 */
	private $_hookCache = array();
	/**
	 * Cache packages are saved in
	 * @var array
	 */
	private $_dependencyCache = array();
	/**
	 * Directory packages are saved in
	 * @var string
	 */
	private $_packagesDir = MODULES_DIRECTORY;

	private $_loaded = array();
	/**
	 * This will load hook and package cache and save the modulmanager class in packages parent class
	 * @return void
	 */
	public function __construct(){
		package::setPackageManagerClass($this);
		if($this->_loadHookCache() === false){
			$this->generateHookCache();
		}
		if($this->_loadDependencyCache() === false){
			$this->generateDependencyCache();
		}
		if($this->_loadTplModificationCache() === false){
			$this->generateTplModificationCache();
		}
		return;
	}
	private function _loadTplModificationCache(){
		if(!file_exists($this->_tplModificationCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_tplModificationCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if(!$this->_checkTplModificationCacheExpire($cacheContents[0]))
		return false;
		return ($this->_tplModificationCache = unserialize($cacheContents[1]));
	}
	private function _checkTplModificationCacheExpire($startTime){
		if((time()-$this->_tplModificationCacheExpire) < $startTime){
			return true;
		}else{
			return false;
		}
	}
	public function generateTplModificationCache(){
		if(!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while($file = readdir($packages)){
			if($file == '.' || $file == '..')
			continue;
			if(!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if(class_exists($className)){
				$newModification = call_user_func(array($className, 'registerTplModifications'));
				if(!$newModification)
				return false;
			}
		}
		$this->_orderTplModificationCache();
		package::$db->Execute("TRUNCATE TABLE `lttx_tplModificationSort`");
		return $this->_writeTplModificationCache();
	}
	private function _writeTplModificationCache(){
		$newfile = '<?php die(); ?>'.time().'%'.serialize($this->_tplModificationCache);
		$file = fopen($this->_tplModificationCacheFile, 'w');
		if(!$file)
		return false;
		fwrite($file, $newfile, 1000000);
		fclose($file);
		//And to database...
		foreach($this->_tplModificationCache as $position => $list){
			$n = 0;
			foreach($list as $item){
				package::$db->Execute("INSERT INTO `lttx_tplModificationSort` (`class`, `function`, `position`, `active`, `sort`) VALUES (?, ?, ?, ?, ?)", array($item[0], $item[1], $position, $item[4], $n));
				$n++;
			}
		}
		return true;
	}
	/**
	 * This function will load hook cach from cacheing file
	 * @return bool
	 */
	private function _loadHookCache(){
		if(!file_exists($this->_hookCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_hookCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if(!$this->_checkHookCacheExpire($cacheContents[0]))
		return false;
		return ($this->_hookCache = unserialize($cacheContents[1]));
	}
	/**
	 * This function checks if cache of hooks is usable
	 * @param int $startTime time cache was created
	 * @return bool (true => cache is useable)
	 */
	private function _checkHookCacheExpire($startTime){
		if((time()-$this->_hookCacheExpire) < $startTime){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * This function generates a new hook cache and saves it in the end
	 * @return bool
	 */
	public function generateHookCache(){
		package::$db->Execute("DELETe FROM `lttx_permissionsAvailable` WHERE `type` = ?", array(2));
		if(!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while($file = readdir($packages)){
			if($file == '.' || $file == '..')
			continue;
			if(!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if(class_exists($className)){
				$newHooks = call_user_func(array($className, 'registerHooks'));
				if(!$newHooks)
				return false;
			}
		}
		return $this->_writeHookCache();
	}
	private function _orderTplModificationCache(){
		$database = package::$db->Execute("SELECT `class`, `function`, `position`, `sort`, `active` FROM `lttx_tplModificationSort` ORDER BY `sort` ASC");
		if(!$database)
		return false;
		$cache = array();
		while(!$database->EOF){
			$cache[$database->fields[0] . ':' . $database->fields[1]] = array($database->fields[0], $database->fields[1], $database->fields[2], $database->fields[3], $database->fields[4]);
			$database->MoveNext();
		}
		$newOrder = array();
		$newOrder['none'] = array();
		foreach($cache as $key => $value){
			if(!isset($this->_tplModificationCache[$key])){
				continue;
			}
			array_push($this->_tplModificationCache[$key], $value[4]);
			$newOrder[$value[2]][] = $this->_tplModificationCache[$key];
			unset($this->_tplModificationCache[$key]);
		}
		foreach($this->_tplModificationCache as $key => $value){
			array_push($value, false);
			$newOrder['none'][] = $value;
		}
		$this->_tplModificationCache = $newOrder;
	}
	/**
	 * This function will write hook cache to the cacheing file
	 * @return bool
	 */
	private function _writeHookCache(){
		$newfile = '<?php die(); ?>'.time().'%'.serialize($this->_hookCache);
		$file = fopen($this->_hookCacheFile, 'w');
		if(!$file)
		return false;
		fwrite($file, $newfile, 1000000);
		fclose($file);
		return true;
	}
	/**
	 * This function registers a new hook, it will be casted when generateHookCache is casted
	 * @param str $class classname of hook saeing class
	 * @param str $hookname name of the hook (called)
	 * @param int $nParams number of params the hook will work with
	 * @param str $function name of the function that should be used if it is not named __hook_$hookname
	 * @return bool
	 */
	public function registerHook($class, $hookname, $nParams, $function, $file, $packageName){
		if($file){
			include_once($file);
		}
		if(!method_exists($class, '__hook_'.$function))
		return false;
		package::$db->Execute("INSERT INTO `lttx_permissionsAvailable` (`type`, `package`, `class`, `function`) VALUES (?, ?, ?, ?)", array(2, $packageName, $class, $function));
		$this->_hookCache[$hookname.':'.$nParams][] = array($class, $function, $file, $packageName);
		return true;
	}
	public function registerTplModification($class, $function, $file, $packageName){
		if($file){
			include_once($file);
		}
		if(!method_exists($class, '__tpl_'.$function))
		return false;
		$this->_tplModificationCache[$class.':'.$function] = array($class, $function, $file, $packageName);
		return true;
	}
	/**
	 * This function will cast every available function castable named $hookname
	 * @param str $hookname name of the hook
	 * @param array $args array with all the arguments that should be passed
	 * @return bool
	 */
	public function callHook($hookname, $args = array()){
		$nParams = count($args);
		if(!isset($this->_hookCache[$hookname . ':' . $nParams]))
		return true;
		$return = true;
		foreach($this->_hookCache[$hookname . ':' . $nParams] as $func){
			if($func[2]){
				if($func[3])
				$this->loadPackage($func[3], false, false);
				include_once($func[2]);
			}
			$this->loadPackage(preg_replace("/^package_/", "", $func[0]), false, false);
			if(!call_user_func_array(array($func[0], '__hook_' . $func[1]), $args))
			$return = false;
		}
		return $return;
	}
	/**
	 * This function will create an instance of a package set by $packageName
	 * @param str $packageName name of class (without package_*)
	 * @param bool Should a template be showed?
	 * @param bool Should the package be initlaized at all? If not only include it...
	 * @return bool on failure | instance of class | true if not initialized
	 */
	public function loadPackage($packageName, $tplEnable = true, $initialize = true){
		if($initialize == false && in_array($packageName, $this->_loaded))
		return true;
		$dep = array();
		if(isset($this->_dependencyCache[$packageName]) && $this->_dependencyCache[$packageName]['active'] == true){
			include_once($this->_packagesDir . '/' . $this->_dependencyCache[$packageName][0] . '/init.php');
			foreach($this->_dependencyCache[$packageName]['loadDep'] as $depName){
				$cache = $this->loadPackage($depName, false);
				if(!$cache)
				trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $packageName . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
				$dep[$depName] = $cache;
			}
			$cname = $this->_dependencyCache[$packageName][1];
			call_user_func(array($cname, 'registerDependency'), $dep);
			$this->_loaded[] = $packageName;
			foreach($this->_dependencyCache[$packageName]['loadDep'] as $depName){
				$cache = $this->loadPackage($depName, false, false);
				if(!$cache)
				trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $packageName . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
			}
			if($initialize){
				$pack = new $this->_dependencyCache[$packageName][1];
				$pack->setTemplatePolicy($tplEnable);
				return $pack;
			}
			return true;
		} else {
			return false;
		}
	}
	private function _getPackageDependencies(){
	}
	/**
	 * This function will load package cache from cacheing file
	 * @return bool
	 */
	private function _loadDependencyCache(){
		if(!file_exists($this->_dependencyCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_dependencyCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if(!$this->_checkDependencyCacheExpire($cacheContents[0]))
		return false;
		return ($this->_dependencyCache = unserialize($cacheContents[1]));
	}
	/**
	 * This function checks if the package cache is usable
	 * @param int $startTime time cache was created
	 * @return bool (true = usable)
	 */
	private function _checkDependencyCacheExpire($startTime){
		if((time()-$this->_dependencyCacheExpire) < $startTime){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * This function will create a new package cache
	 * @return bool
	 */
	public function generateDependencyCache(){
		package::$db->Execute("TRUNCATE TABLE `lttx_permissionsAvailable`");
		if(!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while($file = readdir($packages)){
			if($file == '.' || $file == '..')
			continue;
			if(!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if(class_exists($className)){
				$newInfo = call_user_func(array($className, 'registerClass'), $className);
				if(!$newInfo)
				return false;
			}
		}
		$this->_checkDependency();
		return $this->_writeDependencyCache();
	}

	private function _checkDependency(){
		$return = true;
		$parents = array();
		$checked = array();
		for($i = 0; $i < 2; $i++){
			foreach($this->_dependencyCache as $name => $pack){
				if($pack['active'] == false)
				continue;
				foreach($pack['dep'] as $dep){
					if(!isset($this->_dependencyCache[$dep])){
						trigger_error("Could not find package <i>" . $dep . "</i> which is needed by <i>" . $name . "</i>. The package will be deactivated automaticly!", E_USER_NOTICE);
						$this->_dependencyCache[$name]['active'] = false;
						$return = false;
					} else if($this->_dependencyCache[$dep]['active'] == false){
						trigger_error("Package <i>" . $dep . "</i> which is needed by <i>" . $name . "</i> is deactivated and could not be loaded. The package will be deactivated automaticly!", E_USER_NOTICE);
						$this->_dependencyCache[$name]['active'] = false;
						$return = false;
					}
				}
			}
		}
		return $return;
	}
	/**
	 * This function will register a class for the package cache
	 * @param str $class name of class
	 * @return bool
	 */
	public function registerClass($class){
		$path = str_replace('package_', '', $class);
		$this->_dependencyCache[$path] = array($path, $class);
		$prop = get_class_vars($class);
		$dep = $prop['dependency'];
		$loadDep = $prop['loadDependency'];
		$this->_dependencyCache[$path]['dep'] = $dep;
		$this->_dependencyCache[$path]['loadDep'] = $loadDep;
		$this->_dependencyCache[$path]['active'] = true;
		$pack = $this->loadPackage($path, false, true);
		$actions = $pack->getActions();
		foreach($actions as $action){
			package::$db->Execute("INSERT INTO `lttx_permissionsAvailable` (`type`, `package`, `class`, `function`) VALUES (?, ?, ?, ?)", array(1, $path, $class, $action));
		}
		return true;
	}
	/**
	 * This function will write a cacheing file for packages
	 * @return bool
	 */
	private function _writeDependencyCache(){
		$newfile = '<?php die(); ?>'.time().'%'.serialize($this->_dependencyCache);
		$file = fopen($this->_dependencyCacheFile, 'w');
		if(!$file)
		return false;
		fwrite($file, $newfile, 1000000);
		fclose($file);
	}
	public function activatePackage(){
	}
	public function deactivatePackage(){
	}
	public function installPackage($location, $packageName){
		include_once('installer.class.php');
		if(!file_exists($location . '/installer.php'))
		return false;
		include_once($location . '/installer.php');
		$className = "installer_" . $packageName;
		$installer = new $className($location, $packageName);
	}
	public function removePackage(){
	}
	public function updatePackage(){
	}
	public function getChangeLog(){
	}
	public function updateRemotePackageList(){
	}
	public function copyRemotePackage(){
	}
	public function searchRemotePackageList(){
	}
	/**
	 * Checks if a package exists
	 * @param string $package packagename
	 * @return bool
	 */
	public static function exists($package){
		if(file_exists(MODULES_DIRECTORY . '/' . $package . '/init.php'))
		return true;
		return false;
	}
	public function displayTplModification($position){
		if(!isset($this->_tplModificationCache[$position]))
		return true;
		$return = true;
		foreach($this->_tplModificationCache[$position] as $func){
			if($func[4] == false)
				continue;
			if($func[2]){
				if($func[3])
				$this->loadPackage($func[3], false, false);
				include_once($func[2]);
			}
			$this->loadPackage(preg_replace("/^package_/", "", $func[0]), false, false);
			if(!call_user_func_array(array($func[0], '__tpl_' . $func[1]), array()))
			$return = false;
		}
		return $return;
	}
}
