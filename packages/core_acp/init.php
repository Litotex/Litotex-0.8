<?php

class package_core_acp extends package {

	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'core_acp';

	/**
     * This var contains every possible methode this class provides, methodes are passed as a get param (?action=xxx)
     * If an unknown method was passed __action_main will be casted. The function to be casted (__action_name) must be availabe.
     * @var array
     */
    protected $_availableActions = array('main', 'tplModSort', 'tplModSave');

    /**
     * Only loads the building class
     * @see packages/core/classes/package::__action_main()
     * @return bool
     */
	public function __action_main(){
		return true;
	}

	public function __action_tplModSave(){



		foreach((array)$_POST['tpl'] as $sPos => $aTplMods ){
			$iPosition = 0;
			foreach((array)$aTplMods as $iTplModId ){
				$oTplMod = new tplModSort($iTplModId);
				$oTplMod->position = $sPos;
				$oTplMod->sort = $iPosition;
				$oTplMod->save();
				$iPosition++;
			}
		}

		$this->_theme = 'empty.tpl';
		
		return true;
	}

	public function __action_tplModSort(){

		$oUser = package::$user;
		$bAccess = $oUser->isAcpLogin();

		if( $bAccess === false ){
			throw new lttxError('tplmods_no_access');
		}

		self::addJsFile('jquery-1.4.2.min.js');
		self::addJsFile('jquery-ui-1.8.6.custom.min.js');
		self::addCssFile('jquery-ui-1.8.6.custom.css');
		
		self::addJsFile('tplmod.js', 'core_acp');
		self::addCssFile('tplmod.css', 'core_acp');

		$aElements = tplModSort::getList('none');
		package::$tpl->assign('aElements', $aElements);

		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'displayTplModification', 4);
		self::_registerHook(__CLASS__, 'generateTplModification', 2);
		return true;
	}

	/**
     * Hook function
    */
	public static function __hook_displayTplModification($sHtml, $sPosition, $sPackage, $aFunc) {
       
		if(!isset($_GET['package'])){
			$_GET['package'] = 'main';
		}

        if($_GET['package'] != 'core_acp'){
			return $sHtml;
		}

		if($sPosition == ""){
			$sPosition = 'inactive';
		}

		$iTplID = tplModSort::searchId($aFunc[0], $aFunc[1]);
		$sDescription = '<p>';
		$sDescription .= package::getLanguageVar('tplmods_class').': '.$aFunc[0].'<br/>';
		$sDescription .= package::getLanguageVar('tplmods_function').': '.$aFunc[1];
		$sDescription .= '</p>';
		$sHtml = '<div class="tplmods_draggable ui-widget-content" id="tpl['.$sPosition.']_'.$iTplID.'">'.$sDescription.'</div>';
		
        return $sHtml;
    }

	public static function __hook_generateTplModification($sHtml, $sPosition) {
        
		if(!isset($_GET['package'])){
			$_GET['package'] = 'main';
		}

        if($_GET['package'] != 'core_acp'){
			return $sHtml;
		}

		if($sPosition == ""){
			$sPosition = 'inactive';
		}
		$sHtml = '<div class="tplmods_dropable connectedDropable" id="tpl_mod_list_'.$sPosition.'">'.$sHtml.' </div>';

        return $sHtml;
    }

}