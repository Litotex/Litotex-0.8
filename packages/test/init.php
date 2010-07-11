<?php
class package_test extends package{
	protected $_availableActions = array('nene');
	public function __action_main(){
		echo '<p>Here we go :D Denn ich bin ein normales Modul ;)</p>';
		return true;
	}
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'test', 1);
		return true;
	}
	public static function __hook_test(&$i){
		$i++;
		echo '<p>Ich bin ein Hook</p>';
	}
}