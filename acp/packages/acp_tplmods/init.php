<?php

class package_acp_tplmods extends acpPackage{
	
	protected $_availableActions = array('main', 'frame');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_tplmods';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		
		self::addJsFile('tplmod.js', 'acp_tplmods');
		self::addCssFile('tplmod.css', 'acp_tplmods');

		
		$aElements = tplModSort::getList();
		package::$tpl->assign('aElements', $aElements);
		package::$tpl->assign('LITO_FRONTEND_URL', LITO_FRONTEND_URL);


		return true;
	}

	public function __action_frame(){

		$this->_theme = 'frame.tpl';

		self::addJsFile('tplmod.js', 'acp_tplmods');
		self::addCssFile('tplmod.css', 'acp_tplmods');

		return true;
	}

	public static function registerHooks(){
		return true;
	}
}