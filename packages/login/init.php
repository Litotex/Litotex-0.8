<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Litotex Team
 * @copyright: 2010
 */
class package_login extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'login';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main');

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'showLoginBox', 1);
 		return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showLoginBox');
    	return true;
    }
    /**
     * Main action displays a table in content area
     */
	public static function __hook_showLoginBox() {
	   package::addCssFile('login.css');
        $tpl = new Smarty();
        $tpl->compile_dir = TEMPLATE_COMPILATION;
		self::loadLang($tpl, 'table');
		$tpl->assign('CSS_LOGIN_FILE', self::getTplURL().'login/css/login.css');
		$tpl->display(self::getTplDir() . '/login/login_template.tpl');
        return true;
    } 
	 
    public function __action_main() {
        return true;
    }
}
