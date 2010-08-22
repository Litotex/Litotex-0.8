<?php
include_once('classes/explore.class.php');
/**
 * This is just a dummy class to ensure the building class will be loaded...
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @hooks: None as this class has no features to be serious
 */
class package_core_explore extends package{
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'core_explore';
    /**
     * Dependencies, we need quite a lot of them ;)
     * @var array
     */
    public static $dependency = array('core_ressource', 'core_territory');
    /**
     * Only loads the building class
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
//		self::_registerHook('plugin_buildingRessource', 'manipulateBuildingCost', 1, 'manipulateBuildingCost', LITO_PLUGIN_ROOT . 'buildings/buildingRessource.plugin.php', 'core_buildings');
		return true;
	}
}