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
 * This class has to be extended by every modul used in Litotex 0.8
 * @author: Jonas Schwbae <jonas.schwabe@gmail.com>
 * @copyright: 2010
 */
abstract class Package {

    /**
     * This will keep an instance of the database class for usage in extending classes
     * @var PDO
     */
    public static $pdb;
    
    public static $pdbn = 1;
    /**
     * This will keep an instance of the template class Smarty for usage in extending classes
     * @var Smarty
     */
    public static $tpl;
    /**
     * This will keep an instance of the package and hook manager for usage in extending classes
     * @var PackageManager
     */
    public static $packages;
    /**
     * Session to be used globaly
     * @var Session
     */
    public static $session;
    /**
     * User class to be used globaly
     * @var User
     */
    public static $user;
    /**
     * Permission class instance
     * @var Permission
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
    /**
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
     * Packages to be loaded but not to be initialized when the package in favour is loaded
     * @var array
     */
    public static $dependency = array();
    /**
     * Packages to be loaded and to be initlized + passed to the constructor when the package in favour is loaded
     * @var array
     */
    public static $loadDependency = array();
    /**
     * Loaded and initialized objects set which one in $loadDependency 
     * @var array
     */
    protected $_dep = array();
    /**
     * Template to be loaded
     * @var string
     */
    protected $_defaultTpl = 'default';
    /**
     * This var contains the version of the actual package (Will be used to check for updates, too)
     * @var string
     */
    public static $version = '0.0.0';
    /**
     * contains the action of the package being loaded
     * @var string
     */
    protected static $_action = NULL;

    public static function getAction () {
        if (!is_null(self::$_action))
            return self::$_action;
        if (!isset($_GET['action']))
            self::$_action = 'main';
        else
            self::$_action = $_GET['action'];
        return self::$_action;
    }

    /**
     * This function registers the class into the package manager and loads the casted action
     * @return void
     */
    public final function __construct($init = true, $dep = array()) {
        $this->_dep = $dep;
        if (!$init)
            return;
        $this->_tplDir = self::getTplDir();
        $this->setTemplateSettings(self::$tpl, $this->_packageName);
        $this->_returnValue = $this->_castAction(self::getAction());
        if (!is_bool($this->_returnValue)) {
            $this->_returnValue = (bool) $this->_returnValue;
        }
        return;
    }

    public final function success() {
        return $this->_returnValue;
    }

    /**
     * Displays the template if set to do
     * @return bool
     */
    public final function displayTpl() {
        if ($this->_tpl) {
            if (file_exists(self::getTplDir($this->_packageName) . $this->_theme)) {
                self::$tpl->display(self::getTplDir($this->_packageName) . $this->_theme);
            } else {
                self::$tpl->display(self::getTplDir($this->_packageName, 'default') . $this->_theme);
            }
        }
        return true;
    }

    /**
     * Returns a language variable
     * @param string $var key to lookup
     * @return string language var
     */
    public static function getLanguageVar($var) {
        if (!self::$tpl)
            return false;
        return self::$tpl->getConfigVars($var);
    }

    /**
     * This functions checks which actions are available ($_availableActions) and casts the best function for $action
     * return bool (from action function)
     */
    protected final function _castAction($action) {
        if (in_array($action, $this->_availableActions)) {
            $functionName = '__action_' . $action;
        } else {
            if (in_array('main', $this->_availableActions)) {
                $action = 'main';
                $functionName = '__action_main';
            } else
                return false;
            if (!self::$perm->checkPerm($this->_packageName, $action, 'package_' . $this->_packageName)) {
                throw new LitotexError('E_noPermission');
            }
        }
        if (!self::$perm->checkPerm($this, $action, 'package_' . $this->_packageName)) {
            throw new LitotexError('E_noPermission');
        }
        $this->runtime();
        self::$packages->callHook('ah_' . $this->_packageName . '_before_' . $action, array());
        $result = $this->$functionName();
        if($result){
        	self::$packages->callHook('ah_' . $this->_packageName . '_after_' . $action, array());
        }
        return $result;
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
        if (!self::$packages) {
            trigger_error('The packagemanager was not accessible for this package, register it first.', E_USER_ERROR);
            exit();
        }
        $function = (!$function) ? $hookname : $function;
        $return = self::$packages->registerHook($class, $hookname, $nParams, $function, $file, $packageName);
        if (!$return)
            trigger_error('Packagemanager was unable to load hook function "__hook_' . $function . '"', E_USER_ERROR);
    }

