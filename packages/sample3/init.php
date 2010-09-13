<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample3 extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'sample3';

    /**
     * Default template
     * @var string
     */
    protected $_theme = 'table.tpl';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'sample3', 0);
        return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'sample3');
    	return true;
    }

	
    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
        return true;
    }
    public static function __hook_sample3() {
		package::$tpl->display(self::getTplDir('sample3') . 'table.tpl');
		return true;
    }

 	public static function  __tpl_sample3() {
        return self::__hook_sample3(2);
    }	
	
}
