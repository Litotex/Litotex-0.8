<?php
class package_main extends package{
    protected $_packageName = 'main';
	public function __action_main(){
            $news = false;
                
		return true;
	}
	public static function registerHooks(){
		return true;
	}
}
