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

class LitotexError extends Exception {

    public function __construct($messageCode,$priority = LOG_INFO) {
        $args = func_get_args();
        
        $messageCode = $args[0];
        package::loadLang(package::$tpl);
        if (package::getLanguageVar($messageCode) != '')
            $this->message = package::getLanguageVar($messageCode);
        else
            $this->message = $messageCode;
        
        $messageParams = array_slice($args, 1);
        $this->message = vsprintf($this->message, $messageParams);
<<<<<<< HEAD
        Package::debug($this->message,$priority) ;
=======
        
>>>>>>> 6241669bbf23256b01d33dcd4197e7deffc54de9
        if (DEVDEBUG == true) {
            $this->message .= "<br /><b>DEVDEBUG active</b><br />";
            foreach ($this->getTrace() as $trace) {
                @$this->message .= '<p>'.$trace['class'].':'.$trace['function'].': '.$trace['file'].':'.$trace['line'].'</p>';
            }
        }
        
        Package::debug($this->message, LOG_ERR);
    }

}