    /**
     * This function is used to regenerate the hook cache
     * @return bool
     */
    static public function registerHooks() {
        return true;
    }

    /**
     * This function is used to regenerate the tplMod cache
     * @return bool
     */
    static public function registerTplModifications() {
        return true;
    }

    /**
     * This function passes a tplMod to the package manager for further usage.
     * This function is cached so that it should do the job if every hook is registered in registerHooks()
     * @param string $hookname name of the hook to be registered
     * @param bool | str $function name of function, used if the function is overloaded
     * @return bool was the hook registered successfully?
     */
    protected static final function _registerTplModification($class, $function, $packageName, $file = false) {
        if (!self::$packages) {
            trigger_error('The packagemanager was not accessible for this package, register it first.', E_USER_ERROR);
            exit();
        }
        $return = self::$packages->registerTplModification($class, $function, $file, $packageName);
        if (!$return)
            trigger_error('Packagemanager was unable to load tplModification function "__tpl_' . $function . '"', E_USER_ERROR);
    }

    /**
     * This will save a database instance in the root class
     * Attention! Only allowed on package class
     * @return bool
     */
    static public final function setDatabaseClass($db) {
        if (__CLASS__ != 'Package')
            return false;
        Package::$pdb = $db;
        return true;
    }

    /**
     * This will save a template instance in the root class
     * Attention! Only allowed on package class
     * @return bool
     */
    public static final function setTemplateClass($tpl) {
        if (__CLASS__ != 'Package')
            return false;
        Package::$tpl = $tpl;
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
        if (__CLASS__ != 'Package')
            return false;
        Package::$packages = $packages;
        return true;
    }

    /**
     * This will save the session instance in the package
     * @param Session $session instance
     * @return bool
     */
    public static final function setSessionClass($session) {
        if (__CLASS__ != 'Package')
            return false;
        Package::$session = $session;
        Package::$user = &$session->user;
        return true;
    }

    /**
     * This will save the permission class instance in the package
     * @param Permission $perm instance
     * @return bool
     */
    public static final function setPermClass($perm) {
        if (__CLASS__ != 'Package')
            return false;
        Package::$perm = $perm;
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
        if ($this->_returnValue)
            $this->_tpl = (bool) $tpl;
        else
            $this->_tpl = false;
        return true;
    }

    /**
     * Add a new css file to include to the template
     * @param string $href name of css file
     * @param bool $usePackageDir true if not in object context or if you want to use /tpldir/css, else default package template folder will be used
     * @return bool
     */
    public static function addCssFile($href, $package = false) {
        if (file_exists(self::getCssDir($package) . $href)) {
            if (!in_array(self::getCssUrl($package) . $href, self::$_cssFiles))
                self::$_cssFiles[] = self::getCssUrl($package) . $href;
        }else {
            if (!in_array(self::getCssUrl($package, 'default') . $href, self::$_cssFiles))
                self::$_cssFiles[] = self::getCssUrl($package, 'default') . $href;
        }
        if (self::$tpl)
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
        if (file_exists(self::getJsDir($package) . $href)) {
            if (!in_array(self::getJsUrl($package) . $href, self::$_jsFiles))
                self::$_jsFiles[] = self::getJsUrl($package) . $href;
        }else {
            if (!in_array(self::getJsUrl($package, 'default') . $href, self::$_jsFiles))
                self::$_jsFiles[] = self::getJsUrl($package, 'default') . $href;
        }
        if (self::$tpl)
            self::$tpl->assign('JS_FILES', self::$_jsFiles);
        return true;
    }

    /**
     * Adds a new JS file with no relations to a package, just type the url to use
     * @param str $href
     * @return bool
     */
    public static function addNonPackageJsFile($href) {
        if (!in_array($href, self::$_jsFiles))
            self::$_jsFiles[] = $href;
        if (self::$tpl)
            self::$tpl->assign('JS_FILES', self::$_jsFiles);
        return true;
    }

