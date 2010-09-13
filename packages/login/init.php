<?php
/**
 * It's a simple login module
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_login extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'login';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','loginsubmit','logout','forget');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'showLoginBox', 0);
 		return true;
    }
    public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showLoginBox');
    	return true;
    }
    /**
     *Hook function for LoginBox
    */
	public static function __hook_showLoginBox() {
		package::addCssFile('login.css', 'login');
		if(!package::$user){
			package::$tpl->display(self::getTplDir('login') . 'login_template.tpl');
		}else{
			package::$tpl->assign('USERNAME',package::$user->getUsername() );
			package::$tpl->display(self::getTplDir('login') . 'loggedin_template.tpl');
		}
        return true;
    } 
    /**
     *Main function for LoginBox
    */
    public function __action_main() {
        return true;
    }
	public static function  __tpl_showLoginBox() {
        return self::__hook_showLoginBox(0);
    }
    /**
     *Logout an destrooy Session
    */
	public function __action_logout() {
			if(package::$user){
				package::$user->logout();
				header("Location: index.php"); 
				exit();
			}
	}
	public function __action_forget() {
		throw new lttxFatalError('LN_LOGIN_FORGET'); 
	}
	
    public function __action_loginsubmit() {
		$username="";
		$password="";
		
		if (!isset($_POST['username']) && !isset($_POST['password'])){
			return true;
		}

		$username= mysql_real_escape_string(strtolower($_POST['username']));
		$password= mysql_real_escape_string($_POST['password']);
	
		$user = new user(0);
		$ret=$user->login($username,$password);
		if(package::$user){
			header("Location: index.php");
			exit();
		}
		throw new lttxError('LN_LOGIN_NO_USERNAME'); 
		return true;
    }
	
}
