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

class package_acp_groups extends acpPackage {

	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'del');
	public static $dependency = array('acp_config');
	protected $_packageName = 'acp_groups';
	protected $_theme = 'main.tpl';

	public function __action_main() {

		self::addJsFile('groups.js', 'acp_groups');
		self::addCssFile('groups.css', 'acp_groups');
		self::addCssFile('permissions.css', 'acp_permissions');

		return true;
	}

	public function __action_new() {
		$this->__action_edit();
		return true;
	}

	public function __action_edit() {

		$this->_theme = 'edit.tpl';

		$iGroupId = 0;

		if (isset($_GET['id'])) {
			$iGroupId = (int) $_GET['id'];

			$oGroup = new UserGroup($iGroupId);

			self::$tpl->assign('oGroup', $oGroup);

			$aUsers = User::search('');
			self::$tpl->assign('aUsers', $aUsers);

			$aGroupUsers = $oGroup->getUsers();

			$sUserList = '';
			foreach ((array) $aGroupUsers as $oUser) {
				$sUserList .= $oUser->getUsername();
				$sUserList .= ', ';
			}

			self::$tpl->assign('sUserList', $sUserList);
		}

		return true;
	}

	public function __action_del() {

		$this->_theme = 'empty.tpl';

		$iGroupId = 0;

		if (isset($_POST['id'])) {
			$iGroupId = (int) $_POST['id'];
		} else if (isset($_GET['id'])) {
			$iGroupId = (int) $_GET['id'];
		}

		if ($iGroupId <= 0) {
			return false;
		}

		$oGroup = new UserGroup($iGroupId);
		$oGroup->delete();

		return true;
	}

	public function __action_list() {
		$this->_theme = 'list.tpl';
		$aGroups = UserGroup::getList();
		self::$tpl->assign('aGroups', $aGroups);
		return true;
	}

	public function __action_save() {
		$this->_theme = 'empty.tpl';


		echo json_encode($aTransfer);

		return true;
	}

	public static function registerHooks() {
		return true;
	}

}