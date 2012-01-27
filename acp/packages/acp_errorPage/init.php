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
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class package_acp_errorPage extends Package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'acp_errorPage';
    /**
     * No dependencies because they are loaded automatic by hook's
     * @var array
     */
    public static $dependency = array();
    protected $_availableActions = array('404');
	public function __action_404(){
		$this->_tpl = true;
		$this->_theme = '404.tpl';
		header('HTTP/ 404');
		$this->displayTpl();
		exit();
		return true;
	}
	public function __action_main(){
		return true;
	}

        private static $_sqlErrors = array();

        public static function  registerHooks() {
            self::$packages->registerHook(__CLASS__, 'AdoDBResult', 2, 'AdoDBResult', __FILE__, 'acp_errorPage');
            return true;
        }

        public static function __hook_AdoDBResult(ADOConnection $result, $sql){
            $msg = $result->ErrorMsg();
            if($msg && !in_array($msg, self::$_sqlErrors)){
                    self::$pdb->prepare("INSERT INTO `lttx1_log` (`userid`, `message`, `log_type`) VALUES (?, ?, ?)")->execute(array((self::$user)?self::$user->getUserID ():0, $msg . "\nquery was:\n" . $sql, 1));
                    self::$_sqlErrors[] = $msg;
            }
        }
}
