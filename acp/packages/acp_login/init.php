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
    protected $_availableActions = array('main','loginSubmit');
    /**
     * Register all hooks of this package
     * @return bool
     */
    public function __action_main() {
        return true;
    }
    public function __action_loginSubmit(){
    	if(!(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] && $_POST['password']))
    		throw new lttxInfo('acp_login_UsernamePasswordMissing');
    	$controllUser = user::login($_POST['username'], $_POST['password']);
    	if(!$controllUser || !user::compare(package::$user, $controllUser)){
    		throw new lttxInfo('acp_login_UsernamePasswordWrong');
    	}
    	package::$user->setAcpLogin();
    	header('Location:index.php');
    	exit();
    	return true;
    }
}