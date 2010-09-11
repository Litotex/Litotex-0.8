<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * This class has to be extended by every modul used in Litotex 0.8
 * @author: Jonas Schwbae <jonas.schwabe@gmail.com>
 * @copyright: 2010
 */
abstract class package {
    /**
     * This will keep an instance of the database class for usage in extending classes
     * @var ADODB_mysql
     */
    public static $db;
    /**
     * This will keep an instance of the template class Smarty for usage in extending classes
     * @var Smarty
     */
    public static $tpl;
    /**
     * This will keep an instance of the package and hook manager for usage in extending classes
     * @var packages
     */
    public static $packages;
    /**
     * Session to be used globaly
     * @var session
     */
    public static $session;
    /**
     * User class to be used globaly
     * @var user
     */
    public static $user;
    /**
     * Permission class instance
     * @var perm
     */
    public static $perm;
    /**
     * This var contains every possible methode this class provides, methodes are passed as a get param (?action=xxx)
     * If an unknown method was passed __action_main will be casted. The function to be casted (__action_name) must be availabe.
     * @var array
     */
    protected $_availableActions = array();
    /**
     * This var contains a list of every css file that should be loaded by smarty
     * @var array
     */
    protected static $_cssFiles = array();
    /*
	 * This var contains a list of every JavaScript file that should be loaded by smarty
	 * @var array
    */
    protected static $_jsFiles = array();
    /**
     * This will save the status of the running class, it will be used in the destructor
     * @var bool
     */
    protected $_tplDir = TEMPLATE_DIRECTORY;
    /**
     * Value of return by the __action_* methode casted
     * @var bool
     */
    protected $_returnValue = false;
    /**
     * The name of the package, it has to be set!
     * @var string
     */
    protected $_packageName = 'core';
    /**
     * Template file
     * @var string
     */
    protected $_theme = 'main.tpl';
    /**
     * Show template on destroy
     * @var bool
     */
    protected $_tpl = false;
    /**
     * This function registers the class into the package manager and loads the casted action
     * @return void
     */
    
    public static $dependency = array();
    
    public static $loadDependency = array();
    
    protected static $_dep = array();
    
    public final function __construct() {
    	$this->_tplDir = self::getTplDir();
        if(!isset($_GET['action']))
            $action = 'main';
        else
            $action = $_GET['action'];
        $this->_returnValue = $this->_castAction($action);
        if(!is_bool($this->_returnValue)) {
            $this->_returnValue = (bool)$this->_returnValue;
        }
        self::loadLang(self::$tpl, $this->_packageName);
        $this->setTemplateSettings(self::$tpl, $this->_packageName);
        return;
    }
    public final function success(){
    	return $this->_returnValue;
    }
    /**
     * Displays the template if set to do
     * @return bool
     */
    public final function __destruct() {
        if($this->_tpl) {
            self::$tpl->display(self::getTplDir($this->_packageName) . $this->_theme);
        }
        return true;
    }
    public static function getLanguageVar($var){
    	if(isset(self::$tpl->_config[0]['vars'][$var]))
    		return self::$tpl->_config[0]['vars'][$var];
    	return false;
    } 
    /**
     * This functions checks which actions are available ($_availableActions) and casts the best function for $action
     * return bool (from action function)
     */
    protected final function _castAction($action) {
        if(in_array($action, $this->_availableActions)) {
            $functionName = '__action_' . $action;
            return $this->$functionName();
        }else {
        	if(in_array('main', $this->_availableActions)){
        		$functionName = '__action_main';
        		return $this->$functionName();
        	}
            return false;
        }
    }
    /**
     * This is the main function which is called if no other function is avaialbe
     * It has to be redeclared!
     * @return bool
     */
    abstract public function __action_main();
    /**
     * This function passes a hook to the package manager for further usage.
     * This function is cached so that it should do the job if every hook is registered in registerHooks()
     * @param string $hookname name of the hook to be registered
     * @param int $nParams number of params to be passed, overloading is allowed here
     * @param bool | str $function name of function, used if the function is overloaded
     * @return bool was the hook registered successfully?
     */
    protected static final function _registerHook($class, $hookname, $nParams, $function = false, $file = false, $packageName = false) {
        if(!self::$packages) {
            throw new Exception('The packagemanager was not accessible for this package, register it first.');
            exit();
        }
        $function = (!$function)?$hookname:$function;
        $return = self::$packages->registerHook($class, $hookname, $nParams, $function, $file, $packageName);
        if(!$return)
            throw new Exception('Packagemanager was unable to load hook function "__hook_' . $function . '"');
    }
    /**
     * This function is used to regenerate the hook cache
     * @return bool
     */
    static public function registerHooks(){
    	return true;
    }
    
	static public function registerTplModifications(){
    	return true;
    }
    
	protected static final function _registerTplModification($class, $function, $file = false, $packageName = false) {
        if(!self::$packages) {
            throw new Exception('The packagemanager was not accessible for this package, register it first.');
            exit();
        }
        $return = self::$packages->registerTplModification($class, $function, $file, $packageName);
        if(!$return)
            throw new Exception('Packagemanager was unable to load tplModification function "__tpl_' . $function . '"');
    }
    
