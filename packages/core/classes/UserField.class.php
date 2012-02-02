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
		$result = package::$pdb->query("SELECT `ID` FROM `lttx1_userfields`"); //TODO: Cache
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

	public static function getTypeNameByType($type){
		self::setPluginHandler();
		return self::$_pluginHandler->getLangVar($type, 'typeName');
	}

	public function getTypeName(){
		return self::$_pluginHandler->getLangVar($this->getType, 'typeName');
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

	public function validate($value) {
		return self::$_pluginHandler->callPluginFunc($this->getType(), 'validateContent', array($value));
	}
}