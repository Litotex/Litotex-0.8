<?php
class lttxError extends Exception{
	public function __construct  ($messageCode = ''){
		package::loadLang(package::$tpl);
		$this->message = package::getLanguageVar($messageCode);
	}
}