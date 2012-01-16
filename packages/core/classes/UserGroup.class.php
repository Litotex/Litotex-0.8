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

class UserGroup extends Basis_Entry
{

    protected $_sTableName = 'lttx1_user_groups';
    static protected $_sClassName = 'userGroup';

    /**
     * Get a List of all Entrys
     * @return self
     */
    public static function getList()
    {
        return parent::getList('userGroup');
    }

    /**
     * This returns what is saved in a special column of a group
     * @param string $key column
     * @param bool $cached on true this might use a cache or the buffer if it is activated for this group
     * @return mixed
     */
    public function getData($key)
    {
        return $this->$key;
    }

    /**
     * Get the name of the selected group
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the description which is set to the selected group
     * @return str
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the number of users in the current group
     * @return int
     */
    public function getUserNumber()
    {
        return $this->userNumber;
    }

    /**
     * Returns the current ID
     * @return int
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * This will return an array of all users in the selected group
     * @return array
     */
    public function getUsers()
    {
        $result = Package::$pdb->prepare("
            SELECT `userID`
            FROM `lttx" . Package::$pdbn . "_user_group_connections`
            WHERE `groupID` = ?");
        $result->execute(array($this->ID));
        if ($result->rowCount() < 1) return false;
        $return = array();
        foreach ($result as $user) $return[] = new User($user[0]);
        return $return;
    }

    /**
     * This will add a user to the selected group
     * @param User $user
     * @return bool
     */
    public function addUser(User $user)
    {
        if (!is_a($user, 'user')) return false;
        $ID = $user->getUserID();
        if ($ID === false) return false;
        //Check if already in first...
        $result = Package::$pdb->prepare("
            SELECT COUNT(`ID`)
            FROM  `lttx" . Package::$pdbn . "_user_group_connections`
            WHERE `userID` = ?
            AND `groupID` = ?");
        $result->execute(array($ID, $this->ID));
        if ($result->rowCount() < 1) return false;
        $result = $result->fetch();
        if ($result[0] > 0) return true;
        //end check
        $result = Package::$pdb->prepare("
            INSERT INTO `lttx" . Package::$pdbn . "_user_group_connections`
            (`userID`, `groupID`)
            VALUES
            (?, ?)");
        $result->execute(array($ID, $this->ID));
        if ($result->rowCount() < 1) return false;
    }

    /**
     * This will kick a user from the selected group
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user)
    {
        $ID = $user->getUserID();
        if ($ID === false) return false;
        $result = Package::$pdb->prepare("
            DELETE FROM `lttx" . Package::$pdbn . "_userGroupConnections`
            WHERE `userID` = ?
            AND `groupID` = ?");
        $result->execute(array($ID, $this->ID));
        if ($result->rowCount() < 1) return false;
    }

    public function deleteAllUsers()
    {
        $aUsers = $this->getUsers();
        foreach ((array )$aUsers as $oUser) $this->removeUser($oUser);
    }

    /**
     * This will remove the selected group and all of it's data
     * @return bool
     */
    public function delete()
    {
        if ($this->default == 1) return false;

        Package::$pdb->prepare("
            DELETE FROM `lttx" . Package::$pdbn . "_user_group_connections`
            WHERE `groupID` = ?")->execute(array($this->ID));

        parent::delete();

        return false;
    }

    public static function getDefault()
    {
        $return = array();
        $result = Package::$pdb->prepare("
            SELECT `ID`
            FROM `lttx" . Package::$pdbn . "_user_groups`
            WHERE `default` = ?");
        $result->execute(array(1));

        if ($result->rowCount() < 1) return false;
        foreach ($result as $group) $return[] = new UserGroup($group[0]);
        return $return;
    }


}
