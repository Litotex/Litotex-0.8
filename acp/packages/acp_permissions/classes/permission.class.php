<?php
class permission {
	protected static $_aAvailablePermissons 	= array();
	protected $_iAssociateType 			= 0;
	protected $_iAssociateID 			= 0;
	protected $_aInstance = array();
	public function __construct($iAssociateType, $iAssociateID){
	
		$this->_loadAvailablePermissions();
		
		$this->_iAssociateType	= $iAssociateType;
		$this->_iAssociateID 	= $iAssociateID;
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
	public function getPermissionData($iPermissionId){
		foreach((array)self::$_aAvailablePermissons as $aPermission){
			if($aPermission['ID'] == $iPermissionId){
				return $aPermission;
			}
		}
	}
	/**
	* Return thecurrent AssociateType/AssociateID Permissionlevel
	**/	
	public function getPermissionLevel($iPermissionId){
		$aPermission = $this->getPermissionData($iPermissionId);
		$sSql = "
				SELECT 
					`permissionLevel`
				FROM 
					`lttx_permissions`
				WHERE 
					`associateType` = ? AND 
					`associateID` = ? AND 
					`package` = ? AND 
					`function` = ? AND 
					`class` = ?
			";
		$aSql = array($this->_iAssociateType, $this->_iAssociateID, $aPermission['package'], $aPermission['function'], $aPermission['class']);
		$iPermissionLevel = (int)package::$db->GetOne($sSql,$aSql);
		if($iPermissionLevel == 1){
			return 1;
		} else if($iPermissionLevel == -1){
			return -1;
		}
		return 0;
	}
	/**
	* Delete All Permission of the current AssociateType and AssociateID
	**/
	public function deleteAllPerissions(){
		$sSql = " DELETE FROM 
						`lttx_permissions` 
					WHERE
						`associateType` = ? AND 
						`associateID` = ? ";
						
		$aSql = array($this->_iAssociateType, $this->_iAssociateID);	
		package::$db->Execute($sSql,$aSql);			
	}
	public function insertPermission($iPermissionID, $iValue){
		$aPermission = $this->getPermissionData($iPermissionID);
		$sSql = " INSERT INTO 
						`lttx_permissions` 
					SET
						`permissionLevel` = ?,
						`associateType` = ?,
						`associateID` = ?,
						`package` = ?,
						`function` = ?,
						`class` = ?
						";
						
		$aSql = array($iValue, $this->_iAssociateType, $this->_iAssociateID, $aPermission['package'], $aPermission['function'], $aPermission['class']);	
		package::$db->Execute($sSql,$aSql);	
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
						`lttx_permissionsAvailable`
					";
					
			$aSql = array();
			$aResult = package::$db->GetAssoc($sSql, $aSql);
			self::$_aAvailablePermissons = $aResult;
		}
	}
}