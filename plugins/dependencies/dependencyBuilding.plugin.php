<?php
class plugin_dependencyBuilding extends plugin{
	public static $handlerName = 'dependencies';
	public static $name = 'Ressource';
	public static $availableFunctions = array('checkDependency');
	public static function checkDependency(territory $territory, $preferences){
		if(!self::checkPreferences($preferences))return false;
		$level = $territory->getBuildingLevel($preferences['sourceID']);
		if($level >= $preferences['minLevel'])
			return true;
		return false;
	}
	public static function checkPreferences($preferences){
		if(!isset($preferences['sourceID']) || !isset($preferences['minLevel']))
			return false;
		return true;
	}
}