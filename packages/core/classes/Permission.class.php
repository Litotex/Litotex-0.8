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

class Permission {

	protected $_iAssociateType 	= 0;
	protected $_iAssociateID 	= 0;

	protected static $_aAvailablePermissons = array();

	public function __construct($iAccociateType, $iAssociateID){

		$this->_iAssociateID = $iAssociateID;
		$this->_iAssociateType = $iAccociateType;

		// Caching all available permissions
		$this->_loadAvailablePermissions();

	}

	/**
	 * Return all Available Permissions
	 **/
	public function getAvailablePermissions(){
		return self::$_aAvailablePermissons;
	}

	/**
	 * Return all Permissionsinformation of ONE Available Permission
	 **/
	public function getAvailablePermissionData($iPermissionId){
		foreach((array)self::$_aAvailablePermissons as $aPermission){
			if($aPermission['ID'] == $iPermissionId){
				return $aPermission;
			}
		}
		return array();
	}

	/**
	 * 
	 * Return thecurrent AssociateType/AssociateID Permissionlevel
	 * @param $iPermissionId
	 */
	public function getPermissionLevel($iPermissionId){

		$iPermission = 0;

		$aPermission 	= $this->getAvailablePermissionData($iPermissionId);

		if(!empty($aPermission)){
			$iPermission 	= $this->_getPerm($aPermission['package'], $aPermission['function'], $aPermission['class']);
		}

		return $iPermission;
	}

	/**
	 * 
	 * Check the Permission, if access Allowed return true
	 * @param string or object $mPackage
	 * @param string $sFunction
	 * @param string $sClass
	 * @return bolean
	 */
	public function checkPerm($mPackage, $sFunction, $sClass = false) {	

		if(
			$this->_getPerm($mPackage, $sFunction, $sClass) == 1
		) {
			return true;
		}

		return false;
	}


	/**
	 * Delete All Permission of the current AssociateType and AssociateID
	 **/
	public function deleteAllPerissions(){
		$sSql = " DELETE FROM
						`lttx1_permissions` 
					WHERE
						`associateType` = ? AND 
						`associateID` = ? ";

		$aSql = array($this->_iAssociateType, $this->_iAssociateID);
		Package::$pdb->prepare($sSql)->execute($aSql);
	}

	/**
	 * 
	 * Insert the Permission level for an Available Permission Entry
	 * @param int $iPermissionID
	 * @param int $iValue
	 */
	public function insertAvailablePermission($iPermissionID, $iLevel){

		$bSuccess = false;

		$aPermission 	= $this->getAvailablePermissionData($iPermissionID);

		if(!empty($aPermission)){
			$bSuccess 		= $this->setPermission($iLevel, $aPermission['package'], $aPermission['function'], $aPermission['class']);
		}

		return $bSuccess;
	}

	/**
	 * 
	 * Set the Permission level
	 * @param int $iLevel
	 * @param string or object $mPackage
	 * @param string $sFunction
	 * @param string $sClass
	 */
	public function setPermission($iLevel, $mPackage, $sFunction, $sClass = false){

		$sPackage = $mPackage;

		if(is_object($mPackage)){
			if(get_parent_class($mPackage) != 'package'){
				return false;
			} else {
				$sPackage = $mPackage->getPackageName();
			}
		}

		$mPerm = $this->_getPerm($mPackage, $sFunction, $sClass);

		$sSqlSetPart = "
	   					`permissionLevel` = ?,
						`associateType` = ?,
						`associateID` = ?,
						`package` = ?,
						`function` = ?,
						`class` = ?
		";

		if($mPerm === false){

			$sSql = "
				INSERT INTO
					`lttx1_permissions`
				SET
			".$sSqlSetPart;
			
			$aSql = array(
						(int)$iLevel, 
						$this->_iAssociateType, 
						(int)$this->_iAssociateID, 
						$sPackage, 
						$sFunction, 
						$sClass
						
					);

		} else {

			$sSql = "
				UPDATE
					`lttx1_permissions`
				SET
					".$sSqlSetPart."   
				WHERE  
					`associateType` = ? AND 
					`associateID` = ? AND 
					`package` = ? AND 
					`function` = ? AND
					`class` = ?			
			";
			
		$aSql = array(
						(int)$iLevel, 
						$this->_iAssociateType, 
						(int)$this->_iAssociateID, 
						$sPackage, 
						$sFunction, 
						$sClass,
						$this->_iAssociateType, 
						(int)$this->_iAssociateID, 
						$sPackage, 
						$sFunction, 
						$sClass
					);	
		}

	   	Package::$pdb->prepare($sSql)->execute($aSql);

		return true;

	}

