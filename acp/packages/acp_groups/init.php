<?php
class package_acp_groups extends acpPackage{
	
	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'del');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_groups';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		
		self::addJsFile('groups.js', 'acp_groups');
		self::addCssFile('groups.css', 'acp_groups');
		self::addCssFile('permissions.css', 'acp_permissions');

		return true;
	}

	public function __action_new(){
		$this->__action_edit();
		return true;
	}
	
	public function __action_edit(){
		
		$this->_theme = 'edit.tpl';
		
		$iGroupId = 0;
		
		if(isset($_GET['id'])){
			$iGroupId = (int)$_GET['id'];
		}		
		
		$oGroup = new userGroup($iGroupId);
		
		package::$tpl->assign('oGroup', $oGroup);

		$aUsers = user::search('');
		package::$tpl->assign('aUsers', $aUsers);

		$aGroupUsers = $oGroup->getUsers();

		$sUserList = '';
		foreach((array)$aGroupUsers as $oUser){
			$sUserList .= $oUser->getUsername();
			$sUserList .= ', ';
		}

		package::$tpl->assign('sUserList', $sUserList);


		return true;
	}

	public function __action_del(){

		$this->_theme = 'empty.tpl';

		$iGroupId = 0;

		if(isset($_POST['id'])){
			$iGroupId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$iGroupId = (int)$_GET['id'];
		}

		if($iGroupId <= 0){
			return false;
		}

		$oGroup = new userGroup($iGroupId);
		$oGroup->delete();

		return true;
	}
	
	public function __action_list(){
		
		$this->_theme = 'list.tpl';

		$aGroups = userGroup::getList();

		self::$tpl->assign('aGroups', $aGroups);
		
		return true;
	}
	
	public function __action_save(){
		
		$this->_theme = 'empty.tpl';
		
		$aError = array();
		
		if(isset($_POST['group'])){
			$aGroupData = $_POST['group'];
		} else {
			$aError[] = package::getLanguageVar('groups_no_data_found');
		}
		
		foreach((array)$aGroupData as $aGroup){
			if(empty($aGroup['name'])){
				$aError[] = package::getLanguageVar('groups_no_groupname');
			}
		}
		$iNewGroupId = 0;
		if(empty($aError)){
			try {
				foreach((array)$aGroupData as $iGroupId => $aGroup){
					$oGroup = new userGroup((int)$iGroupId);
					foreach((array)$aGroup as $sColumn => $mValue){
						$oGroup->$sColumn = $mValue;
					}
					$oGroup->save();
					if($iGroupId <= 0){
						$iNewGroupId = $oGroup->ID;
					}
				}
			} catch (Exception $e) {
				$aError[] = package::getLanguageVar('groups_error');
				$aError[] = $e->getMessage();
			}
		}

		if(!empty ($_POST['users']) && $oGroup){
			$aUsers = explode(',', $_POST['users']);
			$oGroup->deleteAllUsers();
			foreach((array)$aUsers as $sUserName){
				$sUserName = trim($sUserName);
				$aUsers = user::search($sUserName);
				$oUser = reset($aUsers);
				if(!$oUser || $oUser->getUserID() <= 0){
					continue;
				}
				$oGroup->addUser($oUser);
			}
		}
		
		$aTransfer = array();
		$aTransfer['errors'] = $aError;
		if(empty($aError)){
			$aTransfer['message'] = package::getLanguageVar('groups_success');
			if(key($aGroupData) == 0){
				$aTransfer['task'] = 'resetFields';
			}
		}
		
		echo json_encode($aTransfer);
		
		return true;
	}
	
	public static function registerHooks(){
		return true;
	}
}