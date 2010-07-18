<?php

define("DEVDEBUG", true);

header("Content-Type: text/html; charset=utf-8");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once('const.php');
require_once('classes/package.class.php');
require_once('classes/packagemanager.class.php');
require_once('classes/AdoDB/adodb.inc.php');
require_once('classes/date.class.php');
require_once('classes/Smarty/Smarty.class.php');
require_once('classes/session.class.php');
require_once('classes/user.class.php'); //ATTENTION! session.class.php has to be included BEFORE user.class.php
require_once 'classes/option.class.php';
//Next... Database connection!
$noDBConfig = false;
if(!file_exists(DATABASE_CONFIG_FILE)) {
    $noDBConfig = true;
} else if(!($dbConfig = parse_ini_file(DATABASE_CONFIG_FILE))) {
    $noDBConfig = true;
} else if(!isset($dbConfig['host']) || !isset($dbConfig['user']) || !isset($dbConfig['password']) || !isset($dbConfig['database'])) {
    $noDBConfig = true;
}
if($noDBConfig) {
    package::printErrorMsg('Fatal', 'No databasesettings saved at ' . DATABASE_CONFIG_FILE, __LINE__, __FILE__);
    exit();
}
$db = NewADOConnection('mysql');
$db->connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database']);
if($db->ErrorMsg()) {
    die('Database connection failed!');
}
package::setDatabaseClass($db);
//Smarty settings... next
$smarty = new Smarty();
$smarty->compile_dir = TEMPLATE_COMPILATION;
$smarty->debugging = true;
$smarty->assign('HEADER', TEMPLATE_HEADER);
$smarty->assign('FOOTER', TEMPLATE_FOOTER);
$smarty->assign('TITLE', 'Litotex 0.8 Preversion');
package::addCssFile('default.css');
package::addCssFile('litotex.css');
package::setTemplateClass($smarty);
//Restore Session?
if(isset($_SESSION['lttx']['session'])){
    $session = unserialize($_SESSION['lttx']['session']);
    if(!$session->sessionActive())
            $session->destroy();
    else
        $session->refresh();
}else
    $session = new session();
package::setSessionClass($session);
//Package next
$packageManager = new packages();
package::setPackageManagerClass($packageManager);
if(!package::$user)
    $perm = new perm(new user(0));
else
$perm = new perm(package::$user);
package::setPermClass($perm);
if(isset($_GET['package'])) {
    $package = $_GET['package'];
    $package = $packageManager->loadPackage($package, true);
    if(!$package)
        $packageManager->loadPackage('404', true);
}else {
    $package = $packageManager->loadPackage('main', true);
}