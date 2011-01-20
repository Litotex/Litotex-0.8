<?php

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
            throw new lttxError ('E_noFilesToCompare');
        if(!file_exists($_GET['oldFile']))
            throw new lttxError ('E_couldNotFindFile', $_GET['oldFile']);
        if(!file_exists($_GET['newFile']))
                throw new lttxError ('E_couldNotFindFile', $_GET['newFile']);
        $diff = new diff;
        $diffOut = $diff->inline($_GET['oldFile'], $_GET['newFile']);
        self::$tpl->assign('diff', $diffOut);
        return true;
    }
}