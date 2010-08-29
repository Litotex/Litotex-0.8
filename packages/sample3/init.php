<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample3 extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'sample3';

    /**
     * Default template
     * @var string
     */
    protected $_theme = 'table.tpl';

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
        return true;
    }

    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
        // load template
        $this->_theme = 'table.tpl';
        return true;
    }
}