    /**
     * This will save a database instance in the root class
     * Attention! Only allowed on package class
     * @return bool
     */
    static public final function setDatabaseClass($db) {
        if(__CLASS__ != 'package')
            return false;
        package::$db = $db;
        return true;
    }
    /**
     * This will save a template instance in the root class
     * Attention! Only allowed on package class
     * @return bool
     */
    public static final function setTemplateClass($tpl) {
        if(__CLASS__ != 'package')
            return false;
        package::$tpl = $tpl;
        self::$tpl->assign('CSS_FILES', self::$_cssFiles);
        self::$tpl->assign('JS_FILES', self::$_jsFiles);
        return true;
    }
    /**
     * This will save a package and hook manager instance in the root class
     * Attention! Only allowed on package class
     * @return bool
     */
    public static final function setPackageManagerClass($packages) {
        if(__CLASS__ != 'package')
            return false;
        package::$packages = $packages;
        return true;
    }
    /**
     * This will save the session instance in the package
     * @param session $session instance
     * @return bool
     */
    public static final function setSessionClass($session) {
        if(__CLASS__ != 'package')
            return false;
        package::$session = $session;
        package::$user = &$session->user;
        return true;
    }
    /**
     * This will save the permission class instance in the package
     * @param perm $perm instance
     * @return bool
     */
    public static final function setPermClass($perm) {
        if(__CLASS__ != 'package')
            return false;
        package::$perm = $perm;
        return true;
    }
    /**
     * This is casted by packagemanger on regenerate cache
     * @param string $class classname to register (need to be same as class name)
     * @return bool
     */
    public static function registerClass($class) {
        return self::$packages->registerClass($class);
    }
    /**
     * Set to true if the template should be showed on destroy
     * @param bool $tpl Tpl policy
     * @return bool
     */
    public final function setTemplatePolicy($tpl) {
    	if($this->_returnValue)
        	$this->_tpl = (bool)$tpl;
        else $this->_tpl = false;
        return true;
    }
    /**
     * Add a new css file to include to the template
     * @param string $href name of css file
     * @param bool $usePackageDir true if not in object context or if you want to use /tpldir/css, else default package template folder will be used
     * @return bool
     */
    public function addCssFile($href, $package = false) {
        self::$_cssFiles[] = self::getCssUrl($package) . $href;
        if(self::$tpl)
            self::$tpl->assign('CSS_FILES', self::$_cssFiles);
        return true;
    }
    /**
     * Adds a new js file to include to the template
     * @param string $href name of js file
     * @param bool $usePackageDir true if not in object context or if you want to use /tpldir/js, else default package template folder will be used
     * @return bool
     */
    public static function addJsFile($href, $package = false) {
        self::$_jsFiles[] = self::getJsUrl($package) . $href;
        if(self::$tpl)
            self::$tpl->assign('JS_FILES', self::$_jsFiles);
        return true;
    }
    /**
     * This will refer the user back to the front page of a package
     * It will prevent from refering to the same page again due to errors by passing an error!
     */
    protected final function _referMain(){
        if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == 'package=' . $this->_packageName)
                throw new Exception('Refer loop... This might be related to a previous error in the source code!');
        header('Location: index.php?package=' . $this->_packageName);
        exit();
    }
    /**
     * Returns the name of the current package
     * @return str
     */
    public final function getPackageName(){
        return $this->_packageName;
    }
    
    public static function registerDependency($dep){
    	self::$_dep = $dep;
    }
    public function getTplDir($package = false){
    	if(!$package)
    		return TEMPLATE_DIRECTORY . self::getTemplate() . '/';
    	else
    		return TEMPLATE_DIRECTORY . self::getTemplate() . '/' . $package . '/';
    }
	public function getTplURL($package = false){
		if(!$package)
    		return TPL_DIRNAME . self::getTemplate() . '/';
    	else
    		return TPL_DIRNAME . self::getTemplate() . '/' . $package . '/';
    }
    public static function getTemplate(){
    	return 'default';
    }
    public function getImgUrl($package = false){
    	return self::getTplURL($package) . IMG_DIR;
    }
	public function getJsUrl($package = false){
    		return self::getTplURL($package) . JS_DIR;
    }
	public function getCssUrl($package = false){
    	return self::getTplURL($package) . CSS_DIR;
    }
	public function getLangPath($package = false){
    	return self::getTplDir($package) . LANG_DIR;
    }
    public static function getLanguage(){
    	return 'de';
    }
	public static function loadLang($tpl, $package = false){
    	if(file_exists(self::getLangPath() . self::getLanguage() . '.lang.php')){
        	$tpl->config_load(self::getLangPath() . self::getLanguage() . '.lang.php');
        } else if(file_exists(self::getLangPath() . 'en' . '.lang.php')){
        	$tpl->config_load(self::getLangPath() . 'en' . '.lang.php');
        }
        if($package && file_exists(self::getLangPath($package) .  self::getLanguage() . '.lang.php')){
        	$tpl->config_load(self::getLangPath($package) . self::getLanguage() . '.lang.php');
        }else if(file_exists(self::getLangPath($package) . 'en' . '.lang.php')){
        	$tpl->config_load(self::getLangPath($package) . 'en' . '.lang.php');
        }
        return true;
    }
    public static function setTemplateSettings($tpl, $package = false){
    	$tpl->assign('CORE_IMG_URL', self::getImgUrl(false));
        $tpl->assign('CORE_CSS_URL', self::getCssUrl(false));
        $tpl->assign('CORE_JS_URL', self::getJsUrl(false));
        if($package){
	        $tpl->assign('IMG_URL', self::getImgUrl($package));
	        $tpl->assign('CSS_URL', self::getCssUrl($package));
	        $tpl->assign('JS_URL', self::getJsUrl($package));
        }
        return true;
    }
    public function getActions(){
    	return $this->_availableActions;
    }
}
