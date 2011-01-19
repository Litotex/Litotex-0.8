<?php
class package_acp_users extends acpPackage{
	
	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_users';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		package::addJsFile('users.js', 'acp_users');
		package::addCssFile('users.css', 'acp_users');
		return true;
	}
	
	public function __action_new(){
		$this->__action_edit();
		return true;
	}
	
	public function __action_edit(){
		
		$this->_theme = 'edit.tpl';
		
		$iUserId = 0;
		
		if(isset($_GET['id'])){
			$iUserId = (int)$_GET['id'];
		}		
		
		$oUser = new user($iUserId);
		
		self::$tpl->assign('oUser', $oUser);
		
		return true;
	}
	
	public function __action_list(){
		
		$this->_theme = 'list.tpl';
		
		$aUsers = array();
		
		$aUsers = (array)user::search('');
		
		self::$tpl->assign('aUsers', $aUsers);
		
		return true;
	}
	
	public function __action_save(){
		
		$this->_theme = 'empty.tpl';
		
		$aError = array();
		
		if(isset($_POST['user'])){
			$aUserData = $_POST['user'];
		} else {
			$aError[] = self::getLanguageVar('users_no_data_found');
		}
		
		foreach((array)$aUserData as $aUser){
			if(empty($aUser['username'])){
				$aError[] = self::getLanguageVar('users_no_username');
			}
			if(empty($aUser['email'])){
				$aError[] = self::getLanguageVar('users_no_email');
			}
		}
		if(empty($aError)){
			try {
				foreach((array)$aUserData as $iUserId => $aUser){
					$oUser = new user((int)$iUserId);
					$oUser->update($aUser);
				}
			} catch (Exception $e) {
				$aError[] = self::getLanguageVar('users_error');
				$aError[] = $e->getMessage();
			}
		}
		
		$aTransfer = array();
		$aTransfer['errors'] = $aError;
		if(empty($aError)){
			$aTransfer['message'] = self::getLanguageVar('users_success');
			if(key($aUserData) == 0){
				$aTransfer['task'] = 'resetFields';
			}
		}
		
		echo json_encode($aTransfer);
		
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