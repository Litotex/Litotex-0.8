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
class package_acp_packageManager extends acpPackage {

    protected $_availableActions = array('main', 'listInstalled', 'listUpdates', 'updateRemoteList', 'processUpdates', 'processUpdateQueue', 'installPackage', 'displayUpdateQueue', 'setQueueDetails');
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
        package::$packages->generateDependencyCache();
        package::$packages->generateHookCache();
        package::$packages->generateTplModificationCache();
        $this->_frontendPackages->generateDependencyCache();
        $this->_frontendPackages->generateHookCache();
        $this->_frontendPackages->generateTplModificationCache();
        return true;
    }

    public function __action_listInstalled() {

    }

    public function __action_listUpdates() {
        self::addJsFile('checkbox.js', $this->_packageName);
        $packages = array();
        $result = self::$db->Execute("SELECT `ID`, `name`, `update`, `critupdate`, `description`, `author`, `authorMail`, `releaseDate`, `changelog` FROM `lttx".package::$pdbn."_package_list` WHERE `update` = 1");
        while (!$result->EOF) {
            $result->fields['changelog'] = unserialize($result->fields['changelog']);
            $packages[] = $result->fields;
            $result->MoveNext();
        }
        self::$tpl->assign('updates', $packages);
        $this->_theme = 'updateList.tpl';
        return true;
    }

    public function __action_updateRemoteList($refer = true) {
        $this->_frontendPackages->updateRemotePackageList(array('' => $this->_frontendPackages, 'acp' => self::$packages));
        if($refer)
        header("location: index.php?package=acp_packageManager&action=listUpdates");
    }

    public function __action_processUpdates() {
        $items = array();
        if (!isset($_POST['update']) || !is_array($_POST['update']))
            header("Location: index.php?package=acp_packageManager&action=listUpdates");
        foreach ($_POST['update'] as $i => $update) {
            $_POST['update'][$i] = (int) $update;
            $exists = self::$db->Execute("SELECT COUNT(*) FROM `lttx".package::$pdbn."_package_list` WHERE `ID` = ? AND `update` = 1", array($_POST['update'][$i]));
            if (!$exists || $exists->fields[0] == 0)
                throw new lttxError('E_updateIDNotFound');
            $this->_addInstallItem($items, $_POST['update'][$i]);
        }
        $_SESSION['updateQueue'] = $items;
        header("location: index.php?package=acp_packageManager&action=displayUpdateQueue");
    }

    private function _addInstallItem(&$items, $newItem) {
        if (in_array($newItem, $items))
            return true;
        $data = self::$db->Execute("SELECT `dependencies`, `name` FROM `lttx".package::$pdbn."_package_list` WHERE `ID` = ?", array($newItem));
        if (!$data || !isset($data->fields[0]))
            return false;
        $dep = unserialize($data->fields[0]);
        foreach ($dep as $depItem) {
            if ($depItem['installed'] >= 1)
                continue;
            $itemID = self::$db->Execute("SELECT `ID` FROM `lttx".package::$pdbn."_package_list` WHERE `name` = ?", array($depItem['name']));
            if (!$itemID || !isset($itemID->fields[0]))
                return false;
            if (!$this->_addInstallItem($items, $itemID->fields[0]))
                return false;
        }
        $items[] = (int) $newItem;
        self::$packages->copyRemotePackage($data->fields[1], '0.8.x');
        return true;
    }

    public function __action_processUpdateQueue() {
        require_once LITO_FRONTEND_ROOT . 'packages/core/classes/installer.class.php';
        $this->__action_updateRemoteList(false);
        if (!isset($_SESSION['updateQueueDetails']))
            header("Location: index.php?package=acp_packageManager&action=listUpdates");
        $item = array_shift($_SESSION['updateQueueDetails']);
        $fileBlacklist = $_SESSION['updateQueueFileBlacklist'];
        $sqlBlacklist = $_SESSION['updateQueueSqlBlacklist'];
        $this->_theme = 'installerWork.tpl';
        if (!$item) {
            header("location: index.php?package=acp_packageManager&action=main");
            exit();
        }
        if ($item['prefix'] === '') {
            $pm = $this->_frontendPackages;
            $root = LITO_FRONTEND_ROOT;
        } else {
            $pm = self::$packages;
            $root = LITO_ROOT;
        }
        require_once(LITO_ROOT . 'files/cache/' . $item['name'] . '.' . $item['version'] . '.0.8.x.cache/installer.php');
        $installerName = 'installer_' . $item['name'];
        if (!class_exists($installerName))
            throw new lttxError('E_couldNotLoadInstallerPackage');
        $installData = new $installerName(LITO_ROOT . 'files/cache/' . $item['name'] . '.' . $item['version'] . '.0.8.x.cache', $item['name'], $pm, $root . 'packages/', $root . TPL_DIR);
        $fBlack = (isset($fileBlackList[$item['name']])) ? $fileBlackList[$item['name']] : array();
        $sBlack = (isset($sqlBlacklist[$item['name']])) ? $sqlBlacklist[$item['name']] : array();
        $installData->install($fBlack, $sBlack);
        self::$tpl->assign('installItem', $item);
        self::$tpl->assign('installer', $installData);
        return true;
    }

    public function __action_installPackage() {
        $this->_theme = 'installPreview.tpl';
        return true;
    }

    public function __action_displayUpdateQueue() {
        require_once LITO_FRONTEND_ROOT . 'packages/core/classes/installer.class.php';
        if (!isset($_SESSION['updateQueue'])) {
            header("Location: index.php?package=acp_packageManager&action=main");
            exit();
        }
        $installItems = array();
        $error = false;
        foreach ($_SESSION['updateQueue'] as $installItem) {
            $tplFiles = array();
            $packageFiles = array();
            $data = self::$db->Execute("SELECT `ID`, `name`, `prefix`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `signInfo`, `releaseDate`, `dependencies`, `changelog` FROM `lttx".package::$pdbn."_package_list` WHERE `ID` = ?", array($installItem));
            if (!$data || !isset($data->fields[0]))
                throw new lttxError('E_updateIDNotFound');
            require_once(LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/installer.php');
            $installerName = 'installer_' . $data->fields['name'];
            if (!class_exists($installerName))
                throw new lttxError('E_couldNotLoadInstallerPackage');
            if ($data->fields['prefix'] === '') {
                $pm = $this->_frontendPackages;
                $root = LITO_FRONTEND_ROOT;
            } else {
                $pm = self::$packages;
                $root = LITO_ROOT;
            }
            $installData = new $installerName(LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache', $data->fields['name'], $pm);
            $data->fields['dependencies'] = unserialize($data->fields['dependencies']);
            $data->fields['changelog'] = unserialize($data->fields['changelog']);
            $data->fields['signInfo'] = unserialize($data->fields['signInfo']);
            $data->fields['packageFiles'] = $installData->getFileList();
            $data->fields['error'] = false;
            foreach ($data->fields['packageFiles']['tpl'] as $tplFile) {
                if (is_dir(LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/template/' . $tplFile))
                    continue;
                if (!file_exists($root . TPL_DIR . 'default/' . $data->fields['name'] . '/' . $tplFile)) {
                    $tplFiles[] = array(0, $tplFile);
                    continue;
                }
                if ($pm->compareFileHash(TPL_DIR . 'default/' . $data->fields['name'] . '/' . $tplFile))
                    $tplFiles[] = array(1, $tplFile, $root . TPL_DIR . 'default/' . $data->fields['name'] . '/' . $tplFile, LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/template/' . $tplFile);
                else {
                    $tplFiles[] = array(-1, $tplFile, $root . TPL_DIR . 'default/' . $data->fields['name'] . '/' . $tplFile, LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/template/' . $tplFile);
                    $data->fields['error'] = true;
                }
            }
            foreach ($data->fields['packageFiles']['package'] as $packageFile) {
                if (is_dir(LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/packages/' . $packageFile))
                    continue;
                if (!file_exists($root . 'packages/' . $data->fields['name'] . '/' . $packageFile)) {
                    $packageFiles[] = array(0, $packageFile);
                    continue;
                }
                if ($pm->compareFileHash('packages/' . $data->fields['name'] . '/' . $packageFile))
                    $packageFiles[] = array(1, $packageFile, $root . 'packages/' . $data->fields['name'] . '/' . $packageFile, LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/package/' . $packageFile);
                else {
                    $packageFiles[] = array(-1, $packageFile, $root . 'packages/' . $data->fields['name'] . '/' . $packageFile, LITO_ROOT . 'files/cache/' . $data->fields['name'] . '.' . $data->fields['version'] . '.0.8.x.cache/package/' . $packageFile);
                    $data->fields['error'] = true;
                }
            }
            $data->fields['tplFilesChecked'] = $tplFiles;
            $data->fields['packageFilesChecked'] = $packageFiles;
            if ($data->fields['error'])
                $error = true;
            $querys = array();
            $queryList = $installData->getQueryList();
            $data->fields['queryList'] = $queryList;
            $installItems[] = $data->fields;
        }
        self::$tpl->assign('error', $error);
        self::$tpl->assign('installQueue', $installItems);
        $this->_theme = 'listQueue.tpl';
        self::addJsFile('checkbox.js', $this->_packageName);
        return true;
    }

    public function __action_setQueueDetails() {
        require_once LITO_FRONTEND_ROOT . 'packages/core/classes/installer.class.php';
        if (!isset($_POST['update']) || !is_array($_POST['update']))
            throw new lttxError('E_noPackageListPassed');
        $fileBlackList = array();
        if (isset($_POST['fileBlacklist']) && is_array($_POST['fileBlacklist'])) {
            foreach ($_POST['fileBlacklist'] as $item) {
                $item = explode(';', $item);
                if (count($item) != 3)
                    continue;
                if (!isset($fileBlackList[$item[0]][$item[1]]))
                    $fileBlackList[$item[0]][$item[1]] = array();
                $fileBlackList[$item[0]][$item[1]][] = $item[2];
            }
        }
        $sqlBlacklist = array();
        if (isset($_POST['queryBlacklist']) && is_array($_POST['queryBlacklist'])) {
            foreach ($_POST['queryBlacklist'] as $item) {
                $item = explode(';', $item);
                if (!isset($sqlBlacklist[$item[0]]))
                    $sqlBlacklist[$item[0]] = array();
                $sqlBlacklist[$item[0]][] = $item[1];
            }
        }
        $packages = array();
        foreach ($_POST['update'] as $item) {
            $data = package::$pdb->Execute("SELECT `ID`, `name`, `prefix`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `signInfo`, `releaseDate`, `dependencies`, `changelog` FROM `lttx".package::$pdbn."_package_list` WHERE `ID` = ?", array($item));
            if (!$data || !isset($data->fields[0]))
                continue;
            $data->fields['signInfo'] = unserialize($data->fields['signInfo']);
            $data->fields['dependencies'] = unserialize($data->fields['dependencies']);
            $data->fields['changelog'] = unserialize($data->fields['changelog']);
            $packages[$data->fields['name']] = $data->fields;
        }
        foreach ($packages as $item) {
            $pm = ($item['prefix'] == '') ? $this->_frontendPackages : package::$packages;
            foreach ($item['dependencies'] as $dep) {
                if (!isset($packages[$dep['name']]) && !$pm->exists($dep['name']))
                    throw new lttxError('E_couldNotResolveDependencies', $item['name']);
            }
        }
        unset($_SESSION['updateQueue']);
        $_SESSION['updateQueueDetails'] = $packages;
        $_SESSION['updateQueueFileBlacklist'] = $fileBlackList;
        $_SESSION['updateQueueSqlBlacklist'] = $sqlBlacklist;
        header("Location: index.php?package=acp_packageManager&action=processUpdateQueue");
    }

}