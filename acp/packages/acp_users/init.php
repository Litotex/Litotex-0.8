<?php
class package_acp_users extends acpPackage{
	protected $_availableActions = array('main', 'editUser', 'searchUser', 'addUser', 'editUserFields');
	public static $dependency = array('acp_config');
	protected $_packageName = 'acp_users';
	protected $_theme = 'main.tpl';
	public function __action_main(){
		return true;
	}
	public function __action_editUser(){
		$this->_theme = 'editUser.tpl'; 
		if(!isset($_GET['ID'])){
			header("Location: ?package=acp_users");
			exit();
		}
		$user = new user($_GET['ID']);
		self::$tpl->assign('user', $user);
		return true;
	}
	public function __action_searchUser(){
		if(isset($_GET['q'])){
			$result = user::search($_GET['q']);
			self::$tpl->assign('users', $result);
		}
		$this->_theme = 'searchUser.tpl';
		return true;
	}
	public function __action_addUser(){
		$this->_theme = 'addUser.tpl';
		return true;
	}
	public function __action_editUserFields(){
		
	}
	public static function registerHooks(){
		return true;
	}
}