<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
define("DEVDEBUG", true);

header("Content-Type: text/html; charset=utf-8");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once('config/const.php');
require_once('classes/math.class.php');
require_once('classes/package.class.php');
require_once('classes/packagemanager.class.php');
require_once('classes/AdoDB/adodb.inc.php');
require_once('classes/plugin.class.php');
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
    throw new Exception('No databasesettings saved at ' . DATABASE_CONFIG_FILE);
    exit();
}
$db = NewADOConnection('mysql');
$db->charSet = 'utf8';
$db->connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database']);
//mysql_set_charset('utf8');
if($db->ErrorMsg()) {
    die('Database connection failed!');
}
package::setDatabaseClass($db);
//Smarty settings... next
$smarty = new Smarty();
$smarty->compile_dir = TEMPLATE_COMPILATION;
$smarty->debugging = false;
$smarty->assign('HEADER', package::getTplDir() . 'header.tpl');
$smarty->assign('FOOTER', package::getTplDir() . 'footer.tpl');
$smarty->assign('TITLE', 'Litotex 0.8 Core Engine');
package::addCssFile('main.css');
package::addJsFile('jquery.js');
package::addJsFile('jquery.validate.min.js');
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

$packageManager->callHook('loadCore', array());

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
//$packageManager->installPackage('/home/jonas/Dokumente/PHP/LinuxDokuSample/Litotex-Sample-Packages/sample1', 'sample1');
$packageManager->callHook('endCore', array());
package::$tpl->assign('queryCount', package::$db->count);