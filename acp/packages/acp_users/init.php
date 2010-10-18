<?php
class package_acp_users extends acpPackage{
	protected $_availableActions = array('main');
	public static $dependency = array('acp_config');
	protected $_packageName = 'acp_users';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		$config = new config();
		$config->addElement('text', 'text', array('type' => 'multiline', 'width' => 100, 'maxLength' => 100), 'default?!', 'LABEL');
		package::$tpl->assign('configForm', $config->getHTML());
		$config = $config->getData();
		return true;
	}
	public static function registerHooks(){
		return true;
	}
}