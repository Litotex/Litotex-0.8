<?php

class package_acp_options extends acpPackage {

    protected $_availableActions = array('main', 'edit', 'list', 'save' );
    public static $dependency = array('acp_config');
    protected $_packageName = 'acp_options';
    protected $_theme = 'main.tpl';
	private static $_options = false;

    public function __action_main() {

        self::addJsFile('options.js', 'acp_options');
        self::addCssFile('options.css', 'acp_options');

        return true;
    }



    public function __action_new() {
        $this->__action_edit();
        return true;
    }

    public function __action_edit() {

        $this->_theme = 'edit.tpl';

        $iOptionID = 0;

        if (isset($_GET['id'])) {
            $iOptionID = (int) $_GET['id'];
        }

		$result = package::$db->Execute("SELECT * FROM `lttx_options` WHERE `ID` = ?",$iOptionID);
		
		
		if(!$result || !$result->RecordCount() ){
				throw new lttxError('LN_DB_ERRROR_1');
				return true;
		}
		
				
		$Option_package =$result->fields['package'];
		$Option_key =$result->fields['key'];
		$Option_value =$result->fields['value'];
		$Option_default =$result->fields['default'];

		package::$tpl->assign('Option_package', $Option_package);
		package::$tpl->assign('Option_key', $Option_key);
		package::$tpl->assign('Option_value', $Option_value);
		package::$tpl->assign('Option_default', $Option_default);
		package::$tpl->assign('edit_id', $iOptionID);
        return true;
    }


    public function __action_list() {

        $this->_theme = 'list.tpl';
		$elements = array();
    	$searchResults =self::$db->Execute("SELECT * FROM `lttx1_options` order by package");
		if($searchResults == false){
			throw new lttxDBError();
		}
    	
		 while(!$searchResults->EOF) {
			$elements[] = $searchResults->fields;
			
            $searchResults->MoveNext();
        }
        self::$tpl->assign('aOptions', $elements);

        return true;
    }

    public function __action_save() {
   self::addJsFile('options.js', 'acp_options');
        self::addCssFile('options.css', 'acp_options');

		if (isset($_GET['id'])) {
            $iOptionID = (int) $_GET['id'];
        }
	
        $this->_theme = 'main.tpl';
		
		
		$result = package::$db->Execute("SELECT * FROM `lttx_options` WHERE `ID` = ?",$iOptionID);
		
		
		if(!$result || !$result->RecordCount() ){
				throw new lttxError('LN_DB_ERRROR_1');
				return true;
		}
		
		$Option_package =$result->fields['package'];
		$Option_key =$result->fields['key'];
		
        if (isset($_POST['Ovalue'])) {
            $aPackageValue = $_POST['Ovalue'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_VALUE');					
        }

        if (isset($_POST['Odefault'])) {
            $aPackageDefaultValue = $_POST['Odefault'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_DEFASULTVALUE');							
        }

		

		$option = new option($Option_package);
		$option->set($Option_key,$aPackageValue);


			
        return true;
    }


}