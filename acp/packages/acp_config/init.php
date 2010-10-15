<?php
require_once "classes/config.class.php";
class package_acp_config extends acpPackage{
	protected $_availableActions = array();
	protected $_packageName = 'acp_config';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		return true;
	}
	public static function registerHooks(){
		return true;
	}
}