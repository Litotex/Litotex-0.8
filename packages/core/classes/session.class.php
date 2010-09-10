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
class session{
    public $user = false;
    private $_sessionKey = false;
    private $_initialized = false;
    private $_ttl = 86400;
    private $_startTime = 0;
    private $_lastIP = '';
    private static $_historyStringTable = array(
        'New Session created',
        'Session destroyed',
        'IP changed',
        'New user registred'
    );
    public function  __construct($userClass = false) {
        if($this->setUserObject($userClass))
                $this->addHistory(0);
        return;
    }
    public function  __destruct() {
        if($this->user)
                $this->user->flushCache();
        $_SESSION['lttx']['session'] = serialize($this);
        return;
    }
    public function  __toString() {
        return $this->_sessionKey;
    }
    public function sessionActive(){
        if(!$this->_initialized)
                return false;
        if(($this->_startTime + $this->_ttl) < time())
                return false;
        if(!$this->user->isUsersInstance())
                return false;
        return true;
    }
    public static function getIPAdress(){
        $IP = '';
        $realIP = $_SERVER['REMOTE_ADDR'];
        $XForward = '';
        if(function_exists('apache_request_headers')){
            $header = apache_request_headers();
            if(isset($header['X-Forwarded-For']))
                $XForward = $header['X-Forwarded-For'];
        }
        $IP = $realIP . ':' . $XForward;
        return $IP;
    }
    public function addHistory($msg){
        $result = package::$db->Execute("INSERT INTO `lttx_sessions` (`sessionID`, `userID`, `username`, `currentIP`, `message`) VALUES (?, ?, ?, ?, ?)", array(hash('sha512', $this->_sessionKey), ($this->user)?$this->user->getUserID():0, ($this->user)?$this->user->getUsername():0, self::getIPAdress(), self::$_historyStringTable[$msg]));
    }
    public function destroy(){
        $this->user = false;
        $this->_lastIP = '';
        $this->_sessionKey = '';
        $this->_initialized = false;
        $this->_startTime = 0;
        return true;
    }
    public function refresh(){
        if($this->_lastIP != self::getIPAdress()){
            $this->addHistory(2);
            $this->_lastIP = self::getIPAdress();
        }
        $this->_startTime = time();
        return true;
    }
    public function setUserObject($userClass){
        if(!is_a($userClass, 'user'))
                return false;
        if(!$userClass->isUsersInstance())
                return false;
        $this->user = $userClass;
        $this->_startTime = time();
        $this->_initialized = true;
        $this->_sessionKey = session_id();
        $this->_lastIP = self::getIPAdress();
        $this->addHistory(3);
        return true;
    }
}
?>
