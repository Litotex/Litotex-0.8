<?php

class package_acp_options extends acpPackage {

    protected $_availableActions = array('main', 'new', 'edit', 'list', 'save','del' );
    public static $dependency = array('acp_config');
    protected $_packageName = 'acp_options';
    protected $_theme = 'main.tpl';

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

        $oUser = new user($iUserId);
        $oUser->delete();

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

		if (isset($_GET['id'])) {
            $iOptionID = (int) $_GET['id'];
        }
		echo($iOptionID );
	
        $this->_theme = 'main.tpl';

        $aError = array();

        if (isset($_POST['package'])) {
            $aPackageData = $_POST['package'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_PAKET');
        }

        if (isset($_POST['Okey'])) {
            $aPackageData = $_POST['Okey'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_KEY');			
        }
		
        if (isset($_POST['Ovalue'])) {
            $aPackageData = $_POST['Ovalue'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_VALUE');					
        }

        if (isset($_POST['Odefault'])) {
            $aPackageData = $_POST['Odefault'];
        } else {
			throw new lttxError('LN_OPTION_ERROR_DEFASULTVALUE');							
        }

       

        return true;
    }


}