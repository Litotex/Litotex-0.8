<?php

class userField {

	protected $_aData = array();
	protected static $_sTable = 'lttx_userfields';
	protected static $_aInstance = array();

	public static function getInstance($iFieldId){

		$oInstance;

		if(isset (self::$_aInstance[$iFieldId])){
			$oInstance = self::$_aInstance[$iFieldId];
		}

		if(
			$iFieldId > 0 &&
			!empty($oInstance)
		){
			return $oInstance;
		} else {
			self::$_aInstance[$iFieldId] = new self($iFieldId);
			return self::$_aInstance[$iFieldId];
		}
		
	}

	public static function getList(){

		$sSql = " SELECT * FROM `".self::$_sTable."` ORDER BY `position` ASC";
		$aSql = array();
		
		$aResult = package::$db->GetAssoc($sSql, $aSql, true);

		$aBack = array();
		if(!empty($aResult)){
			foreach((array)$aResult as $aData){
				$aBack[] = new self($aData['ID']);
			}
		}

		return $aBack;
	}

	public function  __construct($iFieldId = 0) {
		$this->_aData['ID'] = (int)$iFieldId;
		$this->_loadData();
	}

	public function getTypeName(){
		switch ($this->type) {
			case 'input':
				return package::getLanguageVar('users_fieldtype_input');
			case 'checkbox':
				return package::getLanguageVar('users_fieldtype_checkbox');
			case 'textarea':
				return package::getLanguageVar('users_fieldtype_textarea');
			default:
				return package::getLanguageVar('users_fieldtype_unknown');
		}
	}

	public function  __set($sColumn, $mValue) {
		$this->_aData[$sColumn] = $mValue;
	}

	public function  __get($sColumn) {
		return $this->_aData[$sColumn];
	}

	public function validate(){
		return true;
	}

	public function save(){

		$mValidate = $this->validate();

		if($mValidate === true){

			$sSql = "";
			$aSql = array();

			if($this->ID > 0){
				$sSql = " UPDATE `".self::$_sTable."` SET ";
			} else {
				$sSql = " INSERT INTO `".self::$_sTable."` SET ";
			}

			foreach((array)$this->_aData as $sColumn => $mValue ){

				if($sColumn == 'ID'){
					continue;
				}

				$sSql .= " `".$sColumn."` = ? ,";
				$aSql[] = $mValue;
			}

			$sSql = rtrim($sSql, ',');

			if($this->ID > 0){
				$sSql .= " WHERE ID = ? ";
				$aSql[] = (int)$this->ID;
			}

			package::$db->Execute($sSql, $aSql);
	
			return true;

		} else {
			return $mValidate;
		}

	}

	public function delete(){
		$sSql = " DELETE FROM `".self::$_sTable."` WHERE `ID` = ?";
		$aSql = array($this->ID);

		package::$db->Execute($sSql, $aSql, true);
	}

	protected function _loadData(){
		
		if($this->_aData['ID'] > 0){

			$sSql = " SELECT * FROM `".self::$_sTable."` WHERE ID = ? ";
			$aSql = array($this->_aData['ID']);

			package::$db->SetFetchMode(ADODB_FETCH_ASSOC);
			$aResult = package::$db->GetRow($sSql, $aSql);
			package::$db->SetFetchMode(ADODB_FETCH_DEFAULT);
			
			if($aResult === false){
				throw new lttxDBError();
			} else {
				$this->_aData = $aResult;
			}
			
		}
		
	}


}