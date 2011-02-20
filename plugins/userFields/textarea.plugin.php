<?php
class plugin_textarea extends plugin{
	public static $handlerName = 'userFields';
	public static $name = 'textarea';
	public static $availableFunctions = array('getHTML', 'checkValid', 'setContent', 'getContent');
	public static function getHTML(userField $field, user $user){
		return '<textarea name="userfield['.$user->getUserID().']['.$field->ID.']">'.self::getContent($user->getUserFieldData($field->ID)).'</textarea>';
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

