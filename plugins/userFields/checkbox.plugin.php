<?php
class plugin_checkbox extends plugin{
	public static $handlerName = 'userFields';
	public static $name = 'checkbox';
	public static $availableFunctions = array('getHTML', 'checkValid', 'setContent', 'getContent');
	public static function getHTML(userField $field, user $user){
                return '<input type="checkbox" value="'.self::getContent($user->getUserFieldData($field->ID)).'" name="userfield['.$user->getUserID().']['.$field->ID.']" />';
	}
	public static function checkValid($value){
		return true;
	}
	public static function setContent($value){
		if(!self::checkValid($value))
			return false;
		return ($value)?true:false;
	}
	public static function getContent($value){
		return ($value)?true:false;
	}
        public static function validateContent($value){
            return true;
        }
}

