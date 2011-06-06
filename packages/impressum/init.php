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
            self::$_options = new option('impressum');
        $this->_initialized = true;
	
	
		
		$ImpressumMail = self::$_options->get('ImpressumMail', 'mustermann@musterfirma.de');
		$ImpressumName = self::$_options->get('ImpressumName', 'Max Mustermann');
		$ImpressumStreet = self::$_options->get('ImpressumStreet', 'Musterstraße 111');
		$ImpressumCity = self::$_options->get('ImpressumCity', '90210 Musterstadt');
		$ImpressumTel = self::$_options->get('ImpressumTel', '+49 (0) 123 44 55 66');
		$ImpressumFax = self::$_options->get('ImpressumFax', '+49 (0) 123 44 55 99');

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
