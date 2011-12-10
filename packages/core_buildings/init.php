<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once('classes/building.class.php');
/**
 * This is just a dummy class to ensure the building class will be loaded...
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @hooks: None as this class has no features to be serious
 */
class package_core_buildings extends package{
	protected $_availableActions = array('main');
	/**
	 * Name of the module, please do not change this!
	 * @var string
	 */
    protected $_packageName = 'core_buildings';
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
		if(!package::$user)return false;
		$territory = territory::getUserTerritories(package::$user);
		self::$tpl->assign("buildings", $territory[0]->getBuildings());
		$buildings = $territory[0]->getBuildings();
		$territory[0]->increaseBuildingLevel(1);
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