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
require_once('config/const.php');
require_once('classes/math.class.php');
require_once('classes/package.class.php');
require_once('classes/lttxError.class.php');
require_once('classes/packagemanager.class.php');
require_once('classes/AdoDB/adodb.inc.php');
require_once('classes/plugin.class.php');
require_once('classes/date.class.php');
require_once('classes/Smarty/Smarty.class.php');
require_once('classes/session.class.php');
require_once('classes/user.class.php'); //ATTENTION! session.class.php has to be included BEFORE user.class.php
require_once('classes/perm.class.php');
require_once 'classes/option.class.php';
try{
	try{
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
			trigger_error('No databasesettings saved at ' . DATABASE_CONFIG_FILE, E_USER_ERROR);
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
	
		$packageManager = new packages();
		package::setPackageManagerClass($packageManager);
		
		$log =new lttxLog();
		package::setlttxLogClass($log);
		
		
		//Smarty settings... next
		$smarty = new Smarty();
		$smarty->compile_dir = TEMPLATE_COMPILATION;
		$smarty->debugging = false;
		if(file_exists(package::getTplDir() . 'header.tpl')){
			$smarty->assign('HEADER', package::getTplDir() . 'header.tpl');
		}else{
			$smarty->assign('HEADER', package::getTplDir(false, 'default') . 'header.tpl');
		}
		if(file_exists(package::getTplDir() . 'footer.tpl')){
			$smarty->assign('FOOTER', package::getTplDir() . 'footer.tpl');
		}else{
			$smarty->assign('FOOTER', package::getTplDir(false, 'default') . 'footer.tpl');
		}
		$smarty->assign('TITLE', 'Litotex 0.8 Core Engine');
		package::addCssFile('main.css');
		package::addCssFile('jquery-ui-1.8.4.custom.css');
		package::addJsFile('jquery.js');
		package::addJsFile('jquery.validate.min.js');
		package::addJsFile('jquery-ui-1.8.4.custom.min.js');
		package::setTemplateClass($smarty);
	}catch (Exception $e){
		die("Fatal Exception in uncatchable area!<br /><b>You see this message, because a fatal error occured while initializing the system (especially the error handling system which was not usable when it should handle this error).<br />We appologice any inconviniance that might have happened and hope the system is back up running soon. The following data is backtrace information to find out why this error occured.</b><br /><br />" . $e);
	}
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

	$packageManager->callHook('loadCore', array());

	if(!package::$user)
	$perm = new userPerm(new user(0));
	else
	$perm = new userPerm(package::$user);
	package::setPermClass($perm);
	if(isset($_GET['package'])) {
		$package = $_GET['package'];
		$package = $packageManager->loadPackage($package, true);
		if(!$package){
			$error = $packageManager->loadPackage('errorPage', true);
			if(!$error){
				header('HTTP/ 500');
				die('<h1>Internal Server Error</h1><p>Whoops something went wrong!</p>');
			}
			$error->__action_404();
		}
	}else {
		$package = $packageManager->loadPackage(defaultPackage, true);
	}

	//$packageManager->installPackage('/home/jonas/Dokumente/PHP/LinuxDokuSample/Litotex-Sample-Packages/sample1', 'sample1');
	$packageManager->callHook('endCore', array());
}catch (Exception $e){
        package::$tpl->assign('queryCount', package::$db->count);
	if(isset($package) && is_a($package, 'package'))
	$package->setTemplatePolicy(false);
	if(is_a($e, 'lttxFatalError'))
	$e->setTraced(false);
	$tpl = package::$tpl;
	$tpl->assign('errorMessage', $e->getMessage());
	package::loadLang($tpl);
	if(is_a($e, 'lttxError') || is_a($e, 'lttxFatalError'))
	$tpl->display(package::getTplDir('main') . 'CoreError.tpl');
	else if(is_a($e, 'lttxInfo'))
	$tpl->display(package::getTplDir('main') . 'CoreInfo.tpl');
        else
            throw $e;
	exit();
}