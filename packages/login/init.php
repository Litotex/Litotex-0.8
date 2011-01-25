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
    
    protected $_theme = 'main.tpl';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','loginsubmit','logout','forget','forget_submit');

    public static $dependency = array('mail');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'showLoginBox', 0);
 		return true;
    }
    public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showLoginBox', 'login');
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
		package::$tpl->display(self::getTplDir('login') . 'login_forget.tpl');
	}
	
	public function __action_forget_submit() {
		$email= mysql_real_escape_string($_POST['email']);		

		$pos = strpos ($email, "@");
		if ($pos < 1 ) { 
			throw new lttxError('LN_LOGIN_FORGET_NOTE3');
			exit();
		}
		
		if(package::$user){
				header("Location: index.php"); 
				exit();
			}
		
		$result = package::$db->Execute("
            SELECT *
            FROM `lttx_users`
            WHERE `email` = ?",
                $email);
		
		
		if(!$result || !$result->RecordCount() ){
				throw new lttxError('LN_LOGIN_FORGET_ERROR_1');
				return true;
		}
		
		if ($result->RecordCount() > 1 ){
			throw new lttxError('LN_LOGIN_FORGET_ERROR');
			return true;
		}
		
		$forgetUsername =$result->fields['username'];
		$forgetUserID =$result->fields['ID'];
		
		$password="";
		$pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$pool .= "abcdefghijklmnopqrstuvwxyz";
		$pool .= "1234567890";

		srand ((double)microtime()*1000000);
		for ($i = 0; $i < intval(10); $i++) {
			$password .= $pool{rand(0, strlen($pool)-1)};
		}
		
		$MailSubject=package::$tpl->get_config_vars('LN_LOGIN_MAIL_SUBJECT');
		$MailMessage=package::$tpl->get_config_vars('LN_LOGIN_MAIL_MESSAGE');
		
		$MailMessage = str_replace("%%Username%%", $forgetUsername, $MailMessage);
		$MailMessage = str_replace("%%Password%%", $password, $MailMessage);

		$forget_password_user=new user($forgetUserID);
		$forget_password_user->setPassword($password);
		$forget_password_user->logout();
		
		
		$ret=package_mail::sendMailPlain($email,$MailSubject,$MailMessage);
		
		if ($ret){
			throw new lttxInfo('LN_LOGIN_FORGET_OK');
			return true;
		}
		
		return true;
			
	}
	
    public function __action_loginsubmit() {
		if (!isset($_POST['username'])){
			throw new lttxError('login_noUsername');
		}
		if(!isset($_POST['password'])){
			throw new lttxError('login_noUsername');
		}
		$user = user::login($_POST['username'],$_POST['password']);
		if(!$user){
			$sError = user::$sLastLoginError;
			throw new lttxError($sError);
		}
		header("Location:index.php");
		exit();
    }
	
}
