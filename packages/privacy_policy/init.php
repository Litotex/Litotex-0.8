<?php
/**
 * @package privacy_policy

 */
class package_privacy_policy extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'privacy_policy';
	
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
	
	
		self::$tpl->display(self::getTplDir('privacy_policy') . 'main.tpl');
        return true;
    }
	
}
