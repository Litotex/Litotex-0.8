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

define("DEVDEBUG", true);

if (DEVDEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
}

header("Content-Type: text/html; charset=utf-8");

session_start();

require_once MODULES_DIRECTORY.'core/autoloader.php';

try {
    try {
        //Next... Database connection!
        $noDBConfig = false;
        if (!file_exists(DATABASE_CONFIG_FILE)) {
            $noDBConfig = true;
        } else if (!($dbConfig = parse_ini_file(DATABASE_CONFIG_FILE))) {
            $noDBConfig = true;
        } else if (!isset($dbConfig['host']) || !isset($dbConfig['user']) || !isset($dbConfig['password']) || !isset($dbConfig['database'])) {
            $noDBConfig = true;
        }
        if ($noDBConfig) {
            trigger_error('No databasesettings saved at '.DATABASE_CONFIG_FILE, E_USER_ERROR);
            exit();
        }
        try {
            $db = new Database('mysql:dbname='.$dbConfig['database'].';host='.$dbConfig['host'], $dbConfig['user'], $dbConfig['password']);
        } catch (PDOException $e) {
            die('Database connection failed! '.$e->getMessage());
        }
        Package::setDatabaseClass($db);

        $packageManager = new PackageManager();
        Package::setPackageManagerClass($packageManager);

        require_once 'classes/thirdparty/Smarty/Smarty.class.php';

        //Smarty settings... next
        $smarty = new Smarty();
        $smarty->compile_dir = TEMPLATE_COMPILATION;
        $smarty->debugging = false;
        if (file_exists(Package::getTplDir().'header.tpl')) {
            $smarty->assign('HEADER', Package::getTplDir().'header.tpl');
        } else {
            $smarty->assign('HEADER', Package::getTplDir(false, 'default').'header.tpl');
        }
        if (file_exists(Package::getTplDir().'footer.tpl')) {
			$smarty->assign('QueryCount', $db->getQueryCount());
            $smarty->assign('FOOTER', Package::getTplDir().'footer.tpl');
        } else {
            $smarty->assign('FOOTER', Package::getTplDir(false, 'default').'footer.tpl');
        }
        $smarty->assign('TITLE', 'Litotex 0.8 Core Engine');
        Package::setTemplateClass($smarty);
    } catch (Exception $e) {
        die("Fatal Exception in uncatchable area!<br /><b>You see this message, because a fatal error occured while initializing the system (especially the error handling system which was not usable when it should handle this error).<br />We appologice any inconviniance that might have happened and hope the system is back up running soon. The following data is backtrace information to find out why this error occured.</b><br /><br />".$e);
    }
    Package::loadLang($smarty);
    @setlocale(LC_ALL, Package::getLanguageVar('PHP_LOCALE'));
    @date_default_timezone_set(Package::getLanguageVar('PHP_DEFAULT_TIMEZONE'));
    //packages::reloadFileHashTable();
    //Restore Session?
    if (isset($_SESSION['lttx']['session'])) {
        $session = unserialize($_SESSION['lttx']['session']);
        if (!$session->sessionActive())
            $session->destroy();
        else
            $session->refresh();
    }else
        $session = new Session();
    Package::setSessionClass($session);
    //Package next

    $packageManager->callHook('loadCore', array());

    if (!Package::$user)
        $perm = new UserPermission(new User(0));
    else
        $perm = new UserPermission(Package::$user);
    Package::setPermClass($perm);


    if (isset($_GET['package'])) {
        $package = $_GET['package'];
        $package = $packageManager->loadPackage($package, true);
        if (!$package) {
            $error = $packageManager->loadPackage(LITO_ERROR_MODULE, true);
            if (!$error) {
                header('HTTP/ 500');
                die('<h1>Internal Server Error</h1><p>Whoops something went wrong!</p>');
            }
            $error->__action_404();
        }
    } else {
        $package = $packageManager->loadPackage(LITO_START_PACKAGE, true);
    }

    $packageManager->callHook('endCore', array());
} catch (Exception $e) {
    if (isset($package) && is_a($package, 'package'))
        $package->setTemplatePolicy(false);
    if (is_a($e, 'LitotexFatalError'))
        $e->setTraced(false);
    $tpl = Package::$tpl;
    $tpl->assign('errorMessage', $e->getMessage());
    Package::loadLang($tpl);
    if (is_a($e, 'LitotexError') || is_a($e, 'lttxFatalError'))
        $tpl->display(Package::getTplDir('main').'CoreError.tpl');
    else if (is_a($e, 'LitotexInfo'))
        $tpl->display(Package::getTplDir('main').'CoreInfo.tpl');
    else
        throw $e;
    exit();
}
