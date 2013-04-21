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
 * Packagemanager class to organize packages to be loaded etc.
 */
class PackageManager {

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
	/**
	 * Path to tplMod cachefile
	 * @var string
	 */
	private $_tplModificationCacheFile = TPLMOD_CACHE;
	/**
	 * Time until the tplMod cache expires
	 * @var int
	 */
	private $_tplModificationCacheExpire = 0;
	/**
	 * Template modification cache
	 * @var array
	 */
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
	private $_tplDir = TEMPLATE_DIRECTORY;
	private $_packagePrefix = PACKAGE_PREFIX;
	private static $_fileHashBlacklist = array('tpl_c', 'backup', 'files');
	/**
	 * List of all loaded packages
	 * @var array
	 */
	private $_loaded = array();
	/**
	 * List of all packages which had the ability to load lang files
	 * @var array
	 */
	private $_loadedLang = array();

	/**
	 * This will load hook and package cache and save the modulmanager class in packages parent class
	 * @return void
	 */
	public function __construct($prefix = false, $setPM = true, $packagesDir = false, $tplDir = false) {
		if ($prefix !== false)
		$this->_packagePrefix = $prefix;
		$oldPM = Package::$packages;
		Package::setPackageManagerClass($this);
		if ($packagesDir)
		$this->_packagesDir = $packagesDir;
		if ($tplDir)
		$this->_tplDir = $tplDir;
		if ($this->_loadHookCache() === false) {
			$this->generateHookCache();
		}
		if ($this->_loadDependencyCache() === false) {
			$this->generateDependencyCache();
		}
		if ($this->_loadTplModificationCache() === false) {
			$this->generateTplModificationCache();
		}
		if (!$setPM)
		Package::setPackageManagerClass($oldPM);
		return;
	}

