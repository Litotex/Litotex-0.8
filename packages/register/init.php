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
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'showRegisterLink', 1);
 		return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showRegisterLink');
    	return true;
    }
	public static function __hook_showRegisterLink() {
		package::$tpl->display(self::getTplDir('register') . 'register.tpl');
        return true;
    } 
	 
    public function __action_main() {
		package::addCssFile('register.css', 'register');
		return true;
    }
	public static function  __tpl_showRegisterLink() {
        return self::__hook_showRegisterLink(0);
    }
 
 public function __action_register_submit(){
		$rules=0;
		$username= mysql_real_escape_string(strtolower($_POST['username']));
		$email= mysql_real_escape_string($_POST['email']);
		$password= mysql_real_escape_string($_POST['password']);
		if (isset($_POST['rules'])) $rules = $_POST['rules'];
		
		if(!$username || !$email || !$password) {
			
			throw new lttxError('LN_REGISTER_ERROR_1'); 
			exit();
		}

		if (!$rules){
			throw new lttxError('LN_REGISTER_ERROR_2');
			exit()	;
		}

		if (!preg_match ("/^[0-9a-z_-]{3,15}$/i", $username)) {
			throw new lttxError('LN_REGISTER_ERROR_4');
			exit();
		}

		$pos = strpos ($email, "@");
		if ($pos < 1 ) { 
			throw new lttxError('LN_REGISTER_ERROR_3');
			exit();
		}
		
		if(!package::$user){
	
		$user = new user(0);
		
		$array_data['race']='0';
		$array_data['userGroup']='0';
		$date = new Date();
		$array_data['registerDate'] = $date->getDbTime();
		$ret=$user->register($username,$password,$email,$array_data);
		
		$return_msg='';
		if(is_a($ret, 'user')){
			$return_msg=  'ret OK';
		}else if ($ret==-1){
			throw new lttxError('LN_REGISTER_ERROR_5');
		}elseif($ret==-2){
			throw new lttxError('LN_REGISTER_ERROR_6');
		}elseif($ret==-3){
			throw new lttxError('LN_REGISTER_ERROR_7');
		}else{
			$return_msg=  'ret ?';
		}
		
			package::debug('new registration:'.$username);
			$this->_theme = 'register_ok.tpl';
			return true;
		
		}
			
	}	
}
