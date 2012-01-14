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
		if(DEVDEBUG == true){
			$this->message .= "<br /><b>DEVDEBUG active</b><br />";
			foreach ($this->getTrace() as $trace){
				@$this->message .= '<p>' . $trace['class'] . ':' . $trace['function'] . ': ' . $trace['file'] . ':' . $trace['line'] . '</p>';
			}
		}
	}
}
class lttxDBError extends lttxError{
	public function __construct  (){
		$code = package::$pdb->errorInfo();
		$sError = 'No Error!';
		if($code[2] != NULL)
			$sError = $code[2];
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
		$currentuser=0;
		$currenttime = new Date(time());
		$currenttime = $currenttime->getDbTime();
		if(package::$user){
			$currentuser=package::$user->getUserID();
		}
		package::$pdb->prepare("INSERT INTO `lttx".package::$pdbn."_log` (`userid`, `logdate`, `message`) VALUES (?, ?, ?)")->execute(array($currentuser, $currenttime,$message));
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
		package::$pdb->prepare("INSERT INTO `lttx".package::$pdbn."_error_log` (`package`, `traced`, `backtrace`) VALUES (?, ?, ?)")->execute(array($package, 1, '##' . $this->getFile() . '(' . $this->getLine() . '):' . $message . "\n" . $this->getTraceAsString()));
		$this->_oID = package::$pdb->lastInsertId();
	}
	public function setTraced($option){
		if(!$this->_oID)return false;
		package::$pdb->prepare("UPDATE `lttx".package::$pdbn."_error_log` SET `traced` = ? WHERE `ID` = ?")->execute(array($option, $this->_oID));
	}
}
