<?php
class package_acp_users extends acpPackage{
	
	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'ban', 'unban', 'del', 'fields', 'addField', 'sortFields', 'delField');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_users';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		
		self::addJsFile('users.js', 'acp_users');
		self::addCssFile('users.css', 'acp_users');
		self::addCssFile('permissions.css', 'acp_permissions');

		return true;
	}

	public function __action_delField(){
		$this->_theme = 'empty.tpl';
		$oField = userField::getInstance($_POST['field_id']);
		$oField->delete();
	}

	public function __action_sortFields(){
		$this->_theme = 'empty.tpl';
		$aOrder = $_POST['userField'];
		$i = 0;
		foreach((array)$aOrder as $iFieldId){
			if($iFieldId <= 0){
				continue;
			}
			$oField = userField::getInstance($iFieldId);
			$oField->position = $i;
			$oField->save();
			$i++;
		}
	}
	
	public function __action_addField(){
		
		$this->_theme = 'empty.tpl';

		$oNewField = new userField(0);
		$oNewField->key = $_POST['name'];
		$oNewField->type = $_POST['type'];
		$oNewField->optional = $_POST['optional'];
		$oNewField->display = $_POST['display'];
		$oNewField->editable = $_POST['editable'];
		$oNewField->save();
		
		return true;
	}

	public function __action_fields(){
		$this->_theme = 'fields.tpl';

		$aFields = userField::getList();
		package::$tpl->assign('aFields', $aFields);

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
		
		package::$tpl->assign('oUser', $oUser);

		$aFields = userField::getList();
		package::$tpl->assign('aFields', $aFields);
		$aUserGroups = $oUser->getUserGroups();
		if($aUserGroups === false){
			$aUserGroups = array();
		}
		package::$tpl->assign('aUserGroups', $aUserGroups);

		$aGroups = $oUser->getAvailableGroups();
		if($aGroups === false){
			$aUserGroups = array();
		}
		package::$tpl->assign('aGroups', $aGroups);

		return true;
	}

	public function __action_del(){

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if(isset($_POST['id'])){
			$iUserId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$iUserId = (int)$_GET['id'];
		}

		if($iUserId <= 0){
			return false;
		}

		$oUser = new user($iUserId);
		$oUser->delete();

		return true;
	}

	public function __action_ban(){

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if(isset($_POST['id'])){
			$iUserId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$iUserId = (int)$_GET['id'];
		}

		if($iUserId <= 0){
			return false;
		}

		$oUser = new user($iUserId);
		$oUser->banUser();

		return true;
	}

	public function __action_unban(){

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if(isset($_POST['id'])){
			$iUserId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$iUserId = (int)$_GET['id'];
		}

		if($iUserId <= 0){
			return false;
		}

		$oUser = new user($iUserId);
		$oUser->unbanUser();

		return true;
	}
	
	public function __action_list(){
		
		$this->_theme = 'list.tpl';

		$oUser = package::$user;

		$aUsers = array();
		
		$aUsers = (array)user::search('');

		self::$tpl->assign('oUser', $oUser);
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
		$iNewUserId = 0;
		if(empty($aError)){
			try {
				foreach((array)$aUserData as $iUserId => $aUser){
					$oUser = new user((int)$iUserId);
					$oUser->update($aUser);
					if($iUserId <= 0){
						$iNewUserId = $oUser->getUserID();
					}
				}
			} catch (Exception $e) {
				$aError[] = self::getLanguageVar('users_error');
				$aError[] = $e->getMessage();
			}
		}

		if(!empty ($_POST['userfield'])){
			foreach((array)$_POST['userfield'] as $iUserId => $aData){
				if($iUserId <= 0){
					$iUserId = $iNewUserId;
				}
				$oUser = new user($iUserId);
				foreach((array)$aData as $iFieldId => $mValue){
					if($iFieldId <= 0){
						continue;
					}
					$oUser->saveUserFieldData($iFieldId, $mValue);
				}
			}
		}

		$oUser->deleteAllGroups();

		if(!empty ($_POST['group'])){
			foreach((array)$_POST['group'] as $iGroup){
				$oGroup = new userGroup($iGroup);
				$oGroup->addUser($oUser);
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
		
	public function __action_editUserFields(){
		
	}
	
	public static function registerHooks(){
		return true;
	}
}