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
class userGroup extends Basis_Entry {

	protected $_sTableName = 'lttx1_user_groups';
	static protected $_sClassName = 'userGroup';

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList(){
		return parent::getList('userGroup');
	}

	 /**
     * This returns what is saved in a special column of a group
     * @param string $key column
     * @param bool $cached on true this might use a cache or the buffer if it is activated for this group
     * @return mixed
     */
    public function getData($key) {
        return $this->$key;
    }
  
    /**
     * Get the name of the selected group
     * @return string
     */
    public function getName(){
        return $this->name;
    }

	/**
     * Returns the description which is set to the selected group
     * @return str
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Returns the number of users in the current group
     * @return int
     */
    public function getUserNumber() {
        return $this->userNumber;
    }

    /**
     * Returns the current ID
     * @return int
     */
    public function getID(){
        return $this->ID;
    }
	
    /**
     * This will return an array of all users in the selected group
     * @return array
     */
    public function getUsers(){
        $result = package::$db->Execute("
            SELECT `userID`
            FROM `lttx1_user_group_connections`
            WHERE `groupID` = ?",
                array($this->ID));
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
     * This will add a user to the selected group
     * @param user $user
     * @return bool
     */
    public function addUser(user $user){
        if(!is_a($user, 'user'))
                return false;
        $ID = $user->getUserID();
        if($ID === false)
            return false;
        //Check if already in first...
        $result = package::$db->Execute("
            SELECT COUNT(`ID`)
            FROM  `lttx1_user_group_connections`
            WHERE `userID` = ?
            AND `groupID` = ?",
                array($ID, $this->ID));
        if(!$result || !isset($result->fields[0]))
            return false;
        if($result->fields[0] > 0)
                return true;
        //end check
        $result = package::$db->Execute("
            INSERT INTO `lttx1_user_group_connections`
            (`userID`, `groupID`)
            VALUES
            (?, ?)",
                array($ID, $this->ID));
        if(!$result || package::$db->Affected_Rows() <= 0)
                return false;
    }
	
    /**
     * This will kick a user from the selected group
     * @param user $user
     * @return bool
     */
    public function removeUser(user $user){
        $ID = $user->getUserID();
        if($ID === false)
            return false;
        $result = package::$db->Execute("
            DELETE FROM `lttx_userGroupConnections`
            WHERE `userID` = ?
            AND `groupID` = ?",
                array($ID, $this->ID));
        if(!$result || package::$db->Affected_Rows() <= 0)
                return false;
    }

	public function deleteAllUsers(){
		$aUsers = $this->getUsers();
		foreach((array)$aUsers as $oUser){
			$this->removeUser($oUser);
		}
	}
		
    /**
     * This will remove the selected group and all of it's data
     * @return bool
     */
    public function delete(){
		if($this->default == 1){
			return false;
		}

       package::$db->Execute("
            DELETE FROM `lttx1_user_group_connections`
            WHERE `groupID` = ?",
                array($this->ID));

		parent::delete();

        return false;
    }
	
    public static function getDefault(){
    	$return = array();
        $result = package::$db->Execute("
            SELECT `ID`
            FROM `lttx1_user_groups`
            WHERE `default` = ?",
                array(1));
                echo mysql_error();
        if(!$result || !isset($result->fields[0]))
                return false;
        while(!$result->EOF){
            $return[] = new userGroup($result->fields[0]);
            $result->MoveNext();
        }
        return $return;
    }



}