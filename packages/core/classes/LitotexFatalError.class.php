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

class LitotexFatalError extends Exception {

    private $_logID = false;

    public function __construct($message = '', $package = false) {
        Package::loadLang(Package::$tpl);
        $this->message = Package::getLanguageVar('E_fatalErrorOccured');
        $this->message .= '<br /><b>'.nl2br($message).'</b>';
        $this->_log($message, $package);
        //debug_print_backtrace();
    }

    private function _log($message, $package) {
        $backtrace = '##'.$this->getFile().'('.$this->getLine().'):'.$message."\n".$this->getTraceAsString();
        Package::$pdb->prepare("INSERT INTO `lttx1_error_log` (`package`, `traced`, `backtrace`) VALUES (?, ?, ?)")->execute(array($package, 1, $backtrace));
        $this->_logID = Package::$pdb->lastInsertId();
        
    }

    public function setTraced($option) {
        if (!$this->_logID)
            return false;
        //what is that ???
        //Package::$pdb->prepare("UPDATE `lttx1_error_log` SET `traced` = ? WHERE `ID` = ?")->execute(array($option, $this->_logID));
    }

}
