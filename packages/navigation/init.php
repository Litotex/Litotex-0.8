<?php
/**
 * @package navigation

 */
class package_navigation extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'navigation';
    /**
     * Avaibilbe actions in this package
     * @var array
     */
    
    protected $_theme = 'main.tpl';
    
	protected $_availableActions = array('main');

	
    public function __action_main() {
		return true;
    }
	
    public static function registerTplModifications(){
	    
    self::_registerTplModification(__CLASS__, 'displayTopNavigation', 'navigation');
		return true;
    }
    public static function __tpl_displayTopNavigation(){
		echo('displayTopNavigation');
	}
}
?>