 	/**
	 * This will merge two permissions
	 * @param int $perm1
	 * @param int $perm2
	 * @return int
	 */
	protected function _mergePerm($perm1, $perm2) {
		if($perm1 == -1 || $perm2 == -1)
			return -1;
		if($perm1 == 0 && $perm2 == 0)
			return 0;
		if($perm1 == 1 || $perm2 == 1)
			return 1;
		return false;
	}

	/**
	 * Get the Permission level 
	 * @param name of package $mPackage
	 * @param function name $sFunction
	 * @param name of class $sClass
	 */
	protected function _getPerm($mPackage, $sFunction, $sClass = false){

		$sPackage = $mPackage;

		if(is_object($mPackage)){
			if(!in_array(get_parent_class($mPackage), array('acpPackage', 'Package'))){
				return false;
			} else {
				$sPackage = $mPackage->getPackageName();
			}
		}

		$sSql = "
				SELECT 
					`permissionLevel`
				FROM 
					`lttx1_permissions`
				WHERE 
					`associateType` = ? AND 
					`associateID` = ? AND 
					`package` = ? AND 
					`function` = ?
			";

		if(!empty($sClass)){
			 $sSql .= " AND 
					`class` = ? ";
		}

		$aSql = array($this->_iAssociateType, $this->_iAssociateID, $sPackage, $sFunction, $sClass);
		$mPermissionQuery = Package::$pdb->prepare($sSql);
		$mPermissionQuery->execute($aSql);
		$mPermissionQuery = $mPermissionQuery->fetch();
		if(!isset($mPermissionQuery[0]))
			return false;
		
		$mPermissionLevel = $mPermissionQuery[0];

		if($mPermissionLevel === NULL){
			return false;
		} else if((int)$mPermissionLevel == 1){
			return 1;
		} else if((int)$mPermissionLevel == -1){
			return -1;
		}

		return 0;
	}

	/**
	 * Caching all Available Permissions
	 **/
	protected function _loadAvailablePermissions(){
		//Static Variable for Caching
		$aPermissions = self::$_aAvailablePermissons;
		if(empty($aPermissions)){
			$sSql = "
					SELECT 
						*
					FROM 
						`lttx1_permissions_available`
					ORDER BY
						`package`, `function`
					";
				
			$aSelect = Package::$pdb->prepare($sSql);
			$aSelect->execute();
			$aResult = $aSelect->fetchAll();
			if(!empty($aResult)){
				self::$_aAvailablePermissons = $aResult;
			}
		}
	}

        public static function clearAvailableTable($packageDir, $type = 2){
        	$delete = Package::$pdb->prepare("DELETE FROM `lttx1_permissions_available` WHERE `type` = ? AND `packageDir` = ?");
            $delete->execute(array($type, $packageDir));
        }
        public static function registerAvailable($name, $class, $function, $packageDir, $type = 2){
        	$insert = Package::$pdb->prepare("INSERT INTO `lttx1_permissions_available` (`type`, `package`, `class`, `function`, `packageDir`) VALUES (?, ?, ?, ?, ?)");
        	$insert->execute(array($type, $name, $class, $function, $packageDir));
        }
}