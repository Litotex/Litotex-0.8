<?php
/**
 * ACP package to manage packages
 *
 * @author:     Jonas Schwabe <j.s@cascaded-web.com>
 * @licence:	Copyright 2010 Litotex Team
 */
class package_acp_packageManager extends acpPackage {
    protected $_availableActions = array('main', 'listInstalled', 'listUpdates', 'updateRemoteList', 'processUpdates');
    protected $_packageName = 'acp_packageManager';
    protected $_theme = 'main.tpl';
	protected $_frontendPackages = false;
	
	public function runtimeAcp(){
		$this->_frontendPackages = new packages('', false, MODULES_FRONTEND_DIRECTORY, TEMPLATE_FRONTEND_DIRECTORY);
	}
	
    public static function registerHooks() {
        return true;
    }
    public function __action_main(){
    	return true;
    }
    public function __action_listInstalled(){
    	
    }
    public function __action_listUpdates(){
        self::addJsFile('checkbox.js', $this->_packageName);
    	$packages = array();
    	$result = self::$db->Execute("SELECT `ID`, `name`, `update`, `critupdate`, `description`, `author`, `authorMail`, `releaseDate`, `changelog` FROM `lttx_packageList` WHERE `update` = 1");
    	while(!$result->EOF){
                $result->fields['changelog'] = unserialize($result->fields['changelog']);
    		$packages[] = $result->fields;
    		$result->MoveNext();
    	}
    	self::$tpl->assign('updates', $packages);
    	$this->_theme = 'updateList.tpl';
    	return true;
    }
    public function __action_updateRemoteList(){
    	//self::$packages->updateRemotePackageList(array('' => $this->_frontendPackages, 'acp' => self::$packages));
    	$this->_frontendPackages->updateRemotePackageList(array('' => $this->_frontendPackages, 'acp' => self::$packages));
    	header("location: index.php?package=acp_packageManager&action=listUpdates");
    }
}