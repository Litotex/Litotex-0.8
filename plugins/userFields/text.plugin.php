<?php
class plugin_string extends plugin{
	public static $handlerName = 'fields';
	public static $name = 'string';
	public static $availableFunctions = array('getHTML', 'checkValid', 'setContent', 'getContent');
	public static function getHTML($fieldName, $value){
		return '<textarea name="'.$fieldName.'">'.self::getContent($value).'</textarea>';
	}
	public static function checkValid($value){
		return true;
	}
	public static function setContent($value){
		if(!self::checkValid($value))
			return false;
		return $value;
	}
	public static function getContent($value){
		return $value;
	}
}

