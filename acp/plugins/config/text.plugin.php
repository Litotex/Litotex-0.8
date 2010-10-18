<?php
function checkSet($array, $key, $default, $values = array()){
	if(isset($array[$key]) && (count($values) == 0 || in_array($array[$key], $values)))
		return $array;
	$array[$key] = $default;
	return $array;
}
class plugin_text extends plugin{
	public static $handlerName = 'config';
	public static $name = 'text';
	public static $availableFunctions = array('exists', 'registerElement', 'cleanSettings', 'checkInput', 'cleanInput');
	public static function exists(){
		return true;
	}
	public static function registerElement($name, $settings, $defaultValue){
		package::$tpl->assign('cfgElementName', $name);
		package::$tpl->assign('cfgElementSettings', $settings);
		package::$tpl->assign('cfgElementDefaultValue', $defaultValue);
		package::loadNonPackageLang(package::$tpl, LITO_ROOT . LITO_PLUGIN_DIRNAME . 'config/' . 'text.' . $settings['type'].'.plugin');
		$element = new configElement(self::$name, $name, $settings);
		$code = package::$tpl->fetch(dirname(__FILE__) . '/text.'.$settings['type'].'.plugin.tpl');
		package::addNonPackageJsFile(LITO_URL . LITO_PLUGIN_DIRNAME . 'config/' . 'text.' . $settings['type'].'.plugin.js');
		$element->setHTML($code);
		return $element;
	}
	public static function cleanSettings($settings){
		$settings = checkSet($settings, 'type', 'singleline', array('singleline', 'multiline', 'wysiwyg'));
		$settings = checkSet($settings, 'width', 20);
		$settings = checkSet($settings, 'height', 10);
		$settings = checkSet($settings, 'allowHTML', false, array(true, false)); //TODO
		$settings = checkSet($settings, 'autoReplace', array());
		$settings = checkSet($settings, 'maxLength', 0);
		$settings = checkSet($settings, 'allowedChars', array()); //TODO Need to be worked on ;)
		return $settings;
	}
	public static function checkInput($input, $settings){
		$len = strlen($input);
		package::loadNonPackageLang(package::$tpl, LITO_ROOT . LITO_PLUGIN_DIRNAME . 'config/' . 'text.' . $settings['type'].'.plugin');
		if($len != 0 && $len > $settings['maxLength'])
			throw new lttxError('E_tooMuchSigns');
		if(count($settings['allowedChars']) > 0){
			$regex = '/^[';
			foreach ($settings['allowedChars'] as $char){
				$regex .= '\\' . $char[0];
			}
			$regex .= ']*$/';
			if(!preg_match($regex, $input))
				throw new lttxError('E_notAllowedSigns');
		}
		return true;
	}
	public static function cleanInput($input, $settings){
		if(!$settings['allowHTML'])
			$input = htmlspecialchars($input);
		foreach($settings['autoReplace'] as $from => $to){
			$input = str_replace($from, $to, $input);
		}
		return $input;
	}
}