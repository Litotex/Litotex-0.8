<?php
class package_acp_buildings extends acpPackage{
	protected $_availableActions = array('main', 'list');
	protected $_packageName = 'acp_buildings';
	protected $_theme = 'main.tpl';
	public static $dependency = array('core_buildings');
	
	public function __action_main(){
		return true;
	}
	
	public function __action_list(){
		$this->_theme = 'list.tpl';
		$buildings = building::getAll();
		self::$tpl->assign('buildings', $buildings);
		return true;
	}
	
	public static function registerHooks(){
		return true;
	}
}