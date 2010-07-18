<?php
/**
 * This package is like a dashboard, so a lot of information is generated by hooks
 * @todo Almost everything
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @hooks None (yet)
 */
class package_main extends package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'main';
    /**
     * No dependencies because they are loaded automatic by hook's
     * @var array
     */
    public static $dependency = array();
	public function __action_main(){
		return true;
	}
	public static function registerHooks(){
		return true;
	}
}
