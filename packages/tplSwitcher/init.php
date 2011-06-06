<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_tplSwitcher extends package {
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
		package::addCssFile('switch.css', 'tplSwitcher');
		$dir = opendir(TEMPLATE_DIRECTORY);
		$tpls = array();
		$option = new option('tplSwitcher');
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
		package::$tpl->assign('tpls', $tpls);
		package::$tpl->display(self::getTplDir('tplSwitcher') . 'switch.tpl');
		return true;
	}
	/**
	 * This will write back the template name
	 * @param string $back position to save tplName
	 * @return bool
	 */
	public static function __hook_getTemplateName(&$back){
		$option = new option('tplSwitcher');
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
	 * @throws lttxError
	 * @return bool
	 */
	public function __action_save(){
		if(!isset($_POST['tpl']))
		return $this->__action_main();
		$file = $_POST['tpl'];
		if(!is_dir(TEMPLATE_DIRECTORY . $file)){
			throw new lttxError('E_tplNotFound');
		}
		$option = new option('tplSwitcher');
		$option->addIfNExists('tpl', $file, 'default');
		header('Location:index.php');
		return true;
	}
}