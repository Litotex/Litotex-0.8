<?php
class package_acp_main extends acpPackage{
	protected $_availableActions = array('main','main_redirect');
	protected $_packageName = 'acp_main';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		return true;
	}
    public function __action_main_redirect() {
       self::getTplDir('acp_main') . 'main_redirect.tpl';
        return true;
    }
	public static function registerHooks(){
		return true;
	}
}