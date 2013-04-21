<?php
class UserField{
	/**
	 * Plugin Handler for userfields
	 * @var PluginHandler
	 */
	protected static $_pluginHandler = NULL;
	private $_data = array();
	private $_ID = 0;
	public static function getList(){
		$return = array();
		$result = package::$pdb->query("SELECT `ID` FROM `lttx1_userfields` ORDER BY `position`"); //TODO: Cache
		foreach($result as $item){
			$return[] = new UserField($item[0]);
		}
		return $return;
	}
	
	private static function setPluginHandler(){
		if(self::$_pluginHandler == NULL){
			self::$_pluginHandler = new PluginHandler($name = 'userFields', $location = 'userFields', $cacheLocation = 'userFields.plugin.cache.php', $currentFile = __FILE__);
		}
	}

	public function __construct($ID){
		$data = package::$pdb->prepare("SELECT * FROM `lttx1_userfields` WHERE `ID` = ?");
		$data->execute(array($ID));
		if($data->rowCount() < 1){
			throw new LitotexError("E_UserFieldNotFound", $ID);
		}
		$this->_ID = $ID;
		$this->_data = $data->fetch();
		self::setPluginHandler();
		$this->pluginValidate();
	}

	public function getHTML(){
		return self::$_pluginHandler->callPluginFunc($this->getType(), 'getHTML', array($this, package::$user));
	}

	public function getTypeName(){
		return self::$_pluginHandler->getLangVar($this->getType(), 'typeName');
	}
	
	public static function getTypeNameStatic($type){
		self::setPluginHandler();
		return self::$_pluginHandler->getLangVar($type, 'typeName');
	}

	public function getType(){
		return $this->_data['type'];
	}

	public function pluginValidate() {
		if(!self::$_pluginHandler->pluginExists($this->getType())){
			throw new LitotexError ('userField_noPlugin', $this->getType());
		}
		return true;
	}
	
	public static function getTypes(){
		self::setPluginHandler();
		$list = self::$_pluginHandler->getPluginList();
		$return = array();
		foreach($list as $type){
			$return[] = array($type, self::getTypeNameStatic($type));
		}
		return $return;
	}

	public function validate($value) {
		return self::$_pluginHandler->callPluginFunc($this->getType(), 'validateContent', array($value));
	}
	
	public function getKey(){
		return $this->_data['key'];
	}
	
	public function getID(){
		return $this->_ID;
	}
	
	public static function create($key, $type, $extra, $package, $optional, $display, $editable){
		if(!$key || $key == '')
			return false;
		$result = package::$pdb->prepare("INSERT INTO `lttx1_userfields` (
				`key`, `type`, `extra`, `optional`, `display`, `editable`, `package`, `position`
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		if(!$result->execute(array($key, $type, $extra, $optional, $display, $editable, $package, 0)))
			return false;
		$insertID = package::$pdb->lastInsertId();
		return new UserField($insertID);
	}
	
	public function delete(){
		$result = package::$pdb->prepare("DELETE FROM `lttx1_userfields` WHERE `ID` = ?");
		$result->execute(array($this->getID()));
	}
	
	public function setPosition($pos){
		$result = package::$pdb->prepare("UPDATE `lttx1_userfields` SET `position` = ? WHERE `ID` = ?");
		$result->execute(array(intval($pos), $this->getID()));
	}
}
