<?php
class package_acp_main extends acpPackage{
	protected $_availableActions = array('main');
	protected $_packageName = 'acp_main';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		return true;
	}
	public static function registerHooks(){
		return true;
	}
}