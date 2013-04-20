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
 * It's a simple login module with
 * username
 * Mailadress
 * second Mailadress
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_register extends Package {
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
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showRegisterLink', 'register');
    	return true;
    }
	 
    public function __action_main() {
		Package::addCssFile('register.css', 'register');
		$fields = array();
		$additionalFields = UserField::getList();
		return true;
    }
	public static function  __tpl_showRegisterLink() {
        Package::$tpl->display(self::getTplDir('register') . 'register.tpl');
        return true;
    }
	public function __action_register_submit(){
		if(!isset($_POST['confirm_password']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['rules'])){
                    throw new LitotexError('E_notAllInformationPassed');
                }
                $result = User::register($_POST['username'], $_POST['password'], $_POST['email'], array());
                if(is_a($result, 'user')){
                    throw new LitotexInfo('registrationComplete');
                }
                if($result == -1){
                    throw new LitotexError('E_userNameAlreadyExists');
                }
                if($result == -2){
                    throw new LitotexError('E_emailAlreadyExists');
                }
                if($result == -3){
                    throw new LitotexFatalError('unexpected error while registration.');
                }
	}
}
