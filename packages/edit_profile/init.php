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
		
		$email= ($_POST['email']);
		$password=($_POST['password']);

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
