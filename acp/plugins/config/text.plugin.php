<?php
class plugin_text extends plugin{
	public static $handlerName = 'config';
	public static $name = 'text';
	public static $availableFunctions = array('exists', 'registerElement');
	public static function exists(){
		return true;
	}
	public static function registerElement($settings){
		$element = new configElement(self::$name);
		$element->setHTML('<b>Hello World :)</b>');
		return $element;
	}
}