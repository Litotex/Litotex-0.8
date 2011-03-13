<?php

class userField extends Basis_Entry {
	protected $_pluginHandler = NULL;
	protected $_sTableName = 'lttx_userfields';
	static protected $_sClassName = 'userField';
	
	public function  __construct($iFieldId = 0) {
		parent::__construct($iFieldId);
		$this->_pluginHandler = new plugin_handler($name = 'userFields', $location = 'userFields', $cacheLocation = 'userFields.plugin.cache.php', $currentFile = __FILE__);
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

        public function getHTML($user){
            return $this->_pluginHandler->callPluginFunc($this->type, 'getHTML', array($this, $user));
        }

	public function getTypeName(){
		return $this->_pluginHandler->getLangVar($this->type, 'typeName');
	}

        public function  validate() {
            if(!$this->_pluginHandler->pluginExists($this->type)){
                throw new lttxError ('userField_noPlugin', $this->type);
                return false;
            }
            return parent::validate();
        }
    }
