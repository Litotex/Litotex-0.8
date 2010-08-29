<?php
/**
 * This is a sample package which displays a little message in
 * the left sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample1 extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'sample1';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array();

    /**
     * Main action
     */
    public function __action_main() {
        return true;
    }

    /**
     * Register all hooks of this package
     * @return bool
     */
    public static function registerHooks(){
        self::_registerHook(__CLASS__, 'templateSidebarLeft', 0);
        return true;
    }

    /**
     * Method of the "tempalteSidebarLeft" hook
     * return bool
     */
    public static function __hook_templateSidebarLeft() {
        // print a little Hello World message
        echo 'Hello World!';
        return true;
    }
}