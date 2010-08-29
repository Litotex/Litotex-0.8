<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample2 extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'sample2';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array();

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'templateSidebarLeft', 0);
                self::_registerHook(__CLASS__, 'templateSidebarRight', 0);
		return true;
    }

    /**
     * Main action
     */
    public function __action_main() {
        return true;
    }

    /**
     * Method of the "tempalteSidebarRight" hook
     * return bool
     */
    public static function __hook_templateSidebarLeft() {
        // print a little Hello World message in the left sidebar
        echo "Hello World left Sidebar!";
        return true;
    }

    /**
     * Method of the "tempalteSidebarRight" hook
     * return bool
     */
    public static function __hook_templateSidebarRight() {
        // print a little Hello World message in the right sidebar
        echo "Hello World right Sidebar!";
        return true;
    }
}
