<?php

/**
 * ACP package to manage packages
 *
 * @author:     Jonas Schwabe <j.s@cascaded-web.com>
 * @licence:	Copyright 2010 Litotex Team
 */
class package_acp_packageManager extends acpPackage {

    protected $_availableActions = array('main', 'listInstalled', 'listUpdates', 'updateRemoteList', 'processUpdates', 'processUpdateQueue', 'installPackage', 'displayUpdateQueue');
    protected $_packageName = 'acp_packageManager';
    protected $_theme = 'main.tpl';
    protected $_frontendPackages = false;

    public function runtimeAcp() {
        $this->_frontendPackages = new packages('', false, MODULES_FRONTEND_DIRECTORY, TEMPLATE_FRONTEND_DIRECTORY);
    }

    public static function registerHooks() {
        return true;
    }

    public function __action_main() {
        return true;
    }

    public function __action_listInstalled() {

    }

    public function __action_listUpdates() {
        self::addJsFile('checkbox.js', $this->_packageName);
        $packages = array();
        $result = self::$db->Execute("SELECT `ID`, `name`, `update`, `critupdate`, `description`, `author`, `authorMail`, `releaseDate`, `changelog` FROM `lttx_packageList` WHERE `update` = 1");
        while (!$result->EOF) {
            $result->fields['changelog'] = unserialize($result->fields['changelog']);
            $packages[] = $result->fields;
            $result->MoveNext();
        }
        self::$tpl->assign('updates', $packages);
        $this->_theme = 'updateList.tpl';
        return true;
    }

    public function __action_updateRemoteList() {
        $this->_frontendPackages->updateRemotePackageList(array('' => $this->_frontendPackages, 'acp' => self::$packages));
        header("location: index.php?package=acp_packageManager&action=listUpdates");
    }

    public function __action_processUpdates() {
        $items = array();
        if (!isset($_POST['update']) || !is_array($_POST['update']))
            header("Location: index.php?package=acp_packageManager&action=listUpdates");
        foreach ($_POST['update'] as $i => $update) {
            $_POST['update'][$i] = (int) $update;
            $exists = self::$db->Execute("SELECT COUNT(*) FROM `lttx_packageList` WHERE `ID` = ? AND `update` = 1", array($_POST['update'][$i]));
            if (!$exists || $exists->fields[0] == 0)
                throw new lttxError('E_updateIDNotFound');
            $this->_addInstallItem($items, $_POST['update'][$i]);
        }
        $_SESSION['updateQueue'] = $items;
        header("location: index.php?package=acp_packageManager&action=displayUpdateQueue");
    }

    private function _addInstallItem(&$items, $newItem) {
        if(in_array($newItem, $items))
                return true;
        $data = self::$db->Execute("SELECT `dependencies` FROM `lttx_packageList` WHERE `ID` = ?", array($newItem));
        if (!$data || !isset($data->fields[0]))
            return false;
        $dep = unserialize($data->fields[0]);
        foreach ($dep as $depItem) {
            if ($depItem['installed'] > 1)
                continue;
            $itemID = self::$db->Execute("SELECT `ID` FROM `lttx_packageList` WHERE `name` = ?", array($depItem['name']));
            if (!$itemID || !isset($itemID->fields[0]))
                return false;
            if(!$this->_addInstallItem($items, $itemID->fields[0]))
                    return false;
        }
        $items[] = (int)$newItem;
        return true;
    }

    public function __action_processUpdateQueue() {
        if (!isset($_SESSION['updateQueue']))
            header("location: index.php?package=acp_packageManager&action=listUpdates");
        $next = array_pop($_SESSION['updateQueue']);
        header("location: index.php?package=acp_packageManager&action=installPackage&ID=" . $next . "&redirectBack=" . urlencode("index.php?package=acp_packageManager&action=processUpdateQueue"));
    }

    public function __action_installPackage() {
        $this->_theme = 'installPreview.tpl';
        return true;
    }

    public function __action_displayUpdateQueue(){
        if(!isset($_SESSION['updateQueue'])){
            header("Location: index.php?package=acp_packageManager&action=main");
            exit();
        }
        $installItems = array();
        foreach($_SESSION['updateQueue'] as $installItem){
            $data = self::$db->Execute("SELECT `ID`, `name`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `signInfo`, `releaseDate`, `dependencies`, `changelog` FROM `lttx_packageList` WHERE `ID` = ?", array($installItem));
            if(!$data || !isset($data->fields[0]))
                    throw new lttxError ('E_updateIDNotFound');
            $data->fields['dependencies'] = unserialize($data->fields['dependencies']);
            $data->fields['changelog'] = unserialize($data->fields['changelog']);
            $data->fields['signInfo'] = unserialize($data->fields['signInfo']);
            $installItems[] = $data->fields;
        }
        self::$tpl->assign('installQueue', $installItems);
        $this->_theme = 'listQueue.tpl';
        self::addJsFile('checkbox.js', $this->_packageName);
        return true;
    }

}