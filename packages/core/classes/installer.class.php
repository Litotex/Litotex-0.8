<?php
abstract class installer{
	private $_location = false;
	private $_initialized = false;
	private $_packageName = false;
	private $_backup = false;
	private $_versionOld = false;
	private $_versionNew = false;
        private $_templateDir = TEMPLATE_DIRECTORY; /*TEMPLATE_DIRECTORY or TEMPLATE_FRONTEND_DIRECTORY*/
        private $_packageDir = MODULES_DIRECTORY; /*MODULES_DIRECTORY or MODULES_FRONTEND_DIRECTORY*/
	public final function __construct($location, $packageName){
		$this->_location = $location;
		$this->_packageName = $packageName;
		$this->_versionOld = package::$packages->getVersionNumber($this->_packageName);
		$this->_versionNew = $this->_getVersionNumber($this->_packageName, $this->_location . '/package/init.php');
		$this->_checkData();
		$this->_install();
	}
	public final function __destruct(){
		
	}
	private final function _checkData(){
		if(!is_dir($this->_location . '/template'))
			throw new lttxFatalError("Template directory was not found in $this->_location/template");
		if(!is_dir($this->_location . '/package'))
			throw new lttxFatalError("Package directory was not found in $this->_location/package");
		if(!file_exists($this->_location . '/package/init.php'))
			throw new lttxFatalError("Package init file directory was not found in $this->_location/package/init.php");
		if(file_exists($this->_location . '/database/install.sql') && !file_exists($this->_location . '/database/remove.sql')){
			throw new lttxFatalError("Installation package contains an installation SQL File but lacks support for deinstall it. Please add remove.sql in order to install this application.");
		return true;
		}
	}
	private final function _install(){
		$this->_backup = package::$packages->createBackup($this->_packageName);
		packages::recursiveCopy($this->_location . '/template', $this->_templateDir . 'default/' . $this->_packageName);
		packages::recursiveCopy($this->_location . '/package', $this->_packageDir . $this->_packageName);
		$this->_patchDatabase(true);
		$this->_freeInstall();
	}
	private final function _patchDatabase($install){
		if($install){
			if(!file_exists($this->_location . '/database/install.sql'))
				return true;
			else $fileName = $this->_location . '/database/install.sql';
		} else {
			if(!file_exists($this->_location . '/database/remove.sql'))
				return true;
			else $fileName = $this->_location . '/database/remove.sql';
		}
		$data = simplexml_load_file($fileName);
		if(!isset($data->query))
			return true;
		foreach($data->query as $query){
			if(packages::compareVersionNumbers($this->_versionOld, $query->attributes()->version) == 1 || packages::compareVersionNumbers($this->_versionOld, $query->attributes()->version) == 2 && $install){
				package::$db->Execute($query);
				$errorMsg = package::$db->ErrorMsg();
				if($errorMsg){
					try{
						if($install){
							$this->rollback();
							$this->_patchDatabase(false);
						}
					} catch (Exception $e) {}
					throw new lttxError('E_installerMySQLFailure', $errorMsg);
				}
			}
		}
	}
	private final function _getVersionNumber($modulName, $file){
		if(!file_exists($file))
			return false;
		if(class_exists('package_' . $modulName . 'INSTALLER')){
			$vars = get_class_vars('package_'.$modulName.'INSTALLER');
			return (isset($vars['version']))?$vars['version']:false;
		}
		return eval(preg_replace('/<\?php|\?>|/', '', str_replace('class package_'.$modulName, 'class package_' . $modulName . 'INSTALLER', file_get_contents($file))) . " \$vars = get_class_vars('package_".$modulName."INSTALLER'); return (isset(\$vars['version']))?\$vars['version']:false;");
	}
	private final function rollback(){
		package::$packages->restoreBackup($this->_packageName, $this->_backup);
	}
	protected abstract function _freeInstall();
}