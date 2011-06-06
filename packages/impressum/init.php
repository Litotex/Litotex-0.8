<?php
/**
 * @package impressum

 */
class package_impressum extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'impressum';

	private static $_options = false;
	
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
		if(!self::$_options)
            self::$_options = new option('Impressum');
        $this->_initialized = true;
	
	
		
		$ImpressumMail = self::$_options->get('ImpressumMail');
		$ImpressumName = self::$_options->get('ImpressumName');
		$ImpressumStreet = self::$_options->get('ImpressumStreet');
		$ImpressumCity = self::$_options->get('ImpressumCity');
		$ImpressumTel = self::$_options->get('ImpressumTel');
		$ImpressumFax = self::$_options->get('ImpressumFax');

        self::$tpl->assign('ImpressumMail', $ImpressumMail );
		self::$tpl->assign('ImpressumName', $ImpressumName );
		self::$tpl->assign('ImpressumStreet', $ImpressumStreet );
		self::$tpl->assign('ImpressumCity', $ImpressumCity );
		self::$tpl->assign('ImpressumTel', $ImpressumTel );
		self::$tpl->assign('ImpressumFax', $ImpressumFax );
 
	
		self::$tpl->display(self::getTplDir('impressum') . 'main.tpl');
        return false;
    }
	
}
