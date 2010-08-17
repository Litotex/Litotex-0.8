<?php
class plugin_buildingRessource extends plugin{
	public static $handlerName = 'buildings';
	public static $name = 'Ressource';
	public static $availableFunctions = array('increaseLevel', 'checkPrefs');
	public static function increaseLevel(territory $territory, $newLevel, $pluginPrefs, $buildingID){
		if(!self::checkPrefs($pluginPrefs))
			return false;
		if(!math::verifyFormula($pluginPrefs['formula']))
		return false;
		$formula = math::replaceX($pluginPrefs['formula'], $newLevel);
		$valuePerHour = math::calculateString($formula);
		$value = $valuePerHour / 3600 * RESSOURCE_UPDATE_INTERVAL;
		cron::addIfNotExists('ressource' . $territory->getID() . '_' . $buildingID . '_' . $pluginPrefs['resID'], time(), RESSOURCE_UPDATE_INTERVAL, 'simpleAddition', array($pluginPrefs['resID'], $value, '$intNum', true), array('core_buildings'), $territory->getUser()->getUserID(), false, $territory->getRessources());
		return true;
	}
	public static function checkPrefs($pluginPrefs){
		if(!isset($pluginPrefs['resID']) || !isset($pluginPrefs['formula']))
		return false;
		return true;
	}
}