	/**
	 * This will automaticly check the tplMod cache expire and load it if it is still working
	 * if so it will load it automaticly
	 * @return bool
	 */
	private function _loadTplModificationCache() {
		if (!file_exists($this->_tplModificationCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_tplModificationCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if (!$this->_checkTplModificationCacheExpire($cacheContents[0]))
		return false;
		return ($this->_tplModificationCache = unserialize($cacheContents[1]));
	}

	/**
	 * This will check whether or not the tplMod cache is expired
	 * @param int $startTime unix timestamp (generation time of the cache)
	 * @return bool expired
	 */
	private function _checkTplModificationCacheExpire($startTime) {
		if ((time() - $this->_tplModificationCacheExpire) < $startTime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This will generate a new tplModCache and saves it when it's done
	 * @return bool
	 */
	public function generateTplModificationCache() {
		$oldPM = Package::$packages;
		Package::setPackageManagerClass($this);
		$this->_tplModificationCache = array();
		Permission::clearAvailableTable($this->_packagePrefix);
		if (!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while ($file = readdir($packages)) {
			if ($file == '.' || $file == '..')
			continue;
			if (!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if (class_exists($className)) {
				$newModification = call_user_func(array($className, 'registerTplModifications'));
				if (!$newModification)
				return false;
			}
		}
		if (!$this->_orderTplModificationCache()) {
			throw new LitotexFatalError("Could not fetch tplMod settings, this might be a serious database issue!");
		}
		Package::$pdb->prepare("DELETE FROM `lttx1_tpl_modification_sort` WHERE `packageDir` = ?")->execute(array($this->_packagePrefix));
		closedir($packages);
		Package::setPackageManagerClass($oldPM);
		return $this->_writeTplModificationCache();
	}

	/**
	 * This will write a new tplMod cache to the disc
	 * @return bool
	 */
	private function _writeTplModificationCache() {
		$newfile = '<?php die(); ?>' . time() . '%' . serialize($this->_tplModificationCache);
		$file = fopen($this->_tplModificationCacheFile, 'w');
		if (!$file)
		return false;
		fwrite($file, $newfile, 1000000);
		fclose($file);
		//And to database...
		foreach ($this->_tplModificationCache as $position => $list) {
			$n = 0;
			foreach ($list as $item) {
				Package::$pdb->prepare("INSERT INTO `lttx1_tpl_modification_sort` (`class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES (?, ?, ?, ?, ?, ?)")->execute(array($item[0], $item[1], $position, intval($item[4]), $n, $this->_packagePrefix));
				$n++;
			}
		}
		return true;
	}

	/**
	 * This function will load hook cach from cacheing file
	 * @return bool
	 */
	private function _loadHookCache() {
		if (!file_exists($this->_hookCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_hookCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if (!$this->_checkHookCacheExpire($cacheContents[0]))
		return false;
		return ($this->_hookCache = unserialize($cacheContents[1]));
	}

	/**
	 * This function checks if cache of hooks is usable
	 * @param int $startTime time cache was created
	 * @return bool (true => cache is useable)
	 */
	private function _checkHookCacheExpire($startTime) {
		if ((time() - $this->_hookCacheExpire) < $startTime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function generates a new hook cache and saves it in the end
	 * @return bool
	 */
	public function generateHookCache() {
		$oldPM = Package::$packages;
		Package::setPackageManagerClass($this);
		if (!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while ($file = readdir($packages)) {
			if ($file == '.' || $file == '..')
			continue;
			if (!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if (class_exists($className)) {
				$newHooks = call_user_func(array($className, 'registerHooks'));
				if (!$newHooks)
				return false;
			}
		}
		Package::setPackageManagerClass($oldPM);
		return $this->_writeHookCache();
	}

	/**
	 * This will rearange the tplMod cache by using the settings saved in the darabase
	 * @return bool
	 */
	private function _orderTplModificationCache() {
		$database = Package::$pdb->prepare("SELECT `class`, `function`, `position`, `sort`, `active` FROM `lttx1_tpl_modification_sort` WHERE `packageDir` = ? ORDER BY `sort` ASC");
		$database->execute(array($this->_packagePrefix));

		$cache = array();
		foreach($database as $tplMod){
			$cache[$tplMod[0] . ':' . $tplMod[1]] = array($tplMod[0], $tplMod[1], $tplMod[2], $tplMod[3], $tplMod[4]);
		}

		$newOrder = array();
		$newOrder['none'] = array();
		foreach ($cache as $key => $value) {
			if (!isset($this->_tplModificationCache[$key])) {
				continue;
			}
			$this->_tplModificationCache[$key][4] = $value[4];
			$newOrder[$value[2]][] = $this->_tplModificationCache[$key];
			unset($this->_tplModificationCache[$key]);
		}
		foreach ($this->_tplModificationCache as $key => $value) {
			array_push($value, false);
			$newOrder['none'][] = $value;
		}
		$this->_tplModificationCache = $newOrder;
		return true;
	}

	/**
	 * This function will write hook cache to the cacheing file
	 * @return bool
	 */
	private function _writeHookCache() {
		$newfile = '<?php die(); ?>' . time() . '%' . serialize($this->_hookCache);
		$file = fopen($this->_hookCacheFile, 'w');
		if (!$file)
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
	public function registerHook($class, $hookname, $nParams, $function, $file, $packageName) {
		if ($file) {
			include_once($file);
		}
		if (!method_exists($class, '__hook_' . $function))
		return false;
		$this->_hookCache[$hookname . ':' . $nParams][] = array($class, $function, $file, $packageName);
		return true;
	}

	/**
	 * This will add a new tplMod to the cache
	 * @param string $class classname
	 * @param string $function name of the function to be called
	 * @param string $file name of the file to be loaded (optional! false) for plugins
	 * @param string $packageName name of the package to be loaded (optional! false) for plugins
	 * @return bool
	 */
	public function registerTplModification($class, $function, $file, $packageName) {
		if ($file) {
			include_once($file);
		}
		if (!method_exists($class, '__tpl_' . $function))
		return false;
		Permission::registerAvailable($packageName, $class, $function, $this->_packagePrefix);
		$this->_tplModificationCache[$class . ':' . $function] = array($class, $function, $file, $packageName, false);
		return true;
	}

	/**
	 * This function will cast every available function castable named $hookname
	 * @param str $hookname name of the hook
	 * @param array $args array with all the arguments that should be passed
	 * @return bool
	 */
	public function callHook($hookname, $args = array()) {

		// it musst be an array
		$args = (array)$args;

		$nParams = count($args);

		if (!isset($this->_hookCache[$hookname . ':' . $nParams]))
		return true;
		$return = true;
		foreach ($this->_hookCache[$hookname . ':' . $nParams] as $func) {
			if ($func[2]) {
				if ($func[3])
				$this->loadPackage($func[3], false, false);
				include_once($func[2]);
			}
			$this->loadPackage(preg_replace("/^package_/", "", $func[0]), false, false);
			$mFunktionReturn = call_user_func_array(array($func[0], '__hook_' . $func[1]), $args);
			if ($mFunktionReturn === false){
				$return = false;
			} else {
				return $mFunktionReturn;
			}
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
	public function loadPackage($packageName, $tplEnable = true, $initialize = true, $loadDep = true) {
		if (!in_array($packageName, $this->_loadedLang) && is_a(Package::$tpl, 'Smarty')) {
			$this->_loadedLang[] = $packageName;
			Package::loadLang(Package::$tpl, $packageName);
		}
		$dep = array();
		if (isset($this->_dependencyCache[$packageName]) && $this->_dependencyCache[$packageName]['active'] == true) {
			if (!in_array($packageName, $this->_loadedLang) && is_a(Package::$tpl, 'Smarty')) {
				$this->_loadedLang[] = $packageName;
				Package::loadLang(Package::$tpl, $packageName);
			}
			include_once($this->_packagesDir . $this->_dependencyCache[$packageName][0] . '/init.php');
			if ($loadDep)
				$dep = $this->_getPackageDependencies($packageName, -1);
			$pack = new $this->_dependencyCache[$packageName][1](false, $dep);
			$pack->setTemplatePolicy($tplEnable);
			$this->_loaded[$packageName] = $pack;
			if ($loadDep)
				$dep = $this->_getPackageDependencies($packageName, 1);
			$pack->displayTpl();
			$pack = new $this->_dependencyCache[$packageName][1]($initialize, $dep);
			$pack->setTemplatePolicy($tplEnable);
			$this->_loaded[$packageName] = $pack;
			$pack->displayTpl();
			return $pack;
		} else {
			return false;
		}
	}

	/**
	 * Gets all dependencies of a package
	 * @param string $package Name of package
	 * @param int $type -1 = loadDep 0 = all 1 = usualDep
	 * @throws LitotexFatalError
	 * @throws LitotexError
	 */
	private function _getPackageDependencies($package, $type = 0) {
		$dep = array();
		$cache = $this->_dependencyCache[$package];
		if($type == 0 || $type == -1){
			foreach ($cache['loadDep'] as $depName) {
				if (isset($this->_loaded[$depName])) {
					$this->_loaded[$depName]->__construct(false, $this->_getPackageDependencies($depName));
					$dep[$depName] = $this->_loaded[$depName];
					continue;
				}
				$loadCache = $this->loadPackage($depName, false, false);
				if (!$loadCache)
				trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $package . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
				$dep[$depName] = $loadCache;
			}
		}
		if($type == 0 || $type == 1){
			foreach ($cache['dep'] as $depName) {
				if (isset($this->_loaded[$depName]))
				continue;
				$loadCache = $this->loadPackage($depName, false, false);
				if (!$loadCache) {
					trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $package . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
				}
			}
		}
		return $dep;
	}

	/**
	 * This function will load package cache from cacheing file
	 * @return bool
	 */
	private function _loadDependencyCache() {
		if (!file_exists($this->_dependencyCacheFile))
		return false;
		$cacheContents = file_get_contents($this->_dependencyCacheFile);
		$cacheContents = str_replace('<?php die(); ?>', '', $cacheContents);
		$cacheContents = explode('%', $cacheContents);
		if (!$this->_checkDependencyCacheExpire($cacheContents[0]))
		return false;
		$this->_dependencyCache = unserialize($cacheContents[1]);
		return true;
	}

	/**
	 * This function checks if the package cache is usable
	 * @param int $startTime time cache was created
	 * @return bool (true = usable)
	 */
	private function _checkDependencyCacheExpire($startTime) {
		if ((time() - $this->_dependencyCacheExpire) < $startTime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function will create a new package cache
	 * @return bool
	 */
	public function generateDependencyCache() {
		$oldPM = Package::$packages;
		Package::setPackageManagerClass($this);
		Permission::clearAvailableTable($this->_packagePrefix, 1);
		if (!is_dir($this->_packagesDir))
		return false;
		$packages = opendir($this->_packagesDir);
		while ($file = readdir($packages)) {
			if ($file == '.' || $file == '..')
			continue;
			if (!file_exists($this->_packagesDir . '/' . $file . '/init.php'))
			continue;
			include_once($this->_packagesDir . '/' . $file . '/init.php');
			$className = 'package_' . $file;
			if (class_exists($className)) {
				$newInfo = call_user_func(array($className, 'registerClass'), $className);
				if (!$newInfo)
				return false;
			}
		}
		$this->_checkDependency();
		Package::setPackageManagerClass($oldPM);
		return $this->_writeDependencyCache();
	}

	/**
	 * This will check wheather or not alle packages needed by a specific package are available
	 * @return bool
	 */
	private function _checkDependency() {
		$return = true;
		$parents = array();
		$checked = array();
		for ($i = 0; $i < 2; $i++) {
			foreach ($this->_dependencyCache as $name => $pack) {
				if ($pack['active'] == false)
				continue;
				foreach ($pack['dep'] as $dep) {
					if (!isset($this->_dependencyCache[$dep])) {
						trigger_error("Could not find package <i>" . $dep . "</i> which is needed by <i>" . $name . "</i>. The package will be deactivated automaticly!", E_USER_NOTICE);
						$this->_dependencyCache[$name]['active'] = false;
						$return = false;
					} else if ($this->_dependencyCache[$dep]['active'] == false) {
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
	public function registerClass($class) {
		$path = str_replace('package_', '', $class);
		$this->_dependencyCache[$path] = array($path, $class);
		$prop = get_class_vars($class);
		$dep = $prop['dependency'];
		$loadDep = $prop['loadDependency'];
		$this->_dependencyCache[$path]['dep'] = $dep;
		$this->_dependencyCache[$path]['loadDep'] = $loadDep;
		$this->_dependencyCache[$path]['active'] = true;
		$pack = $this->loadPackage($path, false, false, false);
		$actions = $pack->getActions();
		foreach ($actions as $action) {
			Permission::registerAvailable($path, $class, $action, $this->_packagePrefix, 1);
		}
		return true;
	}

	/**
	 * This function will write a cacheing file for packages
	 * @return bool
	 */
	private function _writeDependencyCache() {
		$newfile = '<?php die(); ?>' . time() . '%' . serialize($this->_dependencyCache);
		$file = fopen($this->_dependencyCacheFile, 'w');
		if (!$file)
		return false;
		fwrite($file, $newfile, 1000000);
		fclose($file);
	}

	/**
	 * @TODO: Almost everything
	 */
	public function activatePackage() {

	}

	/**
	 * @TODO: Almost everything
	 */
	public function deactivatePackage() {

	}

	/**
	 * This will install a new package
	 * @param string $location path to the package
	 * @param string $packageName name of the package
	 */
	public function installPackage($location, $packageName) {
		include_once('installer.class.php');
		if (!file_exists($location . '/installer.php'))
		return false;
		include_once($location . '/installer.php');
		$className = "installer_" . $packageName;
		$installer = new $className($location, $packageName);
	}

	/**
	 * @TODO: Almost everything
	 */
	public function removePackage() {

	}

	/**
	 * @TODO: Almost everything
	 */
	public function updatePackage($location, $packageName) {
		include_once('installer.class.php');
		if (!file_exists($location . '/installer.php'))
		return false;
		include_once($location . '/installer.php');
		$className = "installer_" . $packageName;
		$installer = new $className($location, $packageName);
	}

	/**
	 * @TODO: Almost everything
	 */
	public function getChangeLog() {

	}

	public function updateRemotePackageList($prefixRegister) {
		$data = file_get_contents('http://localhost/LitotexUpdateServer/Litotex8/index.php?package=projects&action=getList&platform=0.8.x'); //TODO: Fallback to CURL? TODO: Static? Fail!
		//Check if we have valid XML (should be if the server is up and running!)
		try {
			@$xmlData = new SimpleXMLElement($data);
		} catch (Exception $e) {
			throw new LitotexError('E_couldNotRetrievePackageList');
		}
		$systemData = $xmlData->attributes();
		if ($systemData['responsetype'] != 'packageList') {
			throw new LitotexError('E_wrongListRetrieved');
		}
		//Every check passed :)
		//after this we can delete the old database tables...
		Package::$pdb->query("TRUNCATE TABLE `lttx1_package_list`");
		$dataSection = $xmlData->data;
		foreach ($dataSection->children() as $package) {
			$packageAttributes = $package->attributes();
			$dedPM = (isset($prefixRegister[(string) $packageAttributes['prefix']])) ? $prefixRegister[(string) $packageAttributes['prefix']] : $this;
			$installed = false;
			$name = (string) $packageAttributes['name'];
			$description = (string) $packageAttributes['description'];
			$author = (string) $package->author['name'];
			$authorMail = (string) $package->author['mail'];
			$version = (string) $packageAttributes['version'];
			$update = false;
			$critupdate = false;
			$changelog = array();
			$signed = false;
			$signedOlder = false;
			$fullSigned = false;
			$fullSignedOlder = false;
			$signInfo = array();
			$releaseDate = false;
			$dependency = array();
			$prefix = (string) $packageAttributes['prefix'];
			//Already installed? & Updates available
			if ($dedPM->exists($packageAttributes['name'])) {
				$installed = true;
				//Check if updates are available (might be as the software is installed thought)
				$compare = self::compareVersionNumbers($dedPM->getVersionNumber($packageAttributes['name']), $packageAttributes['version']);
				if ($compare == 1) {
					$update = true;
				}
			}
			//Changelog & Critupdates & Releasedate
			foreach ($package->changelog as $changelogElement) {
				$changelogAttributes = $changelogElement->attributes();
				if ($version == $changelogAttributes['version']) {
					$releaseDate = (string) $changelogAttributes['date'];
				}
				$new = self::compareVersionNumbers($dedPM->getVersionNumber($name), $changelogAttributes['version']);
				$changelog[] = array('text' => (string) $changelogAttributes['text'], 'date' => (string) $changelogAttributes['date'], 'crit' => $changelogAttributes['crit'] == 1, 'new' => $new, 'version' => (string) $changelogAttributes['version']);
				if ($new == 1 && $changelogAttributes['crit'] == 1) {
					$critupdate = true;
				}
			}
			//Signed
			foreach ($package->signed as $signElement) {
				if ($version == $signElement['version']) {
					$signed = true;
					$signedOlder = true;
					if ($signElement['completeReview']) {
						$fullSigned = true;
						$fullSignedOlder = true;
					}
				}
				$signedOlder = true;
				if ($signElement['completeReview']) {
					$fullSignedOlder = true;
				}
				$signInfo[] = array('version' => (string) $signElement['version'], 'completeReview' => (string) $signElement['completeReview'], 'comment' => (string) $signElement['comment']);
			}
			//Dependency
			foreach ($package->dependency as $dependencyElement) {
				$dependencyAttributes = $dependencyElement->attributes();
				$installedDep = (int) $dedPM->exists($dependencyAttributes['name']);
				if ($installedDep) {
					$up2date = self::compareVersionNumbers($dedPM->getVersionNumber($dependencyAttributes['name']), $dependencyAttributes['minVersion']);
					if ($up2date == 0 || $up2date == 1)
					$installedDep = 2;
				}
				$dependency[] = array('name' => (string) $dependencyAttributes['name'], 'minVersion' => (string) $dependencyAttributes['minVersion'], 'installed' => $installedDep);
			}
			//Now we have to write all this to the database :)
			Package::$pdb->prepare("INSERT INTO  `lttx1_package_list` (`ID`, `name`, `prefix`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `releaseDate`, `signInfo`, `dependencies`, `changelog`)
				VALUES (NULL ,  ?, ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?, ?, ?, ?);")->execute(
			array($name, $prefix, $installed, $update, $critupdate, $version, $description, $author, $authorMail, $signed, $signedOlder, $fullSigned, $fullSignedOlder, $releaseDate, serialize($signInfo), serialize($dependency), serialize($changelog)));
		}
	}

	public function getVersionNumber($package) {
		if (!$this->exists($package))
		return false;
		$prop = get_class_vars('package_' . $package);
		return $prop['version'];
	}

	//Special thanks to sinus :)
	//http://freebg.de/wbb/index.php?page=Thread&postID=6842#post6842

	/**
	 * Compares 2 Version numbers
	 * @param string $local_version
	 * @param string $remote_version
	 * 0 = error
	 * 1= $local_version < $remote_version
	 * 2= $local_version = $remote_version
	 * 3= $local_version > $remote_version
	 */
	public static function compareVersionNumbers($local_version, $remote_version) {
		// Variablen definieren
		$iReturn = 0;
		$aNewExpL = array();
		$aNewExpR = array();

		// Variablen prüfen
		if (!$local_version || !$remote_version)
		return $iReturn;

		// Daten verarbeiten
		$aExpL = explode(".", $local_version);
		$aExpR = explode(".", $remote_version);

		// Versionsnummer auffüllen
		for ($x = 0; $x != 3; $x++) {
			if (!isset($aExpL[$x]))
			$aNewExpL[] = 0;
			if (!isset($aExpR[$x]))
			$aNewExpR[] = 0;
		}

		// Zusammenfügen
		$aNewExpL = array_merge($aNewExpL, $aExpL);
		$aNewExpR = array_merge($aNewExpR, $aExpR);

		// Versionsvergleich
		if (implode(".", $aNewExpL) != implode(".", $aNewExpR)) {
			for ($x = 0; $x != 3; $x++) {
				if ($aNewExpL[$x] != $aNewExpR[$x]) {
					if ($aNewExpL[$x] > $aNewExpR[$x]) {
						$iReturn = 3;
						break(1);
					} else {
						$iReturn = 1;
						break(1);
					}
				}
			}
		}
		else
		$iReturn = 2;

		// Ergebnis zurückgeben
		return $iReturn;
	}

	/**
	 * @TODO: Almost everything
	 */
	public function copyRemotePackage($package, $platform) {
		require_once(LITO_FRONTEND_ROOT . 'packages/core/classes/thirdparty/PclZip.class.php');
		$remote = file_get_contents('http://localhost/LitotexUpdateServer/Litotex8/index.php?package=projects&action=fetch&packageName=' . urldecode($package) . '&platform=' . urlencode($platform));
		if (!$remote)
		throw new LitotexError('E_couldNotFetchPackage', $package);
		try {
			@$xml = new SimpleXMLElement($remote);
		} catch (Exception $e) {

		}
		if (!isset($xml) || !$xml)
		throw new LitotexError('E_couldNotFetchPackage', $package);
		$systemData = $xml->attributes();
		if ($systemData['responsetype'] != 'packageFetch') {
			throw new LitotexError('E_wrongFetchRetrieved');
		}
		$package = $xml->package->attributes();
		$handler = @fopen($package['file'], 'r');
		if (!$handler)
		throw new LitotexError("E_couldNotLoadPackage", $package['name']);
		$cache = @fopen(LITO_ROOT . 'files/cache/' . $package . '.' . $package['version'] . '.' . $package['platform'] . '.cache.zip', 'w');
		if (!$cache)
		throw new LitotexError("E_couldNotOpenCachePackage", LITO_ROOT . 'files/cache/' . $package . '.' . $package['version'] . '.' . $package['platform'] . '.cache.zip');
		while (!feof($handler)) {
			fwrite($cache, fread($handler, 10000000));
		}
		fclose($handler);
		fclose($cache);
		$zip = new PclZip(LITO_ROOT . 'files/cache/' . $package . '.' . $package['version'] . '.' . $package['platform'] . '.cache.zip');
		$zip->extract($p_path = LITO_ROOT . 'files/cache/' . $package . '.' . $package['version'] . '.' . $package['platform'] . '.cache');
	}

	/**
	 * @TODO: Almost everything
	 */
	public function searchRemotePackageList() {

	}

	/**
	 * Checks if a package exists
	 * @param string $package packagename
	 * @return bool
	 */
	public function exists($package) {
		if (file_exists($this->_packagesDir . '/' . $package . '/init.php'))
		return true;
		return false;
	}

	/**
	 * This will display tplMods please use the smarty function in order to get the best results!
	 * @param string $position name of the position
	 * @return bool
	 */
	public function displayTplModification($position, $surroundDiv) {

		$return = true;

		if (!isset($this->_tplModificationCache[$position])){
			return $return;
		}

		foreach ($this->_tplModificationCache[$position] as $func) {

			if ($func[4] == false){
				continue;
			}

			if ($func[2]) {
				if ($func[3]){
					$this->loadPackage($func[3], false, false);
				}
				include_once($func[2]);
			}

			$pack = $this->loadPackage(preg_replace("/^package_/", "", $func[0]), false, false);

			if (!Package::$perm->checkPerm($pack, $func[1], $func[0])) {
				continue;
			}

			ob_start();
			echo '<div'.$surroundDiv.'>';
			$bCallFunction = call_user_func_array(array($func[0], '__tpl_' . $func[1]), array());
			echo '</div>';
			$sHtml = ob_get_contents();

			ob_end_clean();
			$hookCache = Package::$packages->callHook('displayTplModification', array($sHtml, $position, $pack, $func));
			$sHtml = ($hookCache === true)?$sHtml:$hookCache;

			echo $sHtml;

			if (!$bCallFunction){
				$return = false;
			}
				
		}

		return $return;
	}

	public function createBackup($package) {
		if (!$this->exists($package))
		throw new LitotexError('E_packageDoesNotExist', $package);
		if (!is_dir(LITO_ROOT . 'backup'))
		throw new LitotexError('E_noBackupDir');
		if (!is_writeable(LITO_ROOT . 'backup'))
		throw new LitotexError('E_backupNotWriteable');
		$saveDirName = LITO_ROOT . 'backup/' . $package . date('c', time()) . '/';
		if (is_dir($saveDirName))
		throw new LitotexError('E_backupOverride');
		mkdir($saveDirName);
		//Here we go, everything should work
		mkdir($saveDirName . 'package');
		mkdir($saveDirName . 'template');
		$tplDir = opendir($this->_tplDir);
		while ($file = readdir($tplDir)) {
			if ($file == '.' || $file == '..')
			continue;
			if (!is_dir($this->_tplDir . $file))
			continue;
			if (is_dir($this->_tplDir . $file . '/' . $package)) {
				mkdir($saveDirName . 'template/' . $file . '/');
				self::recursiveCopy($this->_tplDir . $file . '/' . $package, $saveDirName . 'template/' . $file . '/' . $package);
			}
		}
		self::recursiveCopy($this->_packagesDir . $package, $saveDirName . 'package/' . $package);
		return $saveDirName;
	}

	public function restoreBackup($package, $path) {
		if (!$path)
		return false;
		if (!is_dir($path))
		throw new LitotexError('E_backupPathDoesNotExist');
		if (!is_dir($path . '/package'))
		throw new LitotexError('E_backupNoPackageDir');
		if (!is_dir($path . '/template'))
		throw new LitotexError('E_backupNoTemplateDir');
		self::recursiveCopy($path . '/package/' . $package, $this->_packagesDir . $package);
		self::recursiveCopy($path . '/template/', $this->_tplDir);
	}

	public static final function recursiveCopy($source, $destination, $blacklist = array()) {
		foreach ($blacklist as $fileItem) {
			$firstOccurance = substr_compare($destination, $fileItem, strlen($fileItem) * -1);
			if ($firstOccurance != 0)
			continue;
			return false;
		}
		if (!file_exists($source))
		return false;
		$source = preg_replace("/\/$/", '', $source);
		$destination = preg_replace("/\/$/", '', $destination);
		if (!is_dir(($source))) {
			if(file_exists($destination))
			unlink($destination);
			copy($source, $destination);
			$result = Package::$pdb->prepare("UPDATE `lttx1_file_hash` SET `hash` = ? WHERE `file` = ?");
			$result->execute(array(md5_file($source), $destination));
			if($result->rowCount() <= 0)
			Package::$pdb->prepare("INSERT INTO `lttx1_file_hash` (`hash`, `file`) VALUES (?, ?)")->execute(array(md5_file($source), $destination));
			return true;
		} else if (!is_dir($destination)) {
			mkdir($destination);
		}
		$dir = opendir($source);
		while ($file = readdir($dir)) {
			if ($file == '..' || $file == '.')
			continue;
			self::recursiveCopy($source . '/' . $file, $destination . '/' . $file, $blacklist);
		}
	}

	public static final function reloadFileHashTable() {
		$startdir = LITO_FRONTEND_ROOT;
		//First clear old storage, we will completly rewrite it thought
		Package::$pdb->query("TRUNCATE TABLE `lttx1_file_hash`");
		//Done... write the new data
		self::_insertFileHashReq($startdir);
	}

	private static final function _insertFileHashReq($file) {
		if (!file_exists($file))
		return false;
		if (!is_dir($file)) {
			Package::$pdb->prepare("INSERT INTO `lttx1_file_hash` (`file`, `hash`) VALUES (?, ?)")->execute(array(str_replace('//', '/', $file), md5_file($file)));
			return true;
		}
		$dir = opendir($file);
		while ($fileNew = readdir($dir)) {
			if ($fileNew == '.' || $fileNew == '..' || in_array($fileNew, self::$_fileHashBlacklist))
			continue;
			self::_insertFileHashReq($file . '/' . $fileNew);
		}
		return true;
	}

	public final function compareFileHash($fileName) {
		if (!file_exists(LITO_FRONTEND_ROOT . $this->_packagePrefix . '/' . $fileName) || is_dir(LITO_FRONTEND_ROOT . $this->_packagePrefix . '/' . $fileName))
		return true;
		//The file exists, check if its original
		$result = Package::$pdb->prepare("SELECT `hash` FROM `lttx1_file_hash` WHERE `file` = ?");
		$result->execute(array(str_replace('//', '/', LITO_FRONTEND_ROOT . $this->_packagePrefix . '/' . $fileName)));
		if ($result->rowCount() < 1 || !isset($result[0]))
		return false;
		return md5_file(LITO_FRONTEND_ROOT . $this->_packagePrefix . '/' . $fileName) == $result[0];
	}

}
