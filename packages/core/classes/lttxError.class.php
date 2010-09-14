<?php
class lttxError extends Exception{
	public function __construct  ($messageCode = ''){
		package::loadLang(package::$tpl);
		$this->message = package::getLanguageVar($messageCode);
	}
}
class lttxLog{
	public function __construct  (){
	}
	public function debug($message = ''){
		$message=mysql_real_escape_string($message);
		$currentuser=0;
		$curenttime=package::$db->DBTimeStamp(date("Y-m-d H:m:s", time()));
		if(package::$user){
			$currentuser=package::$user->getUserID();
		}
		package::$db->Execute("INSERT INTO `lttx_log` (`userid`, `logdate`, `message`) VALUES (".$currentuser.",".$curenttime.",'".$message."')");
	}
}


class lttxFatalError extends Exception{
	private $_oID = false;
	public function __construct  ($message = '', $package = false){
		package::loadLang(package::$tpl);
		$this->message = package::getLanguageVar('E_fatalErrorOccured');
		$this->_log($message, $package);
	}
	private function _log($message, $package){
		package::$db->Execute("INSERT INTO `lttx_errorLog` (`package`, `traced`, `backtrace`) VALUES (?, ?, ?)", array($package, 1, '##' . $this->getFile() . '(' . $this->getLine() . '):' . $message . "\n" . $this->getTraceAsString()));
		$this->_oID = package::$db->Insert_ID();
	}
	public function setTraced($option){
		if(!$this->_oID)return false;
		package::$db->Execute("UPDATE `lttx_errorLog` SET `traced` = ? WHERE `ID` = ?", array($option, $this->_oID));
	}
}