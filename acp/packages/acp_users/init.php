<?php
/*
 * Copyright (c) 2010 Litotex
*
* Permission is hereby granted, free of charge,
* to any person obtaining a copy of this software and
* associated documentation files (the "Software"),
* to deal in the Software without restriction,
* including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense,
* and/or sell copies of the Software, and to permit
* persons to whom the Software is furnished to do so,
* subject to the following conditions:
*
* The above copyright notice and this permission notice
* shall be included in all copies or substantial portions
* of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
* DEALINGS IN THE SOFTWARE.
*/

class package_acp_users extends acpPackage {

	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'ban', 'unban', 'del', 'fields', 'addField', 'sortFields', 'delField');
	public static $dependency = array('acp_config');
	protected $_packageName = 'acp_users';
	protected $_theme = 'main.tpl';

	public function __action_main() {

		self::addJsFile('users.js', 'acp_users');
		self::addCssFile('users.css', 'acp_users');
		self::addCssFile('permissions.css', 'acp_permissions');

		return true;
	}

	public function __action_delField() {
		$this->_theme = 'empty.tpl';
		$oField = new UserField($_POST['field_id']);
		$oField->delete();
	}

	public function __action_sortFields() {
		$this->_theme = 'empty.tpl';
		$aOrder = $_POST['userField'];
		$i = 0;
		foreach ((array) $aOrder as $iFieldId) {
			if ($iFieldId <= 0) {
				continue;
			}
			$oField = new UserField($iFieldId);
			$oField->setPosition($i);
			$i++;
		}
	}

	public function __action_addField() {

		$this->_theme = 'empty.tpl';

		if(!UserField::create($_POST['name'], $_POST['type'], '', '', $_POST['optional'], $_POST['display'], $_POST['editable']))
			return false;
		return true;
	}

	public function __action_fields() {
		$this->_theme = 'fields.tpl';

		$aFields = UserField::getList();
		Package::$tpl->assign('aFields', $aFields);

		Package::$tpl->assign('fieldTypes', UserField::getTypes());

		return true;
	}

	public function __action_new() {
		$this->__action_edit();
		return true;
	}

	public function __action_edit() {

		$this->_theme = 'edit.tpl';

		$iUserId = 0;

		if (isset($_GET['id'])) {
			$iUserId = (int) $_GET['id'];
		}

		$oUser = new User($iUserId);

		Package::$tpl->assign('oUser', $oUser);

		$aFields = UserField::getList();
		Package::$tpl->assign('aFields', $aFields);
		$aUserGroups = $oUser->getUserGroups();
		if ($aUserGroups === false) {
			$aUserGroups = array();
		}
		Package::$tpl->assign('aUserGroups', $aUserGroups);

		$aGroups = $oUser->getAvailableGroups();
		if ($aGroups === false) {
			$aUserGroups = array();
		}
		Package::$tpl->assign('aGroups', $aGroups);

		return true;
	}

	public function __action_del() {

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if (isset($_POST['id'])) {
			$iUserId = (int) $_POST['id'];
		} else if (isset($_GET['id'])) {
			$iUserId = (int) $_GET['id'];
		}

		if ($iUserId <= 0) {
			return false;
		}

		$oUser = new User($iUserId);
		$oUser->delete();

		return true;
	}

	public function __action_ban() {

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if (isset($_POST['id'])) {
			$iUserId = (int) $_POST['id'];
		} else if (isset($_GET['id'])) {
			$iUserId = (int) $_GET['id'];
		}

		if ($iUserId <= 0) {
			return false;
		}

		$oUser = new User($iUserId);
		$oUser->banUser();

		return true;
	}

	public function __action_unban() {

		$this->_theme = 'empty.tpl';

		$iUserId = 0;

		if (isset($_POST['id'])) {
			$iUserId = (int) $_POST['id'];
		} else if (isset($_GET['id'])) {
			$iUserId = (int) $_GET['id'];
		}

		if ($iUserId <= 0) {
			return false;
		}

		$oUser = new User($iUserId);
		$oUser->unbanUser();

		return true;
	}

	public function __action_list() {

		$this->_theme = 'list.tpl';

		$oUser = Package::$user;

		$aUsers = array();

		$aUsers = (array) User::search('');

		self::$tpl->assign('oUser', $oUser);
		self::$tpl->assign('aUsers', $aUsers);

		return true;
	}

	public function __action_save() {

		$this->_theme = 'empty.tpl';

		$aError = array();

		if (isset($_POST['user'])) {
			$aUserData = $_POST['user'];
		} else {
			$aError[] = self::getLanguageVar('users_no_data_found');
		}

		foreach ((array) $aUserData as $aUser) {
			if (empty($aUser['username'])) {
				$aError[] = self::getLanguageVar('users_no_username');
			}
			if (empty($aUser['email'])) {
				$aError[] = self::getLanguageVar('users_no_email');
			}
		}
		$iNewUserId = 0;
		if (empty($aError)) {
			try {
				foreach ((array) $aUserData as $iUserId => $aUser) {
					$oUser = new User((int) $iUserId);
					$oUser->update($aUser);
					if ($iUserId <= 0) {
						$iNewUserId = $oUser->getUserID();
					}
				}
			} catch (Exception $e) {
				$aError[] = self::getLanguageVar('users_error');
				$aError[] = $e->getMessage();
			}
		}

		if (!empty($_POST['userfield'])) {
			try {
				foreach ((array) $_POST['userfield'] as $iUserId => $aData) {
					if ($iUserId <= 0) {
						$iUserId = $iNewUserId;
					}
					$oUser = new User($iUserId);
					foreach ((array) $aData as $iFieldId => $mValue) {
						if ($iFieldId <= 0) {
							continue;
						}
						$oUser->validateFieldData($iFieldId, $mValue);
					}
				}
			} catch (Exception $e) {
				$aError[] = self::getLanguageVar('users_error');
				$aError[] = $e->getMessage();
			}
			foreach ((array) $_POST['userfield'] as $iUserId => $aData) {
				if ($iUserId <= 0) {
					$iUserId = $iNewUserId;
				}
				$oUser = new User($iUserId);
				foreach ((array) $aData as $iFieldId => $mValue) {
					if ($iFieldId <= 0) {
						continue;
					}
					$oUser->saveUserFieldData($iFieldId, $mValue);
				}
			}
		}

		$oUser->deleteAllGroups();

		if (!empty($_POST['group'])) {
			foreach ((array) $_POST['group'] as $iGroup) {
				$oGroup = new UserGroup($iGroup);
				$oGroup->addUser($oUser);
			}
		}

		$aTransfer = array();
		$aTransfer['errors'] = $aError;
		if (empty($aError)) {
			$aTransfer['message'] = self::getLanguageVar('users_success');
			if (key($aUserData) == 0) {
				$aTransfer['task'] = 'resetFields';
			}
		}

		echo json_encode($aTransfer);

		return true;
	}

	public function __action_editUserFields() {

	}

	public static function registerHooks() {
		return true;
	}

}