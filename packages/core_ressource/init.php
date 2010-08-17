<?php
include_once('classes/ressource.class.php');
/**
 * This is just a dummy class to ensure the ressource class will be loaded...
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @hooks: None as this class has no features to be serious
 */
class package_core_ressource extends package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'core_ressource';
    /**
     * Dependencies, we only need the core package which will be loaded automaticly
     * @var array
     */
    public static $dependency = array();
    /**
     * Only loads the ressource class
     * @see packages/core/classes/package::__action_main()
     * @return bool
     */
	public function __action_main(){
		return true;
	}
	/**
	 * As I wrote above, no hooks ;)
	 * @return bool
	 */
	public static function registerHooks(){
		return true;
	}
}
