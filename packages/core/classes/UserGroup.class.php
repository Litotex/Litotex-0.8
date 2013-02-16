<?php
class UserGroup{
	private $_ID = 0;
	private $_data = array();

	public function __construct($ID){
		$ID = intval($ID);
		$this->_ID = $ID;
		if(!self::exists($ID)){
			if(!defined('DEVDEBUG') || DEVDEBUG == true)
				$this->deleteAllUsers();
			throw new LitotexError('E_UserGroupNotFound', $ID);
		}
		$result = Package::$pdb->prepare("SELECT * FROM `lttx1_user_groups` WHERE `ID` = ?");
		$result->execute(array($ID));
		$this->_data = $result->fetch();
	}

	private static function exists($ID){
		$result = Package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx1_user_groups` WHERE `ID` = ?");
		$result->execute(array($ID));
		$result = $result->fetch();

		if($result[0] < 1){
			return false;
		}
		return true;
	}

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList(){
		$return = array();
		$result = Package::$pdb->query("SELECT `ID` FROM `lttx1_user_groups`");
		foreach($result as $group){
			$return[] = new UserGroup($group['ID']);
		}
		return $return;
	}

	/**
	 * This returns what is saved in a special column of a group
	 * @param string $key column
	 * @param bool $cached on true this might use a cache or the buffer if it is activated for this group
	 * @return mixed
	 */
	public function getData($key) {
		return $this->_data[$key];
	}

	/**
	 * Get the name of the selected group
	 * @return string
	 */
	public function getName(){
		return $this->_data['name'];
	}

	/**
	 * Returns the description which is set to the selected group
	 * @return str
	 */
	public function getDescription(){
		return $this->_data['description'];
	}

	/**
	 * Returns the number of users in the current group
	 * @return int
	 */
	public function getUserNumber() {
		return $this->_data['userNumber'];
	}

	/**
	 * Returns the current ID
	 * @return int
	 */
	public function getID(){
		return $this->_ID;
	}

	/**
	 * This will return an array of all users in the selected group
	 * @return array
	 */
	public function getUsers(){
		$result = Package::$pdb->prepare("SELECT `userID` FROM `lttx1_user_group_connections` WHERE `groupID` = ?");
		$result->execute(array($this->getID()));
		if($result->rowCount() < 1){
			return false;
		}
		$return = array();
		foreach($result as $user){
			$return[] = new User($user[0]);
		}
		return $return;
	}

	/**
	 * This will add a user to the selected group
	 * @param User $user
	 * @return bool
	 */
	public function addUser(User $user){
		$ID = $user->getUserID();
		if($ID === false){
			return false;
		}
		//Check if already in first...
		$result = Package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx1_user_group_connections` WHERE `userID` = ? AND `groupID` = ?");
		$result->execute(array($ID, $this->getID()));
		if($result->rowCount() < 1){
			return false;
		}

		$result = $result->fetch();
		if($result[0] > 0){
			return true;
		}
		//end check
		$result = Package::$pdb->prepare("INSERT INTO `lttx1_user_group_connections` (`userID`, `groupID`) VALUES (?, ?)");
		$result->execute(array($ID, $this->getID()));
		if($result->rowCount() < 1){
			return false;
		}
		return true;
	}

	/**
	 * This will kick a user from the selected group
	 * @param User $user
	 * @return bool
	 */
	public function removeUser(User $user){
		$ID = $user->getUserID();
		if($ID === false)
			return false;
		$result = Package::$pdb->prepare("DELETE FROM `lttx".Package::$pdbn."_user_group_connections` WHERE `userID` = ? AND `groupID` = ?");
		$result->execute(array($ID, $this->getID()));
		if($result->rowCount() < 1)
			return false;
		return true;
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

		Package::$pdb->prepare("
				DELETE FROM `lttx1_user_group_connections`
				WHERE `groupID` = ?")->execute(array($this->getID()));

		parent::delete();

		return false;
	}
	public static function getDefault(){
		$return = array();
		$result = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_user_groups` WHERE `default` = ?");
		$result->execute(array(1));

		if($result->rowCount() < 1)
			return false;
		foreach($result as $group){
			$return[] = new UserGroup($group[0]);
		}
		return $return;
	}
}
