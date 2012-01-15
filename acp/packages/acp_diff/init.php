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
 * ACP package to manage packages
 *
 * @author:     Jonas Schwabe <j.s@cascaded-web.com>
 * @licence:	Copyright 2010 Litotex Team
 */
require_once 'classes/diff.class.php';
class package_acp_diff extends acpPackage {

    protected $_availableActions = array('main');
    protected $_packageName = 'acp_diff';
    protected $_theme = 'main.tpl';
   
    public static function registerHooks() {
        return true;
    }

    public function __action_main() {
        $this->addCssFile('diff.css', $this->_packageName);
        if(!isset($_GET['oldFile']) || !isset($_GET['newFile']))
            throw new LitotexError ('E_noFilesToCompare');
        if(!file_exists($_GET['oldFile']))
            throw new LitotexError ('E_couldNotFindFile', $_GET['oldFile']);
        if(!file_exists($_GET['newFile']))
                throw new LitotexError ('E_couldNotFindFile', $_GET['newFile']);
        $diff = new diff;
        $diffOut = $diff->inline($_GET['oldFile'], $_GET['newFile']);
        self::$tpl->assign('diff', $diffOut);
        if(isset($_GET['noTemplate']) && $_GET['noTemplate'])
            self::$tpl->assign ('noTemplate', true);
        else
            self::$tpl->assign ('noTemplate', false);
        return true;
    }
}