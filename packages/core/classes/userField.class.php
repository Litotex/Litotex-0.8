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

class userField extends Basis_Entry {
	protected $_pluginHandler = NULL;
	protected $_sTableName = 'lttx_userfields';
	static protected $_sClassName = 'userField';
	
	public function  __construct($iFieldId = 0) {
		parent::__construct($iFieldId);
		$this->_pluginHandler = new plugin_handler($name = 'userFields', $location = 'userFields', $cacheLocation = 'userFields.plugin.cache.php', $currentFile = __FILE__);
	}
	
	public static function getList(){

		$sSql = " SELECT `ID`, `key`, IF (extra='','empty' ,extra) as extra ,`optional`, `display`, `editable`, IF (package='','empty' ,package) as package, `position` FROM `lttx".package::$pdbn."_userfields` ORDER BY `position` ASC";
		$aSql = array();
		
		$aResult = package::$pdb->prepare($sSql);
		$aResult->execute($aSql);
		$aResult = $aResult->fetch(PDO::FETCH_ASSOC);

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

	public function getTypeName($type = false){
                if($type === false)
                    $type = $this->type;
		return $this->_pluginHandler->getLangVar($type, 'typeName');
	}

        public function  validate() {
            if(!$this->_pluginHandler->pluginExists($this->type)){
                throw new lttxError ('userField_noPlugin', $this->type);
                return false;
            }
            return parent::validate();
        }

        public function getTypes(){
            $list = $this->_pluginHandler->getPluginList();
            $return = array();
            foreach($list as $type){
                $return[] = array($type, $this->getTypeName($type));
            }
            return $return;
        }

        public function  validateContent($value) {
            return $this->_pluginHandler->callPluginFunc($this->type, 'validateContent', array($value));
            return parent::validateContent($value);
        }
    }
