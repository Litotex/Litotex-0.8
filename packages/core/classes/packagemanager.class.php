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
	/**
	 * This will automaticly check the tplMod cache expire and load it if it is still working
	 * if so it will load it automaticly
	 * @return bool
	 */
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
	/**
	 * This will check whether or not the tplMod cache is expired
	 * @param int $startTime unix timestamp (generation time of the cache)
	 * @return bool expired
	 */
	private function _checkTplModificationCacheExpire($startTime){
		if((time()-$this->_tplModificationCacheExpire) < $startTime){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * This will generate a new tplModCache and saves it when it's done
	 * @return bool
	 */
	public function generateTplModificationCache(){
		package::$db->Execute("DELETE FROM `lttx_permissionsAvailable` WHERE `type` = ? AND `packageDir` = ?", array(2, PACKAGE_PREFIX));
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
		if(!$this->_orderTplModificationCache()){
			throw new lttxFatalError("Could not fetch tplMod settings, this might be a serious database issue!");
		}
		package::$db->Execute("DELETE FROM `lttx_tplModificationSort` WHERE `packageDir` = ?", array(PACKAGE_PREFIX));
		return $this->_writeTplModificationCache();
	}
	/**
	 * This will write a new tplMod cache to the disc
	 * @return bool
	 */
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
				package::$db->Execute("INSERT INTO `lttx_tplModificationSort` (`class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES (?, ?, ?, ?, ?, ?)", array($item[0], $item[1], $position, $item[4], $n, PACKAGE_PREFIX));
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
	/**
	 * This will rearange the tplMod cache by using the settings saved in the darabase
	 * @return bool
	 */
	private function _orderTplModificationCache(){
		$database = package::$db->Execute("SELECT `class`, `function`, `position`, `sort`, `active` FROM `lttx_tplModificationSort` WHERE `packageDir` = ? ORDER BY `sort` ASC", array(PACKAGE_PREFIX));
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
			$this->_tplModificationCache[$key][4] =  $value[4];
			$newOrder[$value[2]][] = $this->_tplModificationCache[$key];
			unset($this->_tplModificationCache[$key]);
		}
		foreach($this->_tplModificationCache as $key => $value){
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
		$this->_hookCache[$hookname.':'.$nParams][] = array($class, $function, $file, $packageName);
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
	public function registerTplModification($class, $function, $file, $packageName){
		if($file){
			include_once($file);
		}
		if(!method_exists($class, '__tpl_'.$function))
		return false;
		package::$db->Execute("INSERT INTO `lttx_permissionsAvailable` (`type`, `package`, `class`, `function`, `packageDir`) VALUES (?, ?, ?, ?, ?)", array(2, $packageName, $class, $function, PACKAGE_PREFIX));
		$this->_tplModificationCache[$class.':'.$function] = array($class, $function, $file, $packageName, false);
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
	public function loadPackage($packageName, $tplEnable = true, $initialize = true, $loadDep = true){
		if(!in_array($packageName, $this->_loadedLang) && is_a(package::$tpl, 'Smarty')){
			$this->_loadedLang[] = $packageName;
			package::loadLang(package::$tpl, $packageName);
		}
		$dep = array();
		if(isset($this->_dependencyCache[$packageName]) && $this->_dependencyCache[$packageName]['active'] == true){
			if(!in_array($packageName, $this->_loadedLang) && is_a(package::$tpl, 'Smarty')){
				$this->_loadedLang[] = $packageName;
				package::loadLang(package::$tpl, $packageName);
			}
			include_once($this->_packagesDir . '/' . $this->_dependencyCache[$packageName][0] . '/init.php');
			if($loadDep)
				$dep = $this->_getPackageDependencies($packageName);
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
	 * @throws lttxFatalError
	 * @throws lttxError	 
	 */
	private function _getPackageDependencies($package){
		$dep = array();
		$cache = $this->_dependencyCache[$package];
		foreach($cache['loadDep'] as $depName){
			if(isset($this->_loaded[$depName])){
				$this->_loaded[$depName]->__construct(false, $this->_getPackageDependencies($depName));
				$dep[$depName] = $this->_loaded[$depName];
				continue;
			}
			$loadCache = $this->loadPackage($depName, false, false);
			if(!$loadCache)
				trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $package . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
			$dep[$depName] = $loadCache;
		}
		foreach($cache['dep'] as $depName){
			if(isset($this->_loaded[$depName]))
				continue;
			$loadCache = $this->loadPackage($depName, false, false);
			if(!$loadCache){
				trigger_error("Could not load package <i>" . $depName . "</i> but <i>" . $package . '</i> depends on it. Packagemanager failed.', E_USER_ERROR);
			}
		}
		return $dep;
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
		package::$db->Execute("DELETE FROM `lttx_permissionsAvailable` WHERE `packageDir` = ? AND `type` = ?", array(PACKAGE_PREFIX, 1));
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
	/**
	 * This will check wheather or not alle packages needed by a specific package are available
	 * @return bool
	 */
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
		$pack = $this->loadPackage($path, false, false, false);
		$actions = $pack->getActions();
		foreach($actions as $action){
			package::$db->Execute("INSERT INTO `lttx_permissionsAvailable` (`type`, `package`, `class`, `function`, `packageDir`) VALUES (?, ?, ?, ?, ?)", array(1, $path, $class, $action, PACKAGE_PREFIX));
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
	/**
	 * @TODO: Almost everything
	 */
	public function activatePackage(){
	}
	/**
	 * @TODO: Almost everything
	 */
	public function deactivatePackage(){
	}
	/**
	 * This will install a new package
	 * @param string $location path to the package
	 * @param string $packageName name of the package
	 */
	public function installPackage($location, $packageName){
		include_once('installer.class.php');
		if(!file_exists($location . '/installer.php'))
		return false;
		include_once($location . '/installer.php');
		$className = "installer_" . $packageName;
		$installer = new $className($location, $packageName);
	}
	/**
	 * @TODO: Almost everything
	 */
	public function removePackage(){
	}
	/**
	 * @TODO: Almost everything
	 */
	public function updatePackage($location, $packageName){
		include_once('installer.class.php');
		if(!file_exists($location . '/installer.php'))
		return false;
		include_once($location . '/installer.php');
		$className = "installer_" . $packageName;
		$installer = new $className($location, $packageName);
	}
	/**
	 * @TODO: Almost everything
	 */
	public function getChangeLog(){
	}
	/**
	 * @TODO: Almost everything
	 */
	public function updateRemotePackageList(){
		$data = file_get_contents('http://localhost/LitotexUpdateServer/Litotex8/index.php?package=projects&action=getList&platform=0.8.x'); //TODO: Fallback to CURL? TODO: Static? Fail!
		//Check if we have valid XML (should be if the server is up and running!)
		try{
			@$xmlData = new SimpleXMLElement($data);
		}catch (Exception $e){
			throw new lttxError('E_couldNotRetrievePackageList');
		}
		$systemData = $xmlData->attributes();
		if($systemData['responsetype'] != 'packageList'){
			throw new lttxError('E_wrongListRetrieved');
		}
		//Every check passed :)
		//after this we can delete the old database tables...
		package::$db->Execute("TRUNCATE TABLE `lttx_packageList`");
		$dataSection = $xmlData->data;
		foreach ($dataSection->children() as $package){
			$packageAttributes = $package->attributes();
			$installed = false;
			$name = (string)$packageAttributes['name'];
			$description = (string)$packageAttributes['description'];
			$author = (string)$package->author['name'];
			$authorMail = (string)$package->author['mail'];
			$version = (string)$packageAttributes['version'];
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
			//Already installed? & Updates available
			if(self::exists($packageAttributes['name'])){
				$installed = true;
				//Check if updates are available (might be as the software is installed thought)
				$compare = self::compareVersionNumbers(self::getVersionNumber($packageAttributes['name']), $packageAttributes['version']);
				if($compare == 1){
					$update = true;
				}
			}
			//Changelog & Critupdates & Releasedate
			foreach($package->changelog as $changelogElement){
				$changelogAttributes = $changelogElement->attributes();
				if($version == $changelogAttributes['version']){
					$releaseDate = (string)$changelogAttributes['date'];
				}
				$new = self::compareVersionNumbers(self::getVersionNumber($name), $changelogAttributes['version']);
				$changelog[] = array('text' => (string)$changelogAttributes['text'], 'date' => (string)$changelogAttributes['date'], 'crit' => (bool)$changelogAttributes['crit'], 'new' => $new, 'version' => (string)$changelogAttributes['version']);
				if($new == 1 && $changelogAttributes['crit'] == 1){
					$critupdate = true;
				}
			}
			//Signed
			foreach($package->signed as $signElement){
				if($version == $signElement['version']){
					$signed = true;
					$signedOlder = true;
					if($signElement['completeReview']){
						$fullSigned = true;
						$fullSignedOlder = true;
					}
				}
				$signedOlder = true;
				if($signElement['completeReview']){
					$fullSignedOlder = true;
				}
				$signInfo[] = array('version' => (string)$signElement['version'], 'completeReview' => (string)$signElement['completeReview'], 'comment' => (string)$signElement['comment']);
			}
			//Dependency
			foreach($package->dependency as $dependencyElement){
				$dependencyAttributes = $dependencyElement->attributes();
				$installedDep = (int)self::exists($dependencyAttributes['name']);
				if($installedDep){
					$up2date = self::compareVersionNumbers(self::getVersionNumber($dependencyAttributes['name']), $dependencyAttributes['minVersion']);
					if($up2date == 0 || $up2date == 1)
						$installedDep = 2;
				}
				$dependency[] = array('name' => (string)$dependencyAttributes['name'], 'minVersion' => (string)$dependencyAttributes['minVersion'], 'installed' => $installedDep);
			}
			//Now we have to write all this to the database :)
			package::$db->Execute("INSERT INTO  `lttx_packageList` (`ID`, `name`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `releaseDate`, `signInfo`, `dependencies`, `changelog`)
				VALUES (NULL ,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?,  ?, ?, ?, ?);",
				array($name, $installed, $update, $critupdate, $version, $description, $author, $authorMail, $signed, $signedOlder, $fullSigned, $fullSignedOlder, $releaseDate, serialize($signInfo), serialize($dependency), serialize($changelog)));
		}
	}
	
	public static function getVersionNumber($package){
		if(!self::exists($package))
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
	public static function compareVersionNumbers($local_version, $remote_version){
        // Variablen definieren
        $iReturn = 0;
        $aNewExpL = array();
        $aNewExpR = array();

        // Variablen prüfen
        if(!$local_version || !$remote_version) 
            return $iReturn;

        // Daten verarbeiten
        $aExpL = explode( ".", $local_version );
        $aExpR = explode( ".", $remote_version );

        // Versionsnummer auffüllen
        for( $x=0; $x!=3; $x++ )
        {
            if( !isset( $aExpL[ $x ] ) ) $aNewExpL[] = 0;
            if( !isset( $aExpR[ $x ] ) ) $aNewExpR[] = 0;
        }

        // Zusammenfügen
        $aNewExpL = array_merge( $aNewExpL, $aExpL );
        $aNewExpR = array_merge( $aNewExpR, $aExpR );

        // Versionsvergleich
        if( implode( ".", $aNewExpL ) != implode( ".", $aNewExpR ) )
        {
            for( $x=0; $x!=3; $x++)
            {
                if( $aNewExpL[ $x ] != $aNewExpR[ $x ] )
                {
                    if( $aNewExpL[ $x ] > $aNewExpR[ $x ] )
                    {
                        $iReturn = 3;
                        break(1);
                    }
                    else
                    {
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
	public function copyRemotePackage($package, $platform){
		require_once(LITO_ROOT . 'packages/core/classes/pclzip.class.php');
		$remote = file_get_contents('http://localhost/LitotexUpdateServer/Litotex8/index.php?package=projects&action=fetch&packageName=' . urldecode($package) . '&platform=' . urlencode($platform));
		if(!$remote)
			throw new lttxError('E_couldNotFetchPackage', $package);
		try{
			@$xml = new SimpleXMLElement($remote);
		} catch(Exception $e){}
		if(!isset($xml) || !$xml)
			throw new lttxError('E_couldNotFetchPackage', $package);
		$systemData = $xml->attributes();
		if($systemData['responsetype'] != 'packageFetch'){
			throw new lttxError('E_wrongFetchRetrieved');
		}
		$package = $xml->package->attributes();
		$handler = fopen($package['file'], 'r');
		$cache = fopen(LITO_ROOT . 'files/cache/' . $package . '.' . $package['version'] . '.' . $package['platform'] . '.cache.zip', 'w');
		while(!feof($handler)){
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
	/**
	 * This will display tplMods please use the smarty function in order to get the best results!
	 * @param string $position name of the position
	 * @return bool
	 */
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
			$pack = $this->loadPackage(preg_replace("/^package_/", "", $func[0]), false, false);
			if(!package::$perm->checkPerm($pack, $func[1], $func[0])){
				continue;
			}
			if(!call_user_func_array(array($func[0], '__tpl_' . $func[1]), array()))
			$return = false;
		}
		return $return;
	}
	public static function createBackup($package){
		if(!self::exists($package))
			throw new lttxError('E_packageDoesNotExist', $package);
		if(!is_dir(LITO_ROOT . 'backup'))
			throw new lttxError('E_noBackupDir');
		if(!is_writeable(LITO_ROOT . 'backup'))
			throw new lttxError('E_backupNotWriteable');
		$saveDirName = LITO_ROOT . 'backup/' . $package . date('c', time()) . '/';
		if(is_dir($saveDirName))
			throw new lttxError('E_backupOverride');
		mkdir($saveDirName);
		//Here we go, everything should work
		mkdir($saveDirName . 'package');
		mkdir($saveDirName . 'template');
		$tplDir = opendir(TEMPLATE_DIRECTORY);
		while($file = readdir($tplDir)){
			if($file == '.' || $file == '..')
				continue;
			if(!is_dir(TEMPLATE_DIRECTORY . $file))
				continue;
			if(is_dir(TEMPLATE_DIRECTORY . $file . '/' . $package)){
				mkdir($saveDirName . 'template/' . $file . '/');
				self::recursiveCopy(TEMPLATE_DIRECTORY . $file . '/' . $package, $saveDirName . 'template/' . $file . '/' . $package);
			}
		}
		self::recursiveCopy(MODULES_DIRECTORY . $package, $saveDirName . 'package/' . $package);
		return $saveDirName;
	}
	public static function restoreBackup($package, $path){
		if(!$path)
			return false;
		if(!is_dir($path))
			throw new lttxError('E_backupPathDoesNotExist');
		if(!is_dir($path . '/package'))
			throw new lttxError('E_backupNoPackageDir');
		if(!is_dir($path . '/template'))
			throw new lttxError('E_backupNoTemplateDir');
		self::recursiveCopy($path . '/package/' . $package, MODULES_DIRECTORY . $package);
		self::recursiveCopy($path . '/template/', TEMPLATE_DIRECTORY);
	}
	public static final function recursiveCopy($source, $destination){
		if(!file_exists($source))
			return false;
		$source = preg_replace("/\/$/", '', $source);
		$destination = preg_replace("/\/$/", '', $destination);
		if(!is_dir(($source))){
			if(file_exists($destination))
				unlink($destination);
			copy($source, $destination);
			return true;
		} else if(!is_dir($destination)) {
			mkdir($destination);
		}
		$dir = opendir($source);
		while($file = readdir($dir)){
			if($file == '..' || $file == '.')
				continue;
			self::recursiveCopy($source . '/' . $file, $destination . '/' . $file);
		}
	}
}