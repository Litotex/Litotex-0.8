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
/* Log-Levels:
 * LOG_EMERG	system is unusable					0
 * LOG_ALERT	action must be taken immediately                        1
 * LOG_CRIT		critical conditions				2
 * LOG_ERR		error conditions				3
 * LOG_WARNING	warning conditions					4
 * LOG_NOTICE	normal, but significant, condition                      5
 * LOG_INFO		informational message				6
 * LOG_DEBUG	debug-level message					7
 */

class Logger {
    
    /*
     * will be filled with the current timestamp
     */
    private static $_logFile = NULL;
    
	public static function debug($message = '', $priority = LOG_WARNING) {
    	if ($priority > LOG_LEVEL)
    		return false;
    	
    	// Package details - Package detection is a Workaround!
    	$package=$_GET['package'];
		$action=Package::getAction();
    	// get User
        $currentUser = NULL;
        if (Package::$user)
            $currentUser = Package::$user->getUserID();
        
        // get Time
        $date = new Date(time());
        $currentTime = $date->getDbTime();
        
        // when Db isnt working write to disk
        if (!Package::$pdb->prepare("INSERT INTO `lttx".Package::$pdbn."_log` (`userid`, `logdate`, `message`, `log_type`,`package`,`package_action`) VALUES (?, ?, ?, ?, ?, ?)")->execute(array($currentUser, $currentTime, $message, $priority,$package,$action)))
            self::debugDisk($message);
    }

    private static function _initDisk(){
    	if(!self::$_logFile){
    		$curdate=date('c', time());
			$curdate = str_replace(":", "_", $curdate);

    		self::$_logFile = fopen(LITO_FRONTEND_ROOT.'log/'.$curdate. '_' . sha1(microtime()), 'w');
    		if(!self::$_logFile)
    			throw new LitotexFatalError('Could not log to disk.');
    	}
    }
    
    public static function debugDisk($message = '', $priority = LOG_WARNING){
        if ($priority > LOG_LEVEL)
    		return false;
    	
    	// Package details - Package detection is a Workaround!
    	$package=$_GET['package'];
		$action=Package::getAction();
    	// get User
        $currentUser = NULL;
        if (Package::$user)
            $currentUser = Package::$user->getUserID();
        
        // get Time
        $date = new Date(time());
        $currentTime = $date->getDbTime();
        
    	self::_initDisk();
    	fwrite(self::$_logFile, $priority . ':' . $currentTime . ':' . $package . ':' . $action . ':' . $currentUser . ':' . $message . "\n");
    }
    
    public static function debugStartup($message){
    	if(DEVDEBUG){
    		self::debugDisk('STARTUP ' . $message, LOG_INFO);
    	}
    }
}
