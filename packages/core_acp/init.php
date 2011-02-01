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
    protected $_availableActions = array('main', 'tplModSort');

    /**
     * Only loads the building class
     * @see packages/core/classes/package::__action_main()
     * @return bool
     */
	public function __action_main(){
		return true;
	}

	public function __action_tplModSort(){
		self::addJsFile('tplmod.js', 'core_acp');
		self::addCssFile('tplmod.css', 'core_acp');
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'displayTplModification', 1);
		return true;
	}

	/**
     * Hook function
    */
	public static function __hook_displayTplModification($sHtml) {

		$sHtml = '<div class="tplmods_dropable">'.$sHtml.'</div>';
		
        return $sHtml;
    }

}