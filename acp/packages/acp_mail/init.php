<?php
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_acp_mail extends package {
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
    protected $_packageName = 'acp_mail';

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
     * @var option
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
	
		return true;
    }

 	 public function sendMailPlain($mailTo,$mailSubject,$MailMessage){
		
		package::loadLang(package::$tpl,'acp_mail');
		if(!self::$_options)
            self::$_options = new option('acp_mail');
        $this->_initialized = true;
        
		self::$_MailFrom = self::$_options->get('AdminEmail');
		self::$_MailFromName = self::$_options->get('AdminEmailName');
		
		
		if (!self::$_MailFrom || !self::$_MailFromName){
			throw new lttxError('LN_MAIL_CONFIG_ERROR');
			return false;
		}

		$mailHeader ='From: '.self::$_MailFrom."\r\n" ;
		$mailHeader .='Reply-To: '.self::$_MailFrom."\r\n" ;
		$mailHeader .='X-Mailer: Litotex MailModule:/';


		if (!@mail($mailTo, $mailSubject,$MailMessage, $mailHeader)){
			throw new lttxError('LN_MAIL_SEND_ERROR'); 
			return false;
		}else{
			return true;
		}

    }
	
	
}
?>
