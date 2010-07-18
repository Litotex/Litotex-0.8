<?php
/**
 * This is just a dummy class to ensure the building class will be loaded...
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @hooks: None as this class has no features to be serious
 */
class package_core_building extends package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'core_building';
    /**
     * Dependencies, we need quite a lot of them ;)
     * @var array
     */
    public static $dependency = array('core_explore', 'core_territory');
    /**
     * Only loads the building class
     * @see packages/core/classes/package::__action_main()
     * @return bool
     */
	public function __action_main(){
		return (bool)include_once('classes/building.class.php');
	}
	/**
	 * As I wrote above, no hooks ;)
	 * @return bool
	 */
	public static function registerHooks(){
		return true;
	}
}
