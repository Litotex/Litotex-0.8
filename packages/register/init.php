<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
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
    protected $_availableActions = array('main','register_new','register_submit');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'showRegisterLink', 1);
 		return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showRegisterLink');
    	return true;
    }
    /**
     * Main action displays a table in content area
     */
	public static function __hook_showRegisterLink() {
	   
        $tpl = new Smarty();
        $tpl->compile_dir = TEMPLATE_COMPILATION;
		self::loadLang($tpl, 'table');
		$tpl->display(self::getTplDir('register') . 'register.tpl');
        return true;
    } 
	 
    public function __action_main() {
        return true;
    }
	public static function  __tpl_showRegisterLink() {
        return self::__hook_showRegisterLink(0);
    }
    
	public function __action_register_new() {
		//username
	
       $this->_theme = 'register_new.tpl';
	
        return true;
    }
	public function __action_register_submit(){
	
		$useregeln=0;
		$username= mysql_real_escape_string(strtolower($_POST['username']));
		$email= mysql_real_escape_string($_POST['email']);
		$password= mysql_real_escape_string($_POST['password']);
		if (isset($_POST['regeln'])) $useregeln = $_POST['regeln'];
		
		
		if(!$username || !$email || !$password) {
			echo("username kennwort fehler");
			exit();
		}

		if (!$useregeln){
			echo("Regeln nicht zugestimmz");
			exit()	;
		}

		if (!preg_match ("/^[0-9a-z_-]{3,15}$/i", $username)) {
			echo("Ungültiger username");
			exit();
		}

		$pos = strpos ($email, "@");
		if ($pos < 1 ) { // Achtung: 3 Gleichheits-Zeichen
			echo("ungültige mail");
			exit();
		}
		
		if(!package::$user){
	
		$user = new user(0);
		
		$array_data['race']='0';
		$array_data['userGroup']='0';
		$ret=$user->register($username,$password,$email,$array_data);
		
		$return_msg='';
		
		if ($ret==-1){
			$return_msg= 'ret -1';
		}elseif($ret==-2){
			$return_msg= 'ret -2';
		}elseif($ret==-3){
			$return_msg=  'ret -3';
		}else{
			$return_msg=  'ret OK';
		}
		
		package::$tpl->assign('REGISTER_STATUS', $return_msg);
		$this->_theme = 'register_ok.tpl';
		        return true;
		
		}
			
	}	
	
}
