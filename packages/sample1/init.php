<?php
/**
 * This is a sample package which displays a little message in
 * the left sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample1 extends package {
    public static $version = '0.8.10';
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
        self::_registerHook(__CLASS__, 'sample1', 0);
        return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'sample1');
    	return true;
    }
    /**
     * Method of the "tempalteSidebarLeft" hook
     * return bool
     */
    public static function __hook_sample1() {
        // print a little Hello World message
        echo '<p><br>Hello World! (sample1)</p>';
        return true;
    }
	public static function  __tpl_sample1() {
        return self::__hook_sample1(2);
    }
    public static function displayDepExample(){
    	return "<p>Hallo Welt! Ich bin eine statische Funktion, die mit Hilfe des Paketmanagers automatisch zur Verfügung gestellt wurde. Viel Spaß! Nebenbei, ich komme aus Sample1!</p>";
    }

}
