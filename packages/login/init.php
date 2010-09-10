<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
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
    protected $_availableActions = array('main','loginsubmit','logout');

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
     * Main action displays a table in content area
     */
	public static function __hook_showLoginBox() {
	    package::addCssFile('login.css');
        $tpl = new Smarty();
        $tpl->compile_dir = TEMPLATE_COMPILATION;
		$tpl->assign('CSS_LOGIN_FILE', self::getTplURL('login').'css/login.css');
		
		
		if(!package::$user){
			$tpl->display(self::getTplDir('login') . 'login_template.tpl');
		}else{
			$tpl->assign('USERNAME',package::$user->getUsername() );
			$tpl->display(self::getTplDir('login') . 'loggedin_template.tpl');
		}
        return true;
    } 
	 
    public function __action_main() {
        return true;
    }
	public static function  __tpl_showLoginBox() {
        return self::__hook_showLoginBox(0);
    }
	
	public function __action_logout() {
			if(package::$user){
				package::$user->logout();
				header("Location: index.php"); 
				exit();
			}
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
		package::$tpl->assign('LOGIN_ERROR', 'Du kommst hier net rein');
		$this->_theme = 'login_error.tpl';
		return true;
    }
	
}
