<?php

abstract class installer {

    private $_location = false;
    private $_initialized = false;
    private $_packageName = false;
    private $_backup = false;
    private $_versionOld = false;
    private $_versionNew = false;
    private $_templateDir = TEMPLATE_DIRECTORY; /* TEMPLATE_DIRECTORY or TEMPLATE_FRONTEND_DIRECTORY */
    private $_packageDir = MODULES_DIRECTORY; /* MODULES_DIRECTORY or MODULES_FRONTEND_DIRECTORY */
    private $_log = array();
    private $_pm = false;

    public final function __construct($location, $packageName, $pm, $packageDir = false, $templateDir = false) {
        $this->_location = $location;
        $this->_packageName = $packageName;
        if($templateDir)
            $this->_templateDir = $templateDir;
        if($packageDir)
            $this->_packageDir = $packageDir;
        $this->_versionOld = $pm->getVersionNumber($this->_packageName);
        $this->_pm = $pm;
        $this->_versionNew = $this->_getVersionNumber($this->_packageName, $this->_location . '/package/init.php');
        $this->_checkData();
    }

    public final function __destruct() {

    }

    private final function _checkData() {
        if (!is_dir($this->_location . '/template'))
            throw new lttxFatalError("Template directory was not found in $this->_location/template");
        if (!is_dir($this->_location . '/package'))
            throw new lttxFatalError("Package directory was not found in $this->_location/package");
        if (!file_exists($this->_location . '/package/init.php'))
            throw new lttxFatalError("Package init file directory was not found in $this->_location/package/init.php");
        if (file_exists($this->_location . '/database/install.sql') && !file_exists($this->_location . '/database/remove.sql')) {
            throw new lttxFatalError("Installation package contains an installation SQL File but lacks support for deinstall it. Please add remove.sql in order to install this application.");
            return true;
        }
    }

    public final function install($fileBlacklist, $dbBlacklist) {
        try {
            $this->_backup = $this->_pm->createBackup($this->_packageName);
        } catch (Exception $e) {$this->_pm->createBackup($this->_packageName);
            $this->addLog(package::getLanguageVar('acp_packageManager_noBackupTaken'));
        }
        if (!isset($fileBlacklist['tpl']))
            $fileBlacklist['tpl'] = array();
        if (!isset($fileBlacklist['package']))
            $fileBlacklist['package'] = array();
        packages::recursiveCopy($this->_location . '/template', $this->_templateDir . 'default/' . $this->_packageName, $fileBlacklist['tpl']);
        $this->addLog(package::getLanguageVar('acp_packageManager_tplFileCopy'));
        packages::recursiveCopy($this->_location . '/package', $this->_packageDir . $this->_packageName, $fileBlacklist['package']);
        $this->addLog(package::getLanguageVar('acp_packageManager_packageFileCopy'));
        $this->_patchDatabase(true, $dbBlacklist);
        $this->_freeInstall();
    }

    private final function _patchDatabase($install, $blackList) {
        if ($install) {
            if (!file_exists($this->_location . '/database/install.sql'))
                return true;
            else
                $fileName = $this->_location . '/database/install.sql';
        } else {
            if (!file_exists($this->_location . '/database/remove.sql'))
                return true;
            else
                $fileName = $this->_location . '/database/remove.sql';
        }
        $data = simplexml_load_file($fileName);
        if (!isset($data->query))
            return true;
        foreach ($data->query as $query) {
            if(in_array(md5($query), $blackList)) continue;
            if (packages::compareVersionNumbers($this->_versionOld, $query->attributes()->version) == 3 || packages::compareVersionNumbers($this->_versionOld, $query->attributes()->version) == 1 && $install) {
                $this->addLog(sprintf(package::getLanguageVar('acp_packageManager_packageSqlQuery'), $query));
                package::$db->Execute($query);
                $errorMsg = package::$db->ErrorMsg();
                if ($errorMsg) {
                    $this->addLog(sprintf(package::getLanguageVar('acp_packageManager_packageSqlQueryReturn'), $errorMsg));
                    if ($install)
                        $this->addLog(package::getLanguageVar('acp_packageManager_packageRollback'));
                    try {
                        if ($install) {
                            $this->rollback();
                            $this->_patchDatabase(false, $blackList);
                        }
                    } catch (Exception $e) {
                        
                    }
                    return false;
                }
            }
        }
        return true;
    }

    private final function _getVersionNumber($modulName, $file) {
        if (!file_exists($file))
            return false;
        if (class_exists('package_' . $modulName . 'INSTALLER')) {
            $vars = get_class_vars('package_' . $modulName . 'INSTALLER');
            return (isset($vars['version'])) ? $vars['version'] : false;
        }
        return eval(preg_replace('/<\?php|\?>|/', '', str_replace('class package_' . $modulName, 'class package_' . $modulName . 'INSTALLER', file_get_contents($file))) . " \$vars = get_class_vars('package_" . $modulName . "INSTALLER'); return (isset(\$vars['version']))?\$vars['version']:false;");
    }

    private final function rollback() {
        $this->_pm->restoreBackup($this->_packageName, $this->_backup);
    }

    protected abstract function _freeInstall();

    private static final function _generateFileListReq($file, $fileAdd, &$return) {
        if (!file_exists($file . '/' . $fileAdd))
            return false;
        if (!is_dir($file . '/' . $fileAdd)) {
            return false;
        }
        $dir = @opendir($file . '/' . $fileAdd);
        if (!$dir)
            return false;
        while ($readf = readdir($dir)) {
            if ($readf == '.' || $readf == '..')
                continue;
            $return[] = $fileAdd . $readf;
            if (is_dir($file . '/' . $readf))
                self::_generateFileListReq($file, $fileAdd . $readf . '/', $return);
        }
        return true;
    }

    public final function getFileList() {
        $return = array('tpl' => array(), 'package' => array(), 'db' => array());
        $db = $package = $tpl = array();
        self::_generateFileListReq($this->_location . '/template', '', $return['tpl']);
        self::_generateFileListReq($this->_location . '/package', '', $return['package']);
        self::_generateFileListReq($this->_location . '/database', '', $return['db']);
        return $return;
    }

    public final function getQueryList() {
        $queryList = array();
        if (!file_exists($this->_location . '/database/install.sql'))
            return array();
        $content = file_get_contents($this->_location . '/database/install.sql');
        $xml = new SimpleXMLElement($content);
        foreach ($xml->children() as $query)
            $queryList[] = (string) $query;
        return $queryList;
    }
    public final function addLog($message){
        $this->_log[] = $message;
    }
    public final function getLog(){
        return $this->_log;
    }
}