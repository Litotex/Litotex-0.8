<?php
class userGroup {
    /**
     * Set if __construct worked out without any problems
     * @var bool
     */
    private $_initialized = false;
    /**
     * ID of the group
     * @var int
     */
    private $_ID = false;
    /**
     * Name of the group
     * @var str
     */
    private $_name = false;
    /**
     * Brif group description
     * @var str
     */
    private $_description = false;
    /**
     * Number of users
     * @var int
     */
    private $_userNumber = false;
    /**
     * Checks wheather or not the selected group is the default group (used if user belongs to no other groups)
     * @var bool
     */
    private $_default = false;
    /**
     * Cache to save groups in
     * @var array
     */
    private static $_cache = array();
    /**
     * Loads group information
     * @param int $ID
     * @return void
     */
    public function  __construct($ID) {
        if($this->_get($ID))
                $this->_initialized = true;
        else
            throw new Exception('Group could not be fetched!');
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
     * Set a new name for the active group
     * @param str $newName
     * @return bool
     */
    public function setName($newName){
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("
            UPDATE `lttx_userGroups`
            SET `name` = ?
            WHERE `ID` = ?",
                array($newName, $this->_ID));
        if(package::$db->Affected_Rows() > 0)
                return true;
        return false;
    }
    /**
     * Get the name of the selected group
     * @return string
     */
    public function getName(){
        if(!$this->_initialized)
                return false;
        return $this->_name;
    }
    /**
     * Set a new description for the selected group
     * @param str $newDescription
     * @return bool
     */
    public function setDescription($newDescription){
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("
            UPDATE `lttx_userGroups`
            SET `description` = ?
            WHERE `ID` = ?",
                array($newDescription, $this->_ID));
        if(package::$db->Affected_Rows() > 0)
                return true;
        return false;
    }
    /**
     * Returns the description which is set to the selected group
     * @return str
     */
    public function getDescription(){
        if(!$this->_initialized)
                return false;
        return $this->_description;
    }
    /**
     * Returns the number of users in the current group
     * @return int
     */
    public function getUserNumber() {
        if(!$this->_initialized)
                return false;
        return $this->_userNumber;
    }
    /**
     * Returns the current ID
     * @return int
     */
    public function getID(){
        if(!$this->_initialized)
                return false;
        return $this->_ID;
    }
    /**
     * This will check the number of users in a group and saves it in the cache
     * @return bool
     */
    public function regenerateUserNumberCache(){
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("
            SELECT COUNT(`int`)
            FROM `lttx_users`
            WHERE `userGroup` = ?",
                array($this->_ID));
        if(!$result || !isset($result->fields[0]))
                return false;
        return $this->_setUserNumber($result->fields[0]);
    }
    /**
     * This will save a new number of users to the database
     * @param int $newNumber
     * @return bool
     */
    private function _setUserNumber($newNumber){
        if(!$this->_initialized)
                return false;
        $newNumber = (int)$newNumber;
        $result = package::$db->Execute("
            UPDATE `lttx_userGroups`
            SET `userNumber` = ?
            WHERE `ID` = ?",
                array($newNumber, $this->_ID));
        if(package::$db->Affected_Rows() > 0)
                return true;
        return false;
    }
    /**
     * This will return an array of all users in the selected group
     * @return array
     */
    public function getUsers(){
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("
            SELECT `userID`
            FROM `lttx_userGroupConnections`
            WHERE `groupID` = ?",
                array($this->_ID));
        if(!$result)
            return false;
        $return = array();
        while(!$result->EOF){
            $return[] = new user($result->fields[0]);
            $result->MoveNext();
        }
        return $return;
    }
    /**
     * This will get data from cache or database by ID
     * @param int $ID
     * @return bool
     */
    private function _get($ID){
        $ID = (int)$ID;
        if($this->_getCache($ID))
                return true;
        $result = package::$db->Execute("
            SELECT `name`, `description`, `default`, `userNumber`
            FROM `lttx_userGroups`
            WHERE `ID` = ?",
                array($ID));
        if(!$result || !isset($result->fields[0]))
                return false;
        $this->_ID = $ID;
        $this->_name = $result->fields[0];
        $this->_description = $result->fields[1];
        $this->_default = $result->fields[2];
        $this->_userNumber = (int)$result->fields[3];
        self::_writeCache($this->_ID, $this->_name, $this->_description, $this->_default, $this->_userNumber);
        return true;
    }
    /**
     * This will check out the cache for a group by ID
     * @param int $ID
     * @return bool
     */
    private function _getCache($ID){
        if(!isset(self::$_cache[$ID]))
            return false;
        $this->_ID = self::$_cache[$ID]['ID'];
        $this->_name = self::$_cache[$ID]['name'];
        $this->_description = self::$_cache[$ID]['description'];
        $this->_default = self::$_cache[$ID]['default'];
        $this->_userNumber = self::$_cache[$ID]['userNumber'];
        return true;
    }
    /**
     * This will save a group into the cache
     * @param int $ID ID of group
     * @param str $name name of the group
     * @param str $description brief description
     * @param int $userNumber Number of group users
     * @return bool
     */
    private static function _writeCache($ID, $name, $description, $default, $userNumber){
        $ID = (int)$ID;
        $userNumber = (int)$userNumber;
        self::$_cache[$ID] = array(
            'name' => $name,
            'description' => $description,
            'default' => (bool)$default,
            'userNumber' => $userNumber);
        return true;
    }
    /**
     * This will add a user to the selected group
     * @param user $user
     * @return bool
     */
    public function addUser(user $user){
        if(!$this->_initialized)
                return false;
        if(!is_a($user, 'user'))
                return false;
        $ID = $user->getUserID();
        if($ID === false)
            return false;
        //Check if already in first...
        $result = package::$db->Execute("
            SELECT COUNT(`ID`)
            FROM  `lttx_userGroupConnections`
            WHERE `userID` = ?
            AND `groupID` = ?",
                array($ID, $this->_ID));
        if(!$result || !isset($result->fields[0]))
            return false;
        if($result->fields[0] > 0)
                return true;
        //end check
        $result = package::$db->Execute("
            INSERT INTO `lttx_userGroupConnections`
            (`userID`, `groupID`)
            VALUES
            (?, ?)",
                array($ID, $this->_ID));
        if(!$result || package::$db->Affected_Rows() <= 0)
                return false;
    }
    /**
     * This will kick a user from the selected group
     * @param user $user
     * @return bool
     */
    public function removeUser(user $user){
        if(!$this->_initialized)
                return false;
        $ID = $user->getUserID();
        if($ID === false)
            return false;
        $result = package::$db->Execute("
            DELETE FROM `lttx_userGroupConnections`
            WHERE `userID` = ?
            AND `groupID` = ?",
                array($ID, $this->_ID));
        if(!$result || package::$db->Affected_Rows() <= 0)
                return false;
    }
    /**
     * This will create a new user group
     * @param str $name
     * @param str $description
     * @return bool
     */
    public static function create($name, $description){
        $result = package::$db->Execute("
            INSERT INTO `lttx_userGroups`
            (`name`, `description`, `userNumber`, `default`)
            VALUES
            (?, ?, ?, ?)",
                array($name, $description, 0, false));
        if(!$result || package::$db->Affected_Rows() <= 0)
                return false;
        return true;
    }
    /**
     * This will remove the selected group and all of it's data
     * @return bool
     */
    public function removeGroup(){
        if(!$this->_initialized)
                return false;
        //Check if this is the default group first...
        $result = package::$db->Execute("
            SELECT COUNT(`ID`)
            FROM `lttx_userGroups`
            WHERE `ID` = ?
            AND `default` = ?",
                array($this->_ID, true));
        if($result && isset($result->fields[0]) && $result->fields[0] > 0)
                return false;
        $result = package::$db->Execute("
            DELETE FROM `lttx_userGroupConnections`
            WHERE `groupID` = ?",
                array($this->_ID));
        if(!$result)
            return false;
        package::$db->Execute("
            DELETE FROM `lttx_userGroups`
            WHERE `ID` = ?",
                array($this->_ID));
        if(package::$db->Affected_Rows() > 0)
                return true;
        return false;
    }
    /**
     * This will return all groups of a user passed
     * @param user $user
     */
    public static function getUsersGroups(user $user){
        $ID = $user->getUserID();
        if($ID === false)
            return false;
        $return = array();
        $result = package::$db->Execute("
            SELECT `groupID`
            FROM `lttx_userGroupConnections`
            WHERE `userID` = ?",
                array($ID));
        if(!$result || !isset($result->fields[0]))
                return false;
        while(!$result->EOF){
            $return[] = new userGroup($result->fields[0]);
            $result->MoveNext();
        }
        return $return;
    }
}