    /**
     * This will refer the user back to the front page of a package
     * It will prevent from refering to the same page again due to errors by passing an error!
     */
    protected final function _referMain() {
        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == 'package=' . $this->_packageName)
            throw new Exception('Refer loop... This might be related to a previous error in the source code!');
        header('Location: index.php?package=' . $this->_packageName);
        exit();
    }

    /**
     * Returns the name of the current package
     * @return str
     */
    public final function getPackageName() {
        return $this->_packageName;
    }

    public static function getTplDir($package = false, $tpl = false) {
        if (!$package) {
            if (is_dir(TEMPLATE_DIRECTORY . self::getTemplate($tpl))) {
                return TEMPLATE_DIRECTORY . self::getTemplate($tpl) . '/';
            } else {
                return TEMPLATE_DIRECTORY . self::getTemplate('default') . '/';
            }
        } else {
            if (is_dir(TEMPLATE_DIRECTORY . self::getTemplate($tpl) . '/' . $package . '/')) {
                return TEMPLATE_DIRECTORY . self::getTemplate($tpl) . '/' . $package . '/';
            } else {
                return TEMPLATE_DIRECTORY . self::getTemplate('default') . '/' . $package . '/';
            }
        }
    }

    public static function getTplURL($package = false, $tpl = false) {
        if (!$package) {
            if (is_dir(TEMPLATE_DIRECTORY . self::getTemplate($tpl))) {
                return TPL_DIRNAME . self::getTemplate($tpl) . '/';
            } else {
                return TPL_DIRNAME . self::getTemplate('default') . '/';
            }
        } else {
            if (is_dir(TEMPLATE_DIRECTORY . self::getTemplate($tpl) . '/' . $package . '/')) {
                return TPL_DIRNAME . self::getTemplate($tpl) . '/' . $package . '/';
            } else {
                return TPL_DIRNAME . self::getTemplate('default') . '/' . $package . '/';
            }
        }
    }

    public static function getTemplate($tpl = false) {
        if ($tpl)
            return $tpl;
        $return = 'default';
        self::$packages->callHook('getTemplateName', array(&$return));
        return $return;
    }

    public static function getImgUrl($package = false, $tpl = false) {
        return self::getTplURL($package, $tpl) . IMG_DIR;
    }

    public static function getJsUrl($package = false, $tpl = false) {
        return self::getTplURL($package, $tpl) . JS_DIR;
    }

    public static function getCssUrl($package = false, $tpl = false) {
        return self::getTplURL($package, $tpl) . CSS_DIR;
    }

    public static function getImgDir($package = false, $tpl = false) {
        return self::getTplDir($package, $tpl) . IMG_DIR;
    }

    public static function getJsDir($package = false, $tpl = false) {
        return self::getTplDir($package, $tpl) . JS_DIR;
    }

    public static function getCssDir($package = false, $tpl = false) {
        return self::getTplDir($package, $tpl) . CSS_DIR;
    }

    public static function getLangPath($package = false, $tpl = false) {
        if (is_dir(self::getTplDir($package, $tpl) . LANG_DIR)) {
            return self::getTplDir($package, $tpl) . LANG_DIR;
        } else {
            return self::getTplDir($package, 'default') . LANG_DIR;
        }
    }

	 /**
     * returns the path to order  'files'
     * @param string $package name of package
     * @param bool $fronted is true (default) it is the frontend files-folder, else ACP Files folder
     * @return Path
     */
    public function getFilesDir($package=false,$fronted=true) {
		$return_path ='';
		if ($fronted==true){
			if (!$package)
				$return_path= LITO_FRONTEND_ROOT.'files/'.$this->_packageName;
			else
				$return_path= LITO_FRONTEND_ROOT.'files/'.$package;
		}else{
			if (!$package)
				$return_path= LITO_ROOT.'files/'.$this->_packageName;
			else
				$return_path= LITO_ROOT.'files/'.$package;
		}		
	return  $return_path;
	}		
	 /**
     * returns the URL to order  'files'
     * @param string $package name of package
     * @param bool $fronted is true (default) it is the frontend files-folder-URL, else ACP Files folder URL
     * @return Path
     */
    public function getFilesURL($package=false,$fronted=true) {
        $return_path ='';
		if ($fronted==true){
			if (!$package)
				$return_path= LITO_FRONTEND_URL.'files/'.$this->_packageName;
			else
				$return_path= LITO_FRONTEND_URL.'files/'.$package;
		}else{
			if (!$package)
				$return_path= LITO_URL.'files/'.$this->_packageName;
			else
				$return_path= LITO_URL.'files/'.$package;
		}
		return  $return_path;
	}
	
	
    public static function getLanguage() {
        return 'de';
    }

    public static function loadLang($tpl, $package = false) {
        if (!is_a($tpl, 'Smarty'))
            return false;
        if (file_exists(self::getLangPath() . self::getLanguage() . '.lang.php')) {
            $tpl->configLoad(self::getLangPath() . self::getLanguage() . '.lang.php');
        } else if (file_exists(self::getLangPath() . 'en' . '.lang.php')) {
            $tpl->configLoad(self::getLangPath() . 'en' . '.lang.php');
        }
        if ($package && file_exists(self::getLangPath($package) . self::getLanguage() . '.lang.php')) {
            $tpl->configLoad(self::getLangPath($package) . self::getLanguage() . '.lang.php');
        } else if (file_exists(self::getLangPath($package) . 'en' . '.lang.php')) {
            $tpl->configLoad(self::getLangPath($package) . 'en' . '.lang.php');
        } else {
            return false;
        }
        return true;
    }

    public static function loadNonPackageLang($tpl, $href) {
        if (!is_a($tpl, 'Smarty'))
            return false;
        if (file_exists($href . '.' . self::getLanguage() . '.lang.php')) {
            $tpl->configLoad($href . '.' . self::getLanguage() . '.lang.php');
        } else {
            $tpl->configLoad($href . '.en' . '.lang.php');
        }
    }

    public static function setTemplateSettings($tpl, $package = false) {
        if (is_dir(self::getImgDir(false))) {
            $tpl->assign('CORE_IMG_URL', self::getImgUrl(false));
        } else {
            $tpl->assign('CORE_IMG_URL', self::getImgUrl(false, 'default'));
        }
        if (is_dir(self::getCssDir(false))) {
            $tpl->assign('CORE_CSS_URL', self::getCssUrl(false));
        } else {
            $tpl->assign('CORE_CSS_URL', self::getCssUrl(false, 'default'));
        }
        if (is_dir(self::getJsDir(false))) {
            $tpl->assign('CORE_JS_URL', self::getJsUrl(false));
        } else {
            $tpl->assign('CORE_JS_URL', self::getJsUrl(false, 'default'));
        }
        if ($package) {
            if (is_dir(self::getImgDir($package))) {
                $tpl->assign('IMG_URL', self::getImgUrl($package));
            } else {
                $tpl->assign('IMG_URL', self::getImgUrl($package, 'default'));
            }
            if (is_dir(self::getCssDir($package))) {
                $tpl->assign('CSS_URL', self::getCssUrl($package));
            } else {
                $tpl->assign('CSS_URL', self::getCssUrl($package, 'default'));
            }
            if (is_dir(self::getJsDir($package))) {
                $tpl->assign('JS_URL', self::getJsUrl($package));
            } else {
                $tpl->assign('JS_URL', self::getJsUrl($package, 'default'));
            }
            $tpl->assign('PACKAGE_DIR', MODULES_DIRECTORY . $package . '/');
            $tpl->assign('TPL_DIR', self::getTplDir($package = $package) . '/');
        }
        return true;
    }

    public function getActions() {
        return $this->_availableActions;
    }


    public static function debug($message = '', $priority = LOG_LEVEL) {
        return (Logger::debug($message, $priority));
    }

    /**
     * This function will be called automaticly before every other __action_method is used on a package
     * It can be empty as well
     * @return void
     */
    public function runtime() {
        
    }

}
