<?php
class plugin_text extends plugin{
	public static $handlerName = 'config';
	public static $name = 'text';
	public static $availableFunctions = array('exists', 'registerElement');
	public static function exists(){
		return true;
	}
	public static function registerElement($name, $settings){
		package::$tpl->assign('cfgElementName', $name);
		$element = new configElement(self::$name);
		if(isset($settings['multiline']) && $settings['multiline'])
			$code = package::$tpl->fetch(dirname(__FILE__) . '/text.multiline.plugin.tpl');
		else
			$code = package::$tpl->fetch(dirname(__FILE__) . '/text.singleline.plugin.tpl');
		$element->setHTML($code);
		return $element;
	}
}