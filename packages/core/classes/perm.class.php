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
class perm {
    /**
     * User cache
     * @var user
     */
    private $_user = false;
    /**
     * This will hold all userGroups as a cache
     * @var array
     */
    private $_groups = false;
    /**
     * This is a cache for permissions
     * FIXME: Not implemented YET!
     * @var array
     */
    private static $_cache = array();
    /**
     * true if user flag serveradmin is set in database, just ignore all limitations
     * @var bool
     */
    private $_serverAdmin = false;
    /**
     * This will set up permission handlich for a user
     * @param user $user
     * @return void
     */
    public function  __construct($user) {
        if(!is_a($user, 'user')) {
            throw new Exception('User class has to be passed');
            return;
        }
        $this->_serverAdmin = (bool)$user->getData('serverAdmin');
        $this->_user = $user;
        $this->_groups = $user->getUserGroups();
        if(!$this->_groups)
            $this->_groups = array();
        return;
    }
    /**
     * EMPTY
     * @return void
     */
    public function  __destruct() {
        return;
    }
    /**
     * Returns the username whis object is binded to
     * @return str
     */
    public function  __toString() {
        return $this->_user->getUserName();
    }
    /**
     * This will return true, if there are enough permissions for this user
     * @param package (extended) $package name of the package this function belongs to
     * @param str $function name of the function to be checked
     * @param str $class Name of class which is the "owner" of the static function $function, false will check the function on package object
     * @return bool
     */
    public function  checkPerm($package, $function, $class = false) {
    	if($this->_serverAdmin) return true;
        return ($this->_getPerm($package, $function, $class) == 1)?true:false;
    }
    /**
     * This will check for permissions and automaticly cast the function if there are permissions
     * (to avoid mixed up return values it is recommended to check with checkPerm and cast the function in the usual way)
     * Use this, if you only want to cast a short function, for example to manipulate a parameter.
     * Don't use it, if you have to check the return value in deepth.
     * @param package (extended) $package package to check for
     * @param str $function function name to be checked (and casted)
     * @param array $vars parameters
     * @param str $class if this is set, the function will be casted as a static method of this class
     * @return mixed
     */
    public function  castFuntion($package, $function, $vars = false, $class = false) {
    	if(!$this->_serverAdmin){
	        if(get_parent_class($package) != 'package')
	            return false;
	        if(!$this->checkPerm($package, $function, $class))
	            return false;
	        if($class !== false) {
	            $vars = ($vars === false)?array():$vars;
	            return call_user_func($class . '::' . $function, $vars);
	        }
    	}
        return call_user_func(array($package, $function), $vars);
    }
    /**
     * This will return the "real" permission which includes aloowed, denied, never allowed
     * @param package (extended) $package Package to check the permissions from
     * @param str $function Function to check for
     * @param str $class If set the function to be checked is a static method of this class
     * @return int
     */
    private function  _getPerm($package, $function, $class = false) {
        $perm = $this->_getUserPerm($package, $function, $class);
        foreach($this->_groups as $group) {
            $perm = $this->_mergePerm($perm, $this->_getGroupPerm($package, $function, $group, $class));
            if($perm == -1)
                return -1;
        }
        return $perm;
    }
    /**
     * This will merge two permissions
     * @param int $perm1
     * @param int $perm2
     * @return int
     */
    private function _mergePerm($perm1, $perm2) {
        if($perm1 == -1 || $perm2 == -1)
            return -1;
        if($perm1 == 0 && $perm2 == 0)
            return 0;
        if($perm1 == 1 || $perm2 == 1)
            return 1;
        return false;
    }
    /**
     * This will get permissions of a group
     * @param package (extended) $package The package to check for
     * @param str $function The function to be checked
     * @param userGroup $group The group permissions should be fetched from
     * @param str $class If this is set function to be checked is a static method of this class
     * @return int
     */
    private function _getGroupPerm($package, $function, $group, $class = false) {
        if(!is_a($group, 'userGroup'))
            return false;
        return $this->_getPermissionDummy($package, $function, 2, $group->getID(), $class);
    }
    /**
     * This will get permissions of a user
     * @param package (extended) $package The package to check for
     * @param str $function The function to be checked
     * @param str $class If this is set function to be checked is a static method of this class
     * @return int
     */
    private function _getUserPerm($package, $function, $class = false) {
        return $this->_getPermissionDummy($package, $function, 1, $this->_user->getUserID(), $class);
    }
    /**
     * Dummy class (gets permissions from groups and users)
     * @param package (extended) $package The package to check for
     * @param str $function The function to be checked
     * @param int $type 1 => user, 2 => group
     * @param int $ID ID of group or user
     * @param str $class If this is set function to be checked is a static method of this class
     * @return int
     */
    private function _getPermissionDummy($package, $function, $type, $ID, $class = false) {
        if(get_parent_class($package) != 'package')
            return false;
        $packageName = $package->getPackageName();
        if($class === false)
            $class = '';
        $result = package::$db->Execute("
            SELECT `permissionLevel`
            FROM `lttx_permissions`
            WHERE `associateType` = ?
            AND `associateID` = ?
            AND `package` = ?
            AND `function` = ?
            AND `class` = ?",
                array($type, $ID, $packageName, $function, $class)
        );
        if($result->NumRows() <= 0)
            return 0;
        if($result->NumRows() > 1)
            throw new Exception('Multiple permission settings on one group... fix the database!');
        return $result->fields[0];
    }
    /**
     * This sets new group permissions
     * @param userGroup $group Group to set a new value for
     * @param package (extended) $package The package to set for
     * @param str $function The function to be setted
     * @param int $level new permission level
     * @param str $class If this is set function to be set is a static method of this class $class
     * @return bool
     */
    public static function setGroupPermission($group, $package, $function, $level, $class = false) {
        return self::_setPermissionDummy(2, $package, $function, $group->getID(), $level, $class);
    }
    /**
     * This will set new user permissions
     * @param user $user User to set permissions for
     * @param package (extended) $package The package to set for
     * @param str $function The function to be set
     * @param int $level new permission level
     * @param str $class If this is set function to be setted is a static method of this class $class
     * @return bool
     */
    public static function setUserPermission($user, $package, $function, $level, $class = false) {
        return self::_setPermissionDummy(1, $package, $function, $user->getUserID(), $level, $class);
    }
    /**
     * General function to set permissions (users and groups)
     * @param int $type 1 => user, 2 => group
     * @param package (extended) $package The package to set for
     * @param str $function The function to be set
     * @param int $ID ID of user/group to set
     * @param int $level New permission level (-1, 0, 1)
     * @param str $class If this is set function to be setted is a static method of this class $class
     * @return bool
     */
    private static function _setPermissionDummy($type, $package, $function, $ID, $level, $class = false) {
        //Check if permission is already set (If so, update old row)
        if(get_parent_class($package) != 'package')
            return false;
        $packageName = $package->getPackageName();
        if($class === false)
            $class = '';
        $result = package::$db->Execute("
            SELECT `ID`
            FROM `lttx_permissions`
            WHERE `associateType` = ?
            AND `associateID` = ?
            AND `package` = ?
            AND `function` = ?
            AND `class` = ?",
                array($type, $ID, $packageName, $function, $class)
        );
        if($result->NumRows() <= 0) {
            $result = package::$db->Execute("
                INSERT INTO `lttx_permissions`
                (`associateID`, `associateType`, `package`, `class`, `function`, `permissionLevel`)
                VALUES
                (?, ?, ?, ?, ?, ?)",
                    array($ID, $type, $packageName, $class, $function, $level));
        }
        if($result->NumRows() > 1)
            throw new Exception('Multiple permission settings on one group... fix the database!');
         $result = package::$db->Execute("
                UPDATE `lttx_permissions`
                SET `permissionLevel` = ?
                WHERE `ID` = ?",
                    array($level, $ID));
        if(!$result || package::$db->Affected_Rows() <= 0)
                    return false;
        return true;
    }
}