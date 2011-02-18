<?php

class userField extends Basis_Entry {
	protected $_pluginHandler = NULL;
	protected $_sTableName = 'lttx_userfields';
	static protected $_sClassName = 'userField';
	
	public function  __construct($iFieldId = 0) {
		parent::__construct($iFieldId);
		$this->_pluginHandler = new plugin_handler($name = 'userFields', $location = LITO_FRONTEND_ROOT . 'plugins/userFields/', $cacheLocation = LITO_FRONTEND_ROOT . 'packages/core/cache/userFields.plugin.cache.php', $currentFile = __FILE__);
		var_dump($this->_pluginHandler->callPluginFunc($this->type, 'checkValid', array('')));
	}
	
	public static function getList(){

		$sSql = " SELECT * FROM `lttx_userfields` ORDER BY `position` ASC";
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


}
