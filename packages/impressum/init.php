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
