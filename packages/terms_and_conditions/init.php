<?php
/**
 * @package terms_and_conditions

 */
class package_terms_and_conditions extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'terms_and_conditions';
	
    /**
     * Default template
     * @var string
     */
    protected $_theme = 'main.tpl';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main');

    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
	
	
		self::$tpl->display(self::getTplDir('terms_and_conditions') . 'main.tpl');
        return true;
    }
	
}
