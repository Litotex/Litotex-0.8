<?php
/**
 * It's a simple login module
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_acp_login extends acpPackage {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'acp_login';
    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','loginsubmit');

	
	 public static $dependency = array('acp_mail');
    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
  		return true;
    }
    public static function registerTplModifications(){
    	return true;
    }
    public function __action_main() {    	
        return true;
    }
    public function __action_loginsubmit(){
    	package::$user->setAcpLogin();
    	return true;
    }
}