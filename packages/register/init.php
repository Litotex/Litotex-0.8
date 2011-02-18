<?php
/**
 * It's a simple login module with
 * username
 * Mailadress
 * second Mailadress
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_register extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'register';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','register_submit');

    /**
     * Register all hooks of this package
     * @return bool
     */
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showRegisterLink', 'register');
    	return true;
    }
	 
    public function __action_main() {
		package::addCssFile('register.css', 'register');
		$fields = array();
		$additionalFields = userField::getList();
		return true;
    }
	public static function  __tpl_showRegisterLink() {
        package::$tpl->display(self::getTplDir('register') . 'register.tpl');
        return true;
    }
 	public function __action_register_submit(){
		
	}
}
