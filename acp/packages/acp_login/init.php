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
			throw new LitotexInfo('acp_login_UsernamePasswordMissing');
		$controllUser = User::login($_POST['username'], $_POST['password']);
		
		if(!$controllUser || !User::compare(Package::$user, $controllUser))
			throw new LitotexInfo('acp_login_UsernamePasswordWrong');
		
		Package::$user->setAcpLogin();
		header('Location:index.php');
		exit();
		return true;
	}
}

