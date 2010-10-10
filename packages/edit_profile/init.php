<?php
/**
 * It's a simple edit Profile module with
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_edit_profile extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'edit_profile';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','profile_submit');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
  		return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showProfileLink', 'edit_profile');
    	return true;
   }
    public function __action_main() {
		if(!package::$user){
		header("Location: index.php");
		exit();
		}
		package::$tpl->assign('EMAIL', package::$user->getData('email'));
		package::addCssFile('edit_profile.css', 'edit_profile');
		return true;
    }
	public static function  __tpl_showProfileLink() {
        if(package::$user){
			package::$tpl->display(self::getTplDir('edit_profile') . 'edit_profile_link.tpl');
        }
		return true;
    }
 
	public function __action_profile_submit(){
		
		$email= mysql_real_escape_string($_POST['email']);
		$password= mysql_real_escape_string($_POST['password']);

		if(!package::$user){
			throw new lttxError('LN_EDIT_PROFILE_ERROR_2');
		}
		
		if ($email !=''){
			$pos = strpos ($email, "@");
			if ($pos < 1 ) { 
				throw new lttxError('LN_EDIT_PROFILE_ERROR_1');
				exit();
			}
		 
			package::$user->setData('email',$email);
		}	
		
		if ($password !=''){
			package::$user->setPassword($password);;
		}
		
		header("Location: index.php"); 
		return true;
			
	}	
}
