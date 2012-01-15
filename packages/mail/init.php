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
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_mail extends Package {
     private static $_version = '0.8.01';
	 /**
     * Is this instance initialized?
     * @var bool
     */
    private $_initialized = false;
	
	/**
     * Package name
     * @var string
     */
    protected $_packageName = 'mail';

	 /**
     * Current Admin Mail
     * @var string
     */
    private static $_MailFrom = false;
	

	 /**
     * Current Admin Mail name
     * @var string
     */
    private static $_MailFromName = false;
	
	
    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main');

	    /**
     * Contains preferences (auto inform etc)
     * @var Option
     */
    private static $_options = false;
	

    
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'mail', 0);
        return true;
    }
  
	
    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
        return true;
    }
    public static function __hook_mail() {
		Package::$tpl->display(self::getTplDir('sample3') . 'table.tpl');
		return true;
    }

 	 public function sendMailPlain($mailTo,$mailSubject,$MailMessage){
		
		Package::loadLang(Package::$tpl,'mail');
		if(!self::$_options)
            self::$_options = new Option('mail');
        $this->_initialized = true;
        
		self::$_MailFrom = self::$_options->get('AdminEmail');
		self::$_MailFromName = self::$_options->get('AdminEmailName');
		
		
		if (!self::$_MailFrom || !self::$_MailFromName){
			throw new LitotexError('LN_MAIL_CONFIG_ERROR');
			return false;
		}

		$mailHeader ='From: '.self::$_MailFrom."\r\n" ;
		$mailHeader .='Reply-To: '.self::$_MailFrom."\r\n" ;
		$mailHeader .='X-Mailer: Litotex MailModule:/';


		if (!@mail($mailTo, $mailSubject,$MailMessage, $mailHeader)){
			throw new LitotexError('LN_MAIL_SEND_ERROR'); 
			return false;
		}else{
			return true;
		}

    }
	
	
}
?>
