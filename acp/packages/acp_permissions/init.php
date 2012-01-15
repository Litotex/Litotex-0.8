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
//include_once('classes/permission.class.php');

class package_acp_permissions extends acpPackage{

	protected $_availableActions = array('main', 'save');

	public static $dependency = array('acp_config');

	protected $_packageName = 'acp_permissions';
	protected $_theme = 'main.tpl';

	public function __action_main(){

		self::addCssFile('permissions.css', 'acp_permissions');

		//Variable over Get or Post(formular)
		$_POST = array_merge($_POST, $_GET);
		if(!isset($_POST['associateType']) || !isset($_POST['associateID']))
                    throw new LitotexError('permissions_no_user_or_group');

		$iAssociateType = (int)$_POST['associateType'];
		$iAssociateID 	= (int)$_POST['associateID'];

		if(
			!empty($iAssociateType) &&
			!empty($iAssociateID)
		){
			try  {

				if($iAssociateType == 1){
					$oAssociate = new User($iAssociateID);
				} else {
					$oAssociate = new UserGroup($iAssociateID);
				}

			} catch (Exception $e) {
				throw new LitotexError('permissions_no_user_or_group');
			}



			self::$tpl->assign('iAssociateType', $iAssociateType);
			self::$tpl->assign('iAssociateID', $iAssociateID);

			$oPermission = new Permission($iAssociateType, $iAssociateID);

			$aPermissions = $oPermission->getAvailablePermissions();

			$aPermissionArray = array();

			foreach((array)$aPermissions as $aPermission){
				$aPermissionArray[$aPermission['package']][] = $aPermission;
			}

			self::$tpl->assign('oPermission', $oPermission);
			self::$tpl->assign('aPermissionArray', $aPermissionArray);

		}



		return true;
	}

	public function __action_save(){

		$this->_theme = 'empty.tpl';

		$aSavePermissionsData = $_POST['permissions'];

		$iAssociateType = (int)$_POST['associateType'];
		$iAssociateID 	= (int)$_POST['associateID'];

		$oPermission 	= new Permission($iAssociateType, $iAssociateID);

		//$oPermission->deleteAllPerissions();

		foreach($aSavePermissionsData as $iPermissionID => $iValue){
			$oPermission->insertAvailablePermission($iPermissionID, $iValue);
		}

		
		return true;
	}

	public static function registerHooks(){
		return true;
	}
}
