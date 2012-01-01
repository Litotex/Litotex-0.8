<?php
class lttxError extends Exception{
	public function __construct  ($messageCode){
		$args = func_get_args();
		$messageCode = $args[0];
		package::loadLang(package::$tpl);
                if(package::getLanguageVar($messageCode) != '')
                    $this->message = package::getLanguageVar($messageCode);
                else
                    $this->message = $messageCode;
		$argstr = '';
		foreach($args as $i => $arg){
			if($i == 0)
				continue;
			$argstr = ',$args['.$i.']';
		}
		eval("\$this->message = sprintf(\$this->message$argstr);");
	}
}
class lttxDBError extends lttxError{
	public function __construct  (){
		$sError = package::$db->errorCode();
		parent::__construct($sError);
	}
}
class lttxInfo extends Exception{
	public function __construct  ($messageCode){
		$args = func_get_args();
		$messageCode = $args[0];
		package::loadLang(package::$tpl);
		$this->message = package::getLanguageVar($messageCode);
		$argstr = '';
		foreach($args as $i => $arg){
			if($i == 0)
				continue;
			$argstr = ',$args['.$i.']';
		}
		eval("\$this->message = sprintf(\$this->message$argstr);");
	}
}



class lttxLog{
	public function __construct  (){
	}
	public function debug($message = ''){
		$message=mysql_real_escape_string($message);
		$currentuser=0;
		$currenttime = new Date(time());
		$currenttime = $currenttime->getDbTime();
		if(package::$user){
			$currentuser=package::$user->getUserID();
		}
		package::$db->prepare("INSERT INTO `lttx_log` (`userid`, `logdate`, `message`) VALUES (?, ?, ?)")->execute(array($currentuser, $curenttime, $message));
	}
}


class lttxFatalError extends Exception{
	private $_oID = false;
	public function __construct  ($message = '', $package = false){
		package::loadLang(package::$tpl);
		$this->message = package::getLanguageVar('E_fatalErrorOccured');
		$this->message .= '<br /><b>'.nl2br($message).'</b>';
		$this->_log($message, $package);
	}
	private function _log($message, $package){
		package::$db->prepare("INSERT INTO `lttx_error_log` (`package`, `traced`, `backtrace`) VALUES (?, ?, ?)")->execute(array($package, 1, '##' . $this->getFile() . '(' . $this->getLine() . '):' . $message . "\n" . $this->getTraceAsString()));
		$this->_oID = package::$db->lastInsertId();
	}
	public function setTraced($option){
		if(!$this->_oID)return false;
		package::$db->prepare("UPDATE `lttx_error_log` SET `traced` = ? WHERE `ID` = ?")->execute(array($option, $this->_oID));
	}
}
