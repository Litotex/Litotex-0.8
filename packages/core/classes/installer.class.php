<?php
abstract class installer{
	private $_location = false;
	private $_initialized = false;
	private $_packageName = false;
	public final function __construct($location, $packageName){
		$this->_location = $location;
		$this->_packageName = $packageName;
		$this->_checkData();
		$this->_install();
	}
	public final function __destruct(){
		
	}
	private final function _checkData(){
		if(!is_dir($this->_location . '/template'))
			throw new Exception("Template directory was not found in $this->_location/template");
		if(!is_dir($this->_location . '/package'))
			throw new Exception("Package directory was not found in $this->_location/package");
		if(!file_exists($this->_location . '/package/init.php'))
			throw new Exception("Package init file directory was not found in $this->_location/package/init.php");
		if(file_exists($this->_location . '/database/install.sql') && !file_exists($this->_location . '/database/remove.sql')){
			throw new Exception("Installation package contains an installation SQL File but lacks support for deinstall it. Please add remove.sql in order to install this application.");
		return true;
		}
	}
	private final function _recursiveCopy($source, $destination){
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
			$this->_recursiveCopy($source . '/' . $file, $destination . '/' . $file);
		}
	}
	private final function _install(){
		$this->_recursiveCopy($this->_location . '/template', TEMPLATE_DIRECTORY . $this->_packageName);
		$this->_recursiveCopy($this->_location . '/package', MODULES_DIRECTORY . $this->_packageName);
		$this->_patchDatabase(true);
		$this->_freeInstall();
	}
	private final function _patchDatabase($install){
		if($install){
			if(!file_exists($this->_location . '/database/install.sql'))
				return true;
			else $file = fopen($this->_location . '/database/install.sql', 'r');
		} else {
			if(!file_exists($this->_location . '/database/remove.sql'))
				return true;
			else $file = fopen($this->_location . '/database/remove.sql', 'r');
		}
		$puffer = '';
		$unbuffer = false;
		$data = array();
		while(!feof($file)){
			$puffer .= fread($file, 10000);
			if(preg_match('/^\-\-/', $puffer)){
				$puffer = '';
				continue;
			}
			if(preg_match('/^\/\*/', $puffer)){
				$puffer = '';
				$unbuffer = true;
				continue;
			}
			if($unbuffer){
				if(preg_match('/\*\//', $puffer)){
					$unbuffer = false;
				}
				$puffer = '';
				continue;
			}
			if(preg_match('/;$/', $puffer)){
				$data[] = $puffer;
				$puffer = '';
			}
		}
		foreach($data as $query){
//			$query = explode(':', $)
			package::$db->Execute($query);
		}
		return true;
	}
	protected abstract function _freeInstall();
}