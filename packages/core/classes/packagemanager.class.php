<?php
error_reporting(E_ALL);
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
		return;
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
	public function setHookCachePolicy(){
		//TODO
	}
	/**
	 * This function registers a new hook, it will be casted when generateHookCache is casted
	 * @param str $class classname of hook saeing class
	 * @param str $hookname name of the hook (called)
	 * @param int $nParams number of params the hook will work with
	 * @param str $function name of the function that should be used if it is not named __hook_$hookname
	 * @return bool
	 */
	public function registerHook($class, $hookname, $nParams, $function){
		if(!method_exists($class, '__hook_'.$function))
			return false;
		$this->_hookCache[$hookname.':'.$nParams][] = array($class, $function);
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
			if(!call_user_func_array(array($func[0], '__hook_' . $func[1]), $args))
				$return = false;
		}
		return $return;
	}
	/**
	 * This function will create an instance of a package set by $packageName
	 * @param str $packageName name of class (without package_*)
	 * @return bool on failure | instance of class
	 */
	public function loadPackage($packageName, $tplEnable = true){
		if(isset($this->_dependencyCache[$packageName])){
			include_once($this->_packagesDir . '/' . $this->_dependencyCache[$packageName][0] . '/init.php');
			$pack = new $this->_dependencyCache[$packageName][1];
			$pack->setTemplatePolicy($tplEnable);
			return $pack;
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
		return $this->_writeDependencyCache();
	}
	/**
	 * This function will register a class for the package cache
	 * @param str $class name of class
	 * @return bool
	 */
	public function registerClass($class){
		$path = str_replace('package_', '', $class);
		$this->_dependencyCache[$path] = array($path, $class);
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
	public function installPackage(){
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
}
