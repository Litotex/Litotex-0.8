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
 * This class provides methodes to manipulate user informations
 * Further it offers functions to interact with other users
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class User {
	/**
	 * Is this instance initialized successfully
	 * @var bool
	 */
	private $_initialized = false;
	/**
	 * ID of current user
	 * @var int
	 */
	private $_currentID = 0;
	/**
	 * Static salt is used to salt every hash DO NOT CHANGE IT AFTER THE FIRST USE
	 * @var string
	 */
	private static $_staticSalt = 'agudeb7c6xf nkfcb refnm4 x__.$&/§&"$%jj';
	/**
	 * String table to generate a dynamic salt from
	 * @var array
	 */
	private static $_dynamicSaltChars = array(
            'a', 'b', 'c', '$', '§', '_', '..', 'mwe', 'ßß?', '//\\', '`', '1337', '###'
	);
	private static $_dynamicSaltLenght = 20;
	/**
	 * Global cache for every user loaded during the script runtime
	 * Can be deactivated by using global cache policys
	 * @var array
	 */
	private static $_readCache = array();
	/**
	 * This will save all usernames and it's ids to make this work faster
	 * @var array
	 */
	private static $_usernames = array();
	/**
	 * This buffer keeps all write actions together to perform one query in the end
	 * @var array
	 */
	private $_writeBuffer = array();
	/**
	 * Default value for buffer settings
	 * @var bool
	 */
	private $_bufferActive = true;
	/**
	 * Default value or cache settings
	 * @var bool
	 */
	private $_cacheActive = true;
	/**
	 * Was this user fully buffered already?
	 * @var bool
	 */
	private $_buffered = false;
	/**
	 * Default value for global caching
	 * @var bool
	 */
	private static $_globalCacheActive = true;
	/**
	 * Default value for global buffering
	 * @var bool
	 */
	private static $_globalBufferActive = true;
	/**
	 * Is this the current user the owner of this account?
	 * @var bool
	 */
	private $_loggedIn = false;
	/**
	 * Set to true if the acp is accessable
	 * @var bool
	 */
	private $_acpLegit = false;
	/**
	 * Timestamp of acp access
	 * @var int
	 */
	private $_acpLegitTime = 0;
	/**
	 * Time after the acp legitimation is expired and will be revoked default: 1h (3600sec)
	 * @var int
	 */
	private static $_acpLegitExpire = 3600;
	/**
	 * The last error from Login
	 * @var <string>
	 */
	public static $sLastLoginError = '';
	/**
	 * This function loads data of a new user from the database
	 * There are more ways to get an instance
	 * @param int $userID ID of user to be loaded
	 * @return void
	 */
	public function __construct($userID) {
		//if($userID == 0)
		//	return;
		
		$userID = intval($userID);
		if(!self::userExists($userID) && $userID > 0){
                         Package::debug('User ' . $userID . ' was not found', LOG_ERR);
                         return;
                }
		$this->_currentID = $userID;
		$this->_initialized = true;
		$this->_bufferActive = self::$_globalBufferActive;
		$this->_cacheActive = self::$_globalCacheActive;
		return;
	}
	/**
	 * This function writes session information if neccessary
	 * @return void
	 */
	public function __destruct() {
		if(!$this->_initialized)
			return;
		$this->_saveWriteBuffer();
	}
	/**
	 * This function returns the username of the active user
	 * @return string
	 */
	public function __toString() {
		if(!$this->_initialized)
			return false;
		return $this->getData('username');
	}

	/**
	 * Update / Insert User with an Array of Data
	 * @param $aData
	 */
	public function update($aData){
			
		$iUserId = (int)$this->getData('ID');

		// check for PW
		if(isset($aData['password']) && !empty($aData['password'])) {
			$aPasswordSalted        = self::_saltString($aData['password']);
			$aData['password']      = hash('sha512', $aPasswordSalted[1]);
			$aData['dynamicSalt']   = $aPasswordSalted[0];
		} else if (isset($aData['password']) && empty($aData['password'])) {
			unset($aData['password']);
		}
			
		$sSql = "";
		$aSql = "";
		$sSqlSetPart = "";
		
		// write SET Part
		foreach((array)$aData as $sField => $mValue){
			$sSqlSetPart .= "`".$sField."` = ?, ";
			$aSql[] = $mValue;
			// cache
			$this->setData($sField, $mValue, true);
		}
		
		// remove last ","
		$sSqlSetPart = rtrim($sSqlSetPart, ', ');
		$bSuccess = true;
		if($iUserId > 0  &&	self::userExists($iUserId)){
		
			$sSql = " UPDATE `lttx1_users` SET ".$sSqlSetPart." WHERE `ID` = ? ";
			$aSql[] = $iUserId;
			// Update
			$bSuccess = Package::$pdb->prepare($sSql)->execute($aSql);
			
		} else if($iUserId == 0){

			$sSql = " INSERT INTO
    						`lttx1_users`
    					SET
    					".$sSqlSetPart;
			// Insert
			$bSuccess = Package::$pdb->prepare($sSql)->execute($aSql);
			// get ID
			$iUserId = Package::$pdb->lastInsertId();
			// set New ID
			$this->_currentID = $iUserId;
		} else {
			throw new LitotexError('E_unknownUser');
		}

		if($bSuccess === false){
			$sDBError = Package::$pdb->errorCode();
			throw new LitotexError($sDBError);
		}
			
		return true;
	}

	/**
	 * This function will create a new user and return an instance of the created user immediately
	 * @param string $username
	 * @param string $password (unhashed)
	 * @param array $data array of data that should be written to the database
	 * @return int on failure [-1 username exists -2 email exists -3 unknown error]
	 			instance of User class otherwise
	 */
	public static function register($username, $password, $email, $data) {
		$passwordSalted = self::_saltString($password);
			// Doeas the Username already exist?
		if(self::userExists($username))
			return -1;
			// Check for free Email_Adress
		$result = Package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx1_users` WHERE `email` = ?");
		$result->execute(array($email));
		if($result->rowCount() != 1) // COUNT should give exactely one line
			return -3;
		$result = $result->fetch();
		if($result[0] > 0) // Email exists
			return -2;
		
		// Additional Data can be (Default in braces): userGroup (0), race (0), lastActive(NULL), isActive(1), registerDate (CURRENT_TIMESTAMP), serverAdmin (0), bannedDate (NULL), bannedReason (NULL)
		$additionalDataColumns = $additionalDataPointer = '';
		$additionalData = array($username, $email, hash('sha512', $passwordSalted[1]), $passwordSalted[0]);
		foreach($data as $key => $value) {
			$additionalData[] = $value;
			$additionalDataPointer .= ', ?';
			$additionalDataColumns .= ', `' . $key . '`';
		}
		$result = Package::$pdb->prepare("INSERT INTO `lttx1_users`
			(`username`, `email`, `password`, `dynamicSalt`" . $additionalDataColumns . ")
			VALUES (?, ?, ?, ?" . $additionalDataPointer . ")");
		$result->execute($additionalData);
		if($result->rowCount() < 1) // strange error
			return -3;
		return new User(Package::$pdb->lastInsertId());
	}

	/**
	 * This will check the login information and return an instance of the user class on success
	 * @param string $username username
	 * @param string $password unhashed password
	 * @return bool on failure | user
	 */
	static public function login($username, $password) {
		$user = self::getUserByName($username);

		if(!$user){
			self::$sLastLoginError = 'login_incorrect';
			Package::debug('Login failed! Unknown user: ' . $username, LOG_INFO);
			return false;
		} else if($user->checkUserBanned()){
			self::$sLastLoginError = 'login_user_banned';
			Package::debug('Login failed! Banned user: ' . $username, LOG_INFO);
			return false;
		} else if(!$user->checkUserActive()){
			self::$sLastLoginError = 'login_user_inactive';
			Package::debug('Login failed! Inactive user: ' . $username, LOG_INFO);
			return false;
		} else {
			if(self::_compareSaltString($password, $user->getData('password'), $user->getData('dynamicSalt'))) {
				$user->setUsersInstance();
				$user->setData('lastActive', date("Y-m-d H:i:s",time()), false);
				Package::$session->setUserObject($user);
				return $user;
			} else {
				self::$sLastLoginError = 'login_incorrect';
				Package::debug('Login failed! Wrong password: ' . $username, LOG_INFO);
				return false;
			}
		}
		self::$sLastLoginError = 'login_incorrect';
		Package::debug('Login failed! Another error; user: ' . $username . '; Salt: ' . 
            (int)self::_compareSaltString($password, $user->getData('password'), $user->getData('dynamicSalt')), LOG_INFO);
		return false;
	}
	/**
	 * Returns a salted string (static and dynamic salted) which is sh1 hashed afterwards
	 * @param string $str string to salt
	 * @return array (0 = new dynamic salt, 1 = salted string)
	 */
	static private function _saltString($str, $dynSalt = false) {
		if($dynSalt === false) {
			$dynSalt = '';
			for($i = 0; $i < self::$_dynamicSaltLenght; $i++) {
				$dynSalt .= self::$_dynamicSaltChars[rand(0, (count(self::$_dynamicSaltChars) - 1))];
			}
		}
		$salt = $dynSalt . $str . self::$_staticSalt;
		return array($dynSalt, $salt);
	}
	/**
	 * Compares a salted string (sh1 hashed) with an unsalted string (unhased)
	 * @param str $str1 unsalted and unhashed string
	 * @param str $str2 salted and hashed string to compare
	 * @param str $dynSalt dynamic generated salt which was used to salt str2
	 * @return bool (true = match)
	 */
	static private function _compareSaltString($str1, $str2, $dynSalt) {
		$str1 = self::_saltString($str1, $dynSalt);
		if(hash('sha512', $str1[1]) == $str2)
		return true;
		return false;
	}
	/**
	 * This creates a new user instance by using the username
	 * @param str $username username of user to be loaded
	 * @return bool on failure | user
	 */
	static public function getUserByName($username) {
		if(isset(self::$_usernames[$username])) {
			return new User(self::$_usernames[$username]);
		}
		$result = Package::$pdb->prepare("
            SELECT `ID`
            FROM `lttx1_users`
            WHERE `username` = ?");
		$result->execute(array($username));
		
		if($result->rowCount() < 0)
		return false;
		$result = $result->fetch();
		if($result[0] != 0) {
			//self::$_usernames[$username] = $result[0];
			return new User($result[0]);
		}
		return false;
	}
	/**
	 * This returns what is saved in a special column of a user
	 * @param string $key column
	 * @param bool $cached on true this might use a cache or the buffer if it is activated for this user
	 * @return mixed
	 */
	public function getData($key, $cached = true, $buffered = true) {
		if(!$this->_initialized)
			return false;
		if ($this->_currentID == 0)
			return false;
		if($cached && $this->_cacheActive) {
			if(isset(self::$_readCache[$this->_currentID][$key])) {
				return self::$_readCache[$this->_currentID][$key];
			}
		}
		if($buffered && !$this->_buffered && $this->_bufferActive) {
			$this->_createFullBuffer();
			return $this->getData($key, $cached, false);
		}
		//Nothing was cached... read manually
		$result = Package::$pdb->prepare("SELECT `" . $key . "` FROM `lttx1_users` WHERE `id` = ?");
		$result->execute(array($this->_currentID));
		if($result->rowCount() < 1)
			return false;
		$result = $result->fetch();
		self::$_readCache[$this->_currentID][$key] = $result[0];
		return $result[0];
	}
	/**
	 * This saves a new data for a specific column
	 * @param str $key column to save
	 * @param str $newValue new data
	 * @param bool $cached if true is set, the data will be written to the database when the destructor is called, data is loadable with getData when cache is set to true
	 * @return bool
	 */
	public function setData($key, $newValue, $cached = true) {
		if(!$this->_initialized)
		return false;
		if($cached && $this->_bufferActive) {
			if(!$this->_buffered) {
				if(!$this->_createFullBuffer())
				return false;
			}
			if(!isset(self::$_readCache[$this->_currentID][$key]))
			return false;
			$this->_writeBuffer[$key] = $newValue;
			return true;
		}
		$result = Package::$pdb->prepare("UPDATE `lttx1_users` SET `" . $key . "` = ? WHERE `ID` = ?");
		$result->execute(array($newValue, $this->_currentID));
		self::$_readCache[$this->_currentID][$key] = $newValue;
		return true;
	}
	/**
	 * This sets the global cacheing policy for every instance of the user classcreated by now, true is default, false stops cacheing overall (even if a function is casted with cacheing)
	 * This will also control buffering
	 * @param bool $cache true = active cache false = no cache or buffer
	 * @return bool
	 */
	static public function setGlobalCachePolicy($cache) {
		if(!is_bool($cache))
		return false;
		self::$_globalCacheActive = $cache;
		self::$_globalBufferActive = $cache;
		return true;
	}
	/**
	 * This sets the cacheing policy for the active instance only
	 * This will also controll buffering on false
	 * @param bool $cache true = active cache false = no cache or buffer
	 * @return bool
	 */
	public function setLocalCachePolicy($cache) {
		if(!$this->_initialized)
		return false;
		if(!is_bool($cache))
		return false;
		$this->_cacheActive = $cache;
		if($cache == false)
		$this->_bufferActive = false;
		return true;
	}
	/**
	 * This sets the local buffer policy which might be set by cache policys too
	 * true will activate cacheing too!
	 * @param bool $buffer new policy
	 * @return bool
	 */
	public function setLocalBufferPolicy($buffer) {
		if(!$this->_initialized)
		return false;
		if(!is_bool($buffer))
		return false;
		$this->_bufferActive = $buffer;
		if($buffer == true)
		$this->_cacheActive = true;
		return true;
	}
	/**
	 * This will fullbuffer userdata of a user
	 * @return bool
	 */
	private function _createFullBuffer() {
			
		if(!$this->_initialized)
		return false;
		if(!$this->_bufferActive)
		return false;
		if($this->getUserID() == 0)
		return false;

		$result = Package::$pdb->prepare("
            SELECT *
            FROM `lttx1_users`
            WHERE `id` = ?");
		$result->execute(array($this->_currentID));
		$result = $result->fetch();
		if (!$result)	// no User
			return false;
		foreach ($result as $key => $value) {
			self::$_readCache[$this->_currentID][$key] = $value;
		}
		$this->_buffered = true;
		return true;
	}
	/**
	 * This will buffer all columns set in $buffered
	 * @param array $bufferedcolumns to be buffered
	 * @return bool
	 */
	public function createDefinedBuffer($buffered) {
		if(!$this->_initialized)
		return false;
		if(!is_array($buffered))
		return false;
		$fields = '';
		for($i = 0; $i < count($buffered); $i++) {
			if($i == 0) {
				$fields .= '`' . $buffered[$i] . '`';
				continue;
			}
			$fields .= ', `' . $buffered[$i] . '`';
		}
		$result = Package::$pdb->prepare("
            SELECT " . $fields . "
            FROM `lttx1_users`
            WHERE `id` = ?");
		$result->execute(array($this->_currentID));
		if($result->rowCount() < 1)
		return false;
		$result = $result->fetch();
		foreach($result as $key => $value) {
			self::$_readCache[$this->_currentID][$key] = $value;
		}
		return true;
	}
	/**
	 * This will write the buffer
	 * @return bool
	 */
	private function _saveWriteBuffer() {
		if(!$this->_initialized)
		return false;
		if(count($this->_writeBuffer) <= 0)
		return true;
		$queryString = "UPDATE `lttx".package::$pdbn."_users` SET ";
		$values = array();
		$i = 0;
		foreach($this->_writeBuffer as $key => $value) {
			if($i != 0) {
				$queryString .= ', ';
			}
			$queryString .= '`' . $key . '` = ?';
			$values[] = $value;
			$i++;
		}
		$queryString .= ' WHERE `ID` = ?';
		$values[] = $this->_currentID;
		$result = Package::$pdb->prepare($queryString);
		$result->execute($values);
		if($result->rowCount() < 1)
		return false;
		return true;
	}

	/**
	 * Checks if a user exists (uses id or username)
	 * @param int (explecit) | string $user userid or name
	 * @return bool on failure | int id of user
	 */
	public static function userExists($user) {
		if(is_int($user)) {
			if (intval($user) ==0)
			return false;
			
			$result = Package::$pdb->prepare("
                SELECT COUNT(`ID`)
                FROM `lttx1_users`
                WHERE `ID` = ?");
			$result->execute(array($user));
		} else {
			$result = Package::$pdb->prepare("
                SELECT COUNT(`ID`)
                FROM `lttx1_users`
                WHERE `username` = ?");
			$result->execute(array($user));
		}
		if($result->rowCount() < 0) {
			die('Database failure!');
			return false;
		}
		$result = $result->fetch();
		if($result[0] == 1)
		return true;
		return false;
	}



	/**
	 * This will return the user's ID
	 * @return int | bool on failure
	 */
	public function getUserID() {
		if(!$this->_initialized)
		return false;
		return (int)$this->_currentID;
	}

	/**
	 * This will return the user's name
	 * @return string | bool on failure
	 */
	public function getUsername() {
		if(!$this->_initialized)
		return false;
		return $this->getData('username');
	}

	public function getLastActive(){
		return $this->getData('lastActive');
	}

	public function getCreateDate(){
		return $this->getData('registerDate');
	}

	public function getStatus(){
		if($this->checkUserBanned()){
			return Package::getLanguageVar('users_is_banned');
		} else {
			return Package::getLanguageVar('users_is_active');
		}
	}

	/**
	 * This will check if the user is banned
	 * @return bool
	 */
	public function checkUserBanned(){
		if(
		$this->getData('bannedDate') != NULL &&
		$this->getData('bannedDate') != '0000-00-00 00:00:00'
		){
			return true;
		}
		return false;
	}

	public function checkUserActive(){
		if(
		$this->getData('isActive') == 1
		){
			return true;
		}
		return false;
	}

	/**
	 * This will ban a user for a specific amount of time
	 * @param str $reason Reason to ban the user (may show up on login)
	 * @return bool
	 */
	public function banUser($reason = '') {
		$aData = array();
		$aData['bannedDate'] = date('Y-m-d H:i:s');
		$aData['bannedReason'] = $reason;
		$this->update($aData);
		return true;
	}

	/**
	 * This will unban a User
	 */
	public function unbanUser(){
		$aData = array();
		$aData['bannedDate'] = '0000-00-00 00:00:00';
		$aData['bannedReason'] = '';
		$this->update($aData);
		return true;
	}

	/**
	 * This will check if the current user is owner of this account
	 * @return bool
	 */
	public function isUsersInstance() {
		if(!$this->_initialized)
		return false;
		return $this->_loggedIn;
	}

	public function delete(){
		$this->setData('isActive', 0, false);
		return true;
	}

	/**
	 * This will set the current user as to be the owner of this account
	 * @return bool
	 */
	public function setUsersInstance() {
		if(!$this->_initialized)
		return false;
		$this->_loggedIn = true;
		return true;
	}

	/**
	 * This will delete the cache and set all modifications to a default set
	 * @return bool
	 */
	public function flushCache() {
		if(!$this->_initialized)
		return false;
		$this->setLocalBufferPolicy(true);
		$this->_buffered = false;
		unset(self::$_readCache[$this->_currentID]);
		return $this->_saveWriteBuffer();
	}

	/**
	 * This will get all user groups in an array
	 * @return  mixed
	 */
	public function getUserGroups() {

		if(!$this->_initialized || $this->getUserID() <= 0){
			return UserGroup::getDefault();
		}

		$ID = $this->getUserID();

		if($ID === false){
			return false;
		}

		$aGroups = array();
		$result = Package::$pdb->prepare("
            SELECT `groupID`
            FROM `lttx1_user_group_connections`
            WHERE `userID` = ?");
		$result->execute(array($ID));

		if($result->rowCount() < 1){
			return false;
		}

		foreach($result as $connection){
#			echo "UserGroup: " . $connection[0];
			$aGroups[] = new UserGroup($connection[0]);
		}
		
		return $aGroups;
	}

	public function getAvailableGroups(){
		$aGroups = UserGroup::getList();
		$aUserGroups = $this->getUserGroups();

		if($aUserGroups !== false){
			foreach((array)$aUserGroups as $oUserGroup){
				foreach((array)$aGroups as $iKey => $oGroup){
					if($oGroup->getID() == $oUserGroup->getID()){
						unset($aGroups[$iKey]);
					}
				}
			}
		}

		return $aGroups;
	}

	/**
	 * This will logout the currently logged in user by deleting the session
	 */
	public function logout(){
		Package::$session->destroy();
	}

	/**
	 * This will update the password and generate a new salt
	 * @param   string  $password
	 * @return  bool
	 */
	public function setPassword($password){
		$salted = $this->_saltString($password);
		Package::$pdb->prepare("UPDATE `lttx1_users` SET `password` = ?, `dynamicSalt` = ? WHERE `ID` = ?")->execute(array(hash('sha512', $salted[1]), $salted[0], $this->_currentID));
		return true;
	}

	/**
	 * This will set legitimation for acp access
	 * @return  bool
	 */
	public function setAcpLogin(){
		if(!$this->_initialized)
		return false;
		$this->_acpLegit = true;
		$this->_acpLegitTime = time();
		return true;
	}

	/***
	 * This will revoke the acp access legitimation
	*/
	public function revokeAcpLogin(){
		if(!$this->_initialized)
		return false;
		$this->_acpLegit = false;
		$this->_acpLegitTime = 0;
		return true;
	}

	/**
	 * This will check if the user has legitimation for acp access
	 * @return  bool
	 */
	public function isAcpLogin(){
		return $this->_acpLegit;
	}

	/**
	 * This will check if the acp legitimation is expired
	 * @return  bool
	 */
	public function checkAcpLoginExpired(){
		if(!$this->_acpLegit){
			return false;
		}
		return (time() > ($this->_acpLegitTime + self::$_acpLegitExpire))?false:true;
	}

	/**
	 * This will make the acp legitimation new
	 * @return  bool
	 */
	public function acpReLegit(){
		if(!$this->_acpLegit){
			return false;
		}
		$this->_acpLegitTime = time();
		return true;
	}

	/**
	 * This will check if the instance is initialized successfully
	 * @return  bool
	 */
	public function initialized(){
		return (bool)$this->_initialized;
	}



	/**
	 * This will compare two user objects (id's)
	 * @param   User    $u1
	 * @param   User    $u2
	 * @return  bool
	 */
	public static function compare(User $u1, User $u2){
		if(!$u1->initialized() || !$u2->initialized())
		return false;
		if($u1->getUserID() == $u2->getUserID())
		return true;
		return false;
	}

	/**
	 * This will seach for all user id's which profile fields match to the given value
	 * @param   string  $request
	 * @param   string  $field
	 * @return  User
	 */
	public static function search($request, $field = 'username'){
		//We absolutly need to stop buffering every entry! This would be the perfect overkill
		$return = array();
		$match = array();
		$searchResults = Package::$pdb->prepare("SELECT `ID`, `".$field."` FROM `lttx1_users` WHERE `".$field."` LIKE ? AND `isActive` = 1");
		$searchResults->execute(array('%' . $request . '%'));
		if(!$searchResults){
			throw new LitotexDBError();
		}
		foreach($searchResults as $result){
			if($result[1] == $request){
				$user = new User($result[0]);
				$match[] = $user;
			} else {
				$user= new User($result[0]);
				$return[] = $user;
			}
			$user->setLocalBufferPolicy(false);
		}
		$return = array_merge($match, $return);
		return $return;
	}

	public function saveUserFieldData($iFieldId, $mValue){
		$sSql = " REPLACE INTO `lttx1_userfields_userdata` SET `field_id` = ?, `user_id` = ?, value = ? ";
		$aSql = array($iFieldId, $this->getData('ID'), $mValue);
		Package::$pdb->prepare($sSql)->execute($aSql);
	}

	public function validateFieldData($iFieldId, $mValue){
		$field = new UserField($iFieldId);
		if(!$field->validate($mValue)){
			throw new LitotexError('E_user_couldNotValidateUserField', $field->getKey());
		}
	}

	public function getUserFieldData($iFieldId){
		$sSql = " SELECT `value` FROM `lttx1_userfields_userdata` WHERE `field_id` = ? AND `user_id` = ? ";
		$aSql = array($iFieldId, $this->getData('ID'));
		$mValue = Package::$pdb->prepare($sSql);
		$mValue->execute($aSql);
		$mValue = $mValue->fetch();
		$mValue = $mValue[0];
		return $mValue;
	}

	public function deleteAllGroups(){
		$result = Package::$pdb->prepare("
            DELETE FROM `lttx1_user_group_connections`
            WHERE `userID` = ?")
		->execute(array($this->getUserID()));
		return true;
	}
}
