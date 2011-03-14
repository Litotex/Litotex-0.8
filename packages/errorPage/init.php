<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class package_errorPage extends package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'errorPage';
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
            self::$packages->registerHook(__CLASS__, 'AdoDBResult', 1, 'AdoDBResult', __FILE__, 'errorPage');
            return true;
        }

        public static function __hook_AdoDBResult(ADOConnection $result){
            $msg = $result->ErrorMsg();
            if($msg && !in_array($msg, self::$_sqlErrors)){
                    self::$db->Execute ("INSERT INTO `lttx_log` (`userid`, `message`, `log_type`) VALUES (?, ?, ?)", array((self::$user)?self::$user->getUserID ():0, $msg, 1));
                    self::$_sqlErrors[] = $msg;
            }
        }
}
