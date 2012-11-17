<?php
/*
 * Copyright (c) 2010 Litotex
 * 
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software and
 * associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
/**
 * It's a simple login module
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_login extends Package {
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
		Package::addCssFile('login.css', 'login');

		if(!Package::$user){
			Package::$tpl->display(self::getTplDir('login') . 'login_template.tpl');
		}else{
			Package::$tpl->assign('USERNAME',Package::$user->getUsername() );
			Package::$tpl->display(self::getTplDir('login') . 'loggedin_template.tpl');
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
			if(Package::$user){
				Package::$user->logout();
				header("Location: index.php"); 
				exit();
			}
	}
	public function __action_forget() {
		Package::$tpl->display(self::getTplDir('login') . 'login_forget.tpl');
	}
	
	public function __action_forget_submit() {
                $email= ($_POST['email']);		

		$pos = strpos ($email, "@");
		if ($pos < 1 ) { 
			throw new LitotexError('LN_LOGIN_FORGET_NOTE3');
			exit();
		}
		
		if(Package::$user){
				header("Location: index.php"); 
				exit();
			}
		
		$result = Package::$pdb->prepare("
            SELECT *
            FROM `lttx1_users`
            WHERE `email` = ?");
		$result->execute($email);
		
		
		if($result->rowCount() < 1){
				throw new LitotexError('LN_LOGIN_FORGET_ERROR_1');
				return true;
		}
		
		if ($result->rowCount() > 1 ){
			throw new LitotexError('LN_LOGIN_FORGET_ERROR');
			return true;
		}
		
		$result = $result->fetch();
		$forgetUsername =$result['username'];
		$forgetUserID =$result['ID'];
		
		$password="";
		$pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$pool .= "abcdefghijklmnopqrstuvwxyz";
		$pool .= "1234567890";

		srand ((double)microtime()*1000000);
		for ($i = 0; $i < intval(10); $i++) {
			$password .= $pool{rand(0, strlen($pool)-1)};
		}
		
		$MailSubject=Package::$tpl->get_config_vars('LN_LOGIN_MAIL_SUBJECT');
		$MailMessage=Package::$tpl->get_config_vars('LN_LOGIN_MAIL_MESSAGE');
		
		$MailMessage = str_replace("%%Username%%", $forgetUsername, $MailMessage);
		$MailMessage = str_replace("%%Password%%", $password, $MailMessage);

		$forget_password_user=new User($forgetUserID);
		$forget_password_user->setPassword($password);
		$forget_password_user->logout();
		
		
		$ret=package_mail::sendMailPlain($email,$MailSubject,$MailMessage);
		
		if ($ret){
			throw new LitotexInfo('LN_LOGIN_FORGET_OK');
			return true;
		}
		
		return true;
			
	}
	
    public function __action_loginsubmit() {
		if (!isset($_POST['username'])){
			throw new LitotexError('login_noUsername');
		}
		if(!isset($_POST['password'])){
			throw new LitotexError('login_noUsername');
		}
		$user = User::login($_POST['username'],$_POST['password']);
		if(!$user){
			$sError = User::$sLastLoginError;
			throw new LitotexInfo($sError);
		}
		Package::debug('user '.$_POST['username'].' logged in') ;
		header("Location:index.php");
		return true ;
                
    }
	
}
