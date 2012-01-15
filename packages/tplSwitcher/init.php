<?php
/*
 * Copyright (c) 2010 Litotex
 * 
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software and
 * associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_tplSwitcher extends Package {
	/**
	 * Package name
	 * @var string
	 */
	protected $_packageName = 'tplSwitcher';
	/**
	 * Avaibilbe actions in this package
	 * @var array
	 */
	protected $_availableActions = array('main','save');
	/**
	 * Register all hooks of this package
	 * @return bool
	 */
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'getTemplateName', 1);
		return true;
	}
	/**
	 * Register all tplMods of this package
	 * @return bool
	 */
	public static function registerTplModifications(){
		self::_registerTplModification(__CLASS__, 'showTemplateSwitch', 'tplSwitcher');
		return true;
	}
	/**
	 * This will display a little box which makes it possible to choose from different templates
	 * @return bool
	 */
	public static function  __tpl_showTemplateSwitch() {
		return self::__hook_showTemplateSwitch(0);
	}
	/**
	 * This will display a little box which makes it possible to choose from different templates
	 * @return bool
	 */
	public static function __hook_showTemplateSwitch() {
		Package::addCssFile('switch.css', 'tplSwitcher');
		$dir = opendir(TEMPLATE_DIRECTORY);
		$tpls = array();
		$option = new Option('tplSwitcher');
		$now = $option->get('tpl');
		if(!$now){
			$option->add('tpl', 'default', 'default');
		}
		while($file = readdir($dir)){
			if($file == '.' || $file == '..'){
				continue;
			}
			if(is_dir(TEMPLATE_DIRECTORY . $file))
			$tpls[] = array($file, ($now == $file)?true:false);
		}
		Package::$tpl->assign('tpls', $tpls);
		Package::$tpl->display(self::getTplDir('tplSwitcher') . 'switch.tpl');
		return true;
	}
	/**
	 * This will write back the template name
	 * @param string $back position to save tplName
	 * @return bool
	 */
	public static function __hook_getTemplateName(&$back){
		$option = new Option('tplSwitcher');
		if(!($var = $option->get('tpl'))){
			$var = 'default';
			$option->add('tpl', $var, $var);
		}
		if(file_exists(TEMPLATE_DIRECTORY . $var))
		$back = $var;
		else return false;
		return true;
	}
	/**
	 * This will actually just refer back to index.php
	 * @see packages/core/classes/package::__action_main()
	 * @return bool
	 */
	public function __action_main(){
		header('Location:index.php');
		return true;
	}
	/**
	 * This will save tpl settings and refers back to index.php
	 * @throws LitotexError
	 * @return bool
	 */
	public function __action_save(){
		if(!isset($_POST['tpl']))
		return $this->__action_main();
		$file = $_POST['tpl'];
		if(!is_dir(TEMPLATE_DIRECTORY . $file)){
			throw new LitotexError('E_tplNotFound');
		}
		$option = new Option('tplSwitcher');
		$option->addIfNExists('tpl', $file, 'default');
		header('Location:index.php');
		return true;
	}
}