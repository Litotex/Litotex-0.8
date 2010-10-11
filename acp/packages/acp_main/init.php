<?php
class package_acp_main extends package{
	protected $_availableActions = array('main');
	protected $_packageName = 'acp_main';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		return true;
	}
	public static function registerHooks(){
		return true;
	}
	public function runtime(){
		if(!package::$user->isAcpLogin()){
    		header('Location: index.php?package=acp_login');
    		exit();
    	}
	}
}