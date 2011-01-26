-- phpMyAdmin SQL Dump
-- version 3.3.7deb3build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Januar 2011 um 17:46
-- Server Version: 5.1.49
-- PHP-Version: 5.3.3-1ubuntu9.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `litotex`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_acpNavigation`
--

CREATE TABLE IF NOT EXISTS `lttx1_acpNavigation` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `action` varchar(100) COLLATE utf8_bin NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `lttx1_acpNavigation`
--

INSERT INTO `lttx1_acpNavigation` (`ID`, `parent`, `title`, `package`, `action`, `sort`) VALUES
(1, NULL, 'TEST', 'acp_login', 'main', 0),
(2, 1, 'TEST UNTER', 'acp_main', 'main', 0),
(3, NULL, 'NEUER', 'acp_login', 'loginSumit', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_buildingDependencies`
--

CREATE TABLE IF NOT EXISTS `lttx1_buildingDependencies` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sourceID` int(11) NOT NULL,
  `plugin` varchar(100) COLLATE utf8_bin NOT NULL,
  `pluginPreferences` text COLLATE utf8_bin NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_buildingDependencies`
--

INSERT INTO `lttx1_buildingDependencies` (`ID`, `sourceID`, `plugin`, `pluginPreferences`, `level`) VALUES
(1, 1, 'Ressource', 'a:2:{s:8:"sourceID";i:1;s:8:"minLevel";i:8;}', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_buildingRessources`
--

CREATE TABLE IF NOT EXISTS `lttx1_buildingRessources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` int(11) NOT NULL,
  `increaseFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_buildingRessources`
--

INSERT INTO `lttx1_buildingRessources` (`ID`, `resID`, `raceID`, `sourceID`, `resNum`, `increaseFormula`) VALUES
(1, 1, 1, 1, 20, '2*x');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_buildings`
--

CREATE TABLE IF NOT EXISTS `lttx1_buildings` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `race` int(11) NOT NULL,
  `plugin` text CHARACTER SET latin1 NOT NULL,
  `pluginPreferences` text CHARACTER SET latin1 NOT NULL,
  `timeFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `pointsFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `race` (`race`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_buildings`
--

INSERT INTO `lttx1_buildings` (`ID`, `name`, `race`, `plugin`, `pluginPreferences`, `timeFormula`, `pointsFormula`) VALUES
(1, 'Holzfäller', 1, 'a:1:{i:0;s:9:"Ressource";}', 'a:1:{i:0;a:2:{s:7:"formula";s:6:"x^1*20";s:5:"resID";i:1;}}', 'x*2', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_cron`
--

CREATE TABLE IF NOT EXISTS `lttx1_cron` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `textID` varchar(100) CHARACTER SET latin1 NOT NULL,
  `serialized` text CHARACTER SET latin1 NOT NULL,
  `function` varchar(100) CHARACTER SET latin1 NOT NULL,
  `params` text CHARACTER SET latin1 NOT NULL,
  `nextInt` int(11) NOT NULL,
  `interval` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `blockUserID` varchar(100) CHARACTER SET latin1 NOT NULL,
  `dependencies` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `textID` (`textID`),
  KEY `interval` (`interval`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_cron`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_errorLog`
--

CREATE TABLE IF NOT EXISTS `lttx1_errorLog` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `traced` tinyint(1) NOT NULL,
  `backtrace` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=72 ;

--
-- Daten für Tabelle `lttx1_errorLog`
--

INSERT INTO `lttx1_errorLog` (`ID`, `package`, `traced`, `backtrace`) VALUES
(3, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(64): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(4, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(115): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(5, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(64): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(6, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(115): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(7, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(68): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(8, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(339):TEST\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(327): package::getTemplate()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(117): package::getTplDir()\n#2 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#3 {main}'),
(9, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/login/init.php(74):LN_LOGIN_FORGET\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(152): package_login->__action_forget()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(117): package->_castAction(''forget'')\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(300): package->__construct(true)\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(105): packages->loadPackage(''login'', true)\n#4 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#5 {main}'),
(10, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/login/init.php(74):LN_LOGIN_FORGET\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(152): package_login->__action_forget()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(117): package->_castAction(''forget'')\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(300): package->__construct(true)\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(105): packages->loadPackage(''login'', true)\n#4 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#5 {main}'),
(11, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/login/init.php(74):LN_LOGIN_FORGET\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(152): package_login->__action_forget()\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(117): package->_castAction(''forget'')\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(300): package->__construct(true)\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(105): packages->loadPackage(''login'', true)\n#4 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#5 {main}'),
(12, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(373):Not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(347): packages->_getPackageDependencies(''core_territory'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(472): packages->loadPackage(''core_territory'', false, false)\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(314): packages->registerClass(''package_core_te...'')\n#3 [internal function]: package::registerClass(''package_core_te...'')\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(422): call_user_func(Array, ''package_core_te...'')\n#5 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(92): packages->generateDependencyCache()\n#6 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(60): packages->__construct()\n#7 /home/jonas/Dokumente/PHP/Litotex8/index.php(3): require(''/home/jonas/Dok...'')\n#8 {main}'),
(13, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(210):Packagemanager was unable to load hook function "__hook_displayNavigation"\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_navigation/init.php(15): package::_registerHook(''acp_navigation'', ''displayNavigati...'', 0)\n#1 [internal function]: package_acp_navigation::registerHooks()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(215): call_user_func(Array)\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(89): packages->generateHookCache()\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(59): packages->__construct()\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(14, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(15, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(16, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(17, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(18, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(19, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(20, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(21, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(22, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(23, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(24, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(25, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(26, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(27, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(28, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(29, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(30, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(31, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(32, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(33, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(34, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(27):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(35, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(44):Config plugin test could not initialize a new element, it returned an undefined problem.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(36, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(44):Config plugin test could not initialize a new element, it returned an undefined problem.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(37, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(44):Config plugin test could not initialize a new element, it returned an undefined problem.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(38, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(41):Config plugin test could not be found within the plugin directory.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(9): config->addElement(''test'', ''test'', Array)\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(39, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(32):Unable to fetch value for text. Use the predefined form!\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(25): configElement->_getSystemSaveValue()\n#1 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_config/classes/config.class.php(77): configElement->getSaveValue(Object(configPluginHandler))\n#2 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(11): config->getData()\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_main()\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#5 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#6 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#7 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#8 {main}'),
(40, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(55): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(41, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(55): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(42, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(55): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(43, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(55): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(44, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(55): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(45, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(56): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(46, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(56): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(47, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(56): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(48, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(56): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(49, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(56): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(50, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(131): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_editProject()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''editProject'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(51, '0', 1, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 4 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_projects/init.php(132): user->__construct(''4'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_projects->__action_editProject()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''editProject'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_projects'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(52, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 11 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(15): user->__construct(''11'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_editUser()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''editUser'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(53, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(138):User 11 was not found\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_users/init.php(16): user->__construct(''11'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_users->__action_editUser()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''editUser'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(108): packages->loadPackage(''acp_users'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(54, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(112): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(55, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(112): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(56, '0', 0, '##/home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/Litotex8/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/Litotex8/packages/core/global.php(112): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/Litotex8/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(57, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(114): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(58, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(114): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(59, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(114): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(60, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(114): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(61, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(62, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(63, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(64, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}');
INSERT INTO `lttx1_errorLog` (`ID`, `package`, `traced`, `backtrace`) VALUES
(65, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(66, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(67, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(68, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(69, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(108): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(70, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(109): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}'),
(71, '0', 0, '##/home/jonas/Dokumente/PHP/AOSA/packages/core/classes/user.class.php(626):Userfield test already exists! We will not do any changes.\n#0 /home/jonas/Dokumente/PHP/Litotex8/acp/packages/acp_main/init.php(7): user::addField(''test'', ''string'')\n#1 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(186): package_acp_main->__action_main()\n#2 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/package.class.php(133): package->_castAction(''main'')\n#3 /home/jonas/Dokumente/PHP/AOSA/packages/core/classes/packagemanager.class.php(345): package->__construct(true, Array)\n#4 /home/jonas/Dokumente/PHP/AOSA/packages/core/global.php(109): packages->loadPackage(''acp_main'', true)\n#5 /home/jonas/Dokumente/PHP/AOSA/acp/index.php(7): require(''/home/jonas/Dok...'')\n#6 {main}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_exploreDependencies`
--

CREATE TABLE IF NOT EXISTS `lttx1_exploreDependencies` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sourceID` int(11) NOT NULL,
  `plugin` varchar(100) COLLATE utf8_bin NOT NULL,
  `pluginPreferences` text COLLATE utf8_bin NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_exploreDependencies`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_exploreRessources`
--

CREATE TABLE IF NOT EXISTS `lttx1_exploreRessources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` int(11) NOT NULL,
  `increaseFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_exploreRessources`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_explores`
--

CREATE TABLE IF NOT EXISTS `lttx1_explores` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `race` int(11) NOT NULL,
  `plugin` text CHARACTER SET latin1 NOT NULL,
  `pluginPreferences` text CHARACTER SET latin1 NOT NULL,
  `timeFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `pointsFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `race` (`race`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_explores`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_log`
--

CREATE TABLE IF NOT EXISTS `lttx1_log` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `logdate` datetime NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `lttx1_log`
--

INSERT INTO `lttx1_log` (`ID`, `userid`, `logdate`, `message`) VALUES
(1, 0, '2010-09-15 22:09:23', 'Login error Username:gh1234'),
(2, 0, '2010-09-15 22:09:41', 'new registration:gh1234'),
(3, 1, '2010-09-15 22:09:48', 'Login successfully Username:gh1234'),
(4, 0, '2010-09-15 22:09:22', 'new registration:snoop'),
(5, 2, '2010-09-15 22:09:46', 'Login successfully Username:snoop'),
(6, 1, '2010-09-17 15:09:10', 'Login successfully Username:gh1234'),
(7, 0, '2010-10-10 10:10:16', 'Login error Username:gh1234'),
(8, 0, '2010-10-10 10:10:26', 'Login error Username:gh1234'),
(9, 0, '2010-10-10 10:10:32', 'Login error Username:gh1234');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_news`
--

CREATE TABLE IF NOT EXISTS `lttx1_news` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `category` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `commentNum` int(11) NOT NULL,
  `writtenBy` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `category` (`category`),
  KEY `title` (`title`,`date`,`writtenBy`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `lttx1_news`
--

INSERT INTO `lttx1_news` (`ID`, `title`, `text`, `category`, `date`, `commentNum`, `writtenBy`, `active`) VALUES
(1, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-14 22:05:40', 1, 1, 1),
(2, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:59', 0, 0, 1),
(3, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:01', 0, 0, 1),
(4, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:25', 0, 0, 1),
(5, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:50', 0, 0, 1),
(6, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:06', 0, 0, 1),
(7, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:30', 0, 0, 1),
(8, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:56', 0, 0, 1),
(9, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:13', 0, 0, 1),
(10, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 22:05:50', 0, 0, 1),
(11, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-17 16:05:56', 0, 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_newsCategories`
--

CREATE TABLE IF NOT EXISTS `lttx1_newsCategories` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `newsNum` int(11) NOT NULL,
  `newsLastDate` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `title` (`title`,`newsLastDate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `lttx1_newsCategories`
--

INSERT INTO `lttx1_newsCategories` (`ID`, `title`, `description`, `newsNum`, `newsLastDate`) VALUES
(1, 'Test', 'Das ist nur ein kurzer Funktionstest...', 0, '0000-00-00 00:00:00'),
(12, 'tes', 'lalala', 0, '2010-05-17 16:05:56');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_newsComments`
--

CREATE TABLE IF NOT EXISTS `lttx1_newsComments` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `news` int(11) NOT NULL,
  `writer` int(11) NOT NULL,
  `IP` varchar(39) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `lttx1_newsComments`
--

INSERT INTO `lttx1_newsComments` (`ID`, `title`, `text`, `date`, `news`, `writer`, `IP`) VALUES
(1, 'Test', 'Testen wir auch die Comments mal???', '2010-05-17 17:16:54', 1, 1, '123.134.123.192'),
(2, 'TTT', 'Hmemmmmm', '2010-05-18 16:32:09', 1, 1, '123.134.123.192');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_options`
--

CREATE TABLE IF NOT EXISTS `lttx1_options` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(100) CHARACTER SET latin1 NOT NULL,
  `key` varchar(100) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  `default` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `package` (`package`,`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=66 ;

--
-- Daten für Tabelle `lttx1_options`
--

INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES
(1, 'news', 'autoInformMail', '1', '1'),
(2, 'news', 'autoInformPM', '1', '1'),
(3, 'news', 'commentsPerSite', '50', '50'),
(4, 'news', 'newsPerSite', '10', '10'),
(5, 'tplSwitcher', 'tpl', 'default', 'default'),
(28, 'mail', 'AdminEmailName', 'adminEmailName', 'Admin'),
(27, 'mail', 'AdminEmail', 'info@freebg.de', 'info@freebg.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_packageList`
--

CREATE TABLE IF NOT EXISTS `lttx1_packageList` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `installed` tinyint(1) NOT NULL,
  `update` tinyint(1) NOT NULL,
  `critupdate` tinyint(1) NOT NULL,
  `version` varchar(20) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `author` varchar(100) COLLATE utf8_bin NOT NULL,
  `authorMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `signed` tinyint(1) NOT NULL,
  `signedOld` tinyint(1) NOT NULL,
  `fullSigned` tinyint(1) NOT NULL,
  `fullSignedOld` tinyint(1) NOT NULL,
  `signInfo` blob NOT NULL,
  `releaseDate` datetime NOT NULL,
  `dependencies` blob NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_packageList`
--

INSERT INTO `lttx1_packageList` (`ID`, `name`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `signInfo`, `releaseDate`, `dependencies`) VALUES
(1, 'projects', 1, 1, 0, '1.0.0', 'Das ist ein Test', 'gh1234', 'jonas.schwabe@gmail.com', 0, 1, 0, 1, 0x613a323a7b693a303b613a333a7b733a373a2276657273696f6e223b733a353a22302e312e30223b733a31343a22636f6d706c657465526576696577223b733a313a2230223b733a373a22636f6d6d656e74223b733a33373a22446173206973742061756368206e75722065696e20776569746572657220746573742e2e2e223b7d693a313b613a333a7b733a373a2276657273696f6e223b733a353a22302e312e30223b733a31343a22636f6d706c657465526576696577223b733a313a2231223b733a373a22636f6d6d656e74223b733a373a224d656872203a29223b7d7d, '2011-01-07 15:37:13', 0x613a313a7b693a303b613a333a7b733a343a226e616d65223b733a353a226c6f67696e223b733a31303a226d696e56657273696f6e223b733a353a22312e302e30223b733a393a22696e7374616c6c6564223b693a323b7d7d);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_permissions`
--

CREATE TABLE IF NOT EXISTS `lttx1_permissions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permissionLevel` int(11) NOT NULL,
  `associateType` int(11) NOT NULL,
  `associateID` int(11) NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `lttx1_permissions`
--

INSERT INTO `lttx1_permissions` (`ID`, `permissionLevel`, `associateType`, `associateID`, `package`, `function`, `class`) VALUES
(1, 1, 2, 1, 'main', 'main', ''),
(2, 1, 2, 1, 'acp_main', 'main', ''),
(3, 1, 2, 1, 'news', 'showNewsBlock', 'package_news'),
(4, 1, 2, 1, 'news', 'main', 'package_news'),
(5, 1, 2, 1, 'login', 'forget_submit', 'package_login'),
(6, 1, 2, 1, 'login', 'forget', 'package_login'),
(7, 1, 2, 1, 'login', 'logout', 'package_login'),
(8, 1, 2, 1, 'login', 'loginsubmit', 'package_login'),
(9, 1, 2, 1, 'login', 'main', 'package_login'),
(10, 1, 2, 1, 'login', 'showLoginBox', 'package_login');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_permissionsAvailable`
--

CREATE TABLE IF NOT EXISTS `lttx1_permissionsAvailable` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1 = action 2 = hook',
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `packageDir` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13773 ;

--
-- Daten für Tabelle `lttx1_permissionsAvailable`
--

INSERT INTO `lttx1_permissionsAvailable` (`ID`, `type`, `package`, `class`, `function`, `packageDir`) VALUES
(10379, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpNavigation', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10378, 1, 'acp_users', 'package_acp_users', 'editUserFields', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10377, 1, 'acp_users', 'package_acp_users', 'addUser', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10376, 1, 'acp_users', 'package_acp_users', 'searchUser', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10375, 1, 'acp_users', 'package_acp_users', 'editUser', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10374, 1, 'acp_users', 'package_acp_users', 'main', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10373, 1, 'acp_projects', 'package_acp_projects', 'deleteRelease', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10425, 1, 'sample3', 'package_sample3', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10424, 1, 'projects', 'package_projects', 'getList', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10423, 1, 'projects', 'package_projects', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10422, 1, 'errorPage', 'package_errorPage', '404', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10421, 1, 'register', 'package_register', 'register_submit', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10420, 1, 'register', 'package_register', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10419, 1, 'login', 'package_login', 'forget_submit', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10433, 2, 'sample3', 'package_sample3', 'sample3', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10432, 2, 'register', 'package_register', 'showRegisterLink', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10431, 2, 'login', 'package_login', 'showLoginBox', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10429, 2, 'tplSwitcher', 'package_tplSwitcher', 'showTemplateSwitch', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10418, 1, 'login', 'package_login', 'forget', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10417, 1, 'login', 'package_login', 'logout', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10416, 1, 'login', 'package_login', 'loginsubmit', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10415, 1, 'login', 'package_login', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10413, 1, 'edit_profile', 'package_edit_profile', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10430, 2, 'edit_profile', 'package_edit_profile', 'showProfileLink', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10428, 2, 'sample1', 'package_sample1', 'sample1', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10427, 2, 'news', 'package_news', 'showNewsBlock', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10426, 2, 'sample2', 'package_sample2', 'sample2', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10414, 1, 'edit_profile', 'package_edit_profile', 'profile_submit', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10412, 1, 'mail', 'package_mail', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10411, 1, 'tplSwitcher', 'package_tplSwitcher', 'save', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10410, 1, 'tplSwitcher', 'package_tplSwitcher', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10407, 1, 'news', 'package_news', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10409, 1, 'main', 'package_main', 'main', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10408, 1, 'news', 'package_news', 'showComments', '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(10372, 1, 'acp_projects', 'package_acp_projects', 'deleteReleaseNotSure', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10364, 1, 'acp_projects', 'package_acp_projects', 'createProject', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10365, 1, 'acp_projects', 'package_acp_projects', 'createProjectSave', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10366, 1, 'acp_projects', 'package_acp_projects', 'editProject', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10367, 1, 'acp_projects', 'package_acp_projects', 'editProjectSave', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10368, 1, 'acp_projects', 'package_acp_projects', 'deleteProject', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10369, 1, 'acp_projects', 'package_acp_projects', 'deleteProjectNotSure', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10371, 1, 'acp_projects', 'package_acp_projects', 'uploadReleaseSave', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10370, 1, 'acp_projects', 'package_acp_projects', 'uploadRelease', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10363, 1, 'acp_projects', 'package_acp_projects', 'main', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10362, 1, 'acp_main', 'package_acp_main', 'main', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10361, 1, 'acp_login', 'package_acp_login', 'loginSubmit', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(10360, 1, 'acp_login', 'package_acp_login', 'main', '/home/jonas/Dokumente/PHP/Litotex8/acp/packages/'),
(13772, 2, 'sample3', 'package_sample3', 'sample3', ''),
(13764, 1, 'sample3', 'package_sample3', 'main', ''),
(13763, 1, 'projects', 'package_projects', 'getList', ''),
(13762, 1, 'projects', 'package_projects', 'main', ''),
(13761, 1, 'errorPage', 'package_errorPage', '404', ''),
(13760, 1, 'register', 'package_register', 'register_submit', ''),
(13759, 1, 'register', 'package_register', 'main', ''),
(13758, 1, 'login', 'package_login', 'forget_submit', ''),
(13757, 1, 'login', 'package_login', 'forget', ''),
(13756, 1, 'login', 'package_login', 'logout', ''),
(13755, 1, 'login', 'package_login', 'loginsubmit', ''),
(13754, 1, 'login', 'package_login', 'main', ''),
(13771, 2, 'register', 'package_register', 'showRegisterLink', ''),
(13770, 2, 'login', 'package_login', 'showLoginBox', ''),
(13769, 2, 'edit_profile', 'package_edit_profile', 'showProfileLink', ''),
(13768, 2, 'tplSwitcher', 'package_tplSwitcher', 'showTemplateSwitch', ''),
(13767, 2, 'sample1', 'package_sample1', 'sample1', ''),
(13753, 1, 'edit_profile', 'package_edit_profile', 'profile_submit', ''),
(13752, 1, 'edit_profile', 'package_edit_profile', 'main', ''),
(13751, 1, 'mail', 'package_mail', 'main', ''),
(13750, 1, 'tplSwitcher', 'package_tplSwitcher', 'save', ''),
(13749, 1, 'tplSwitcher', 'package_tplSwitcher', 'main', ''),
(13766, 2, 'news', 'package_news', 'showNewsBlock', ''),
(13748, 1, 'main', 'package_main', 'main', ''),
(13765, 2, 'sample2', 'package_sample2', 'sample2', ''),
(13747, 1, 'news', 'package_news', 'showComments', ''),
(13746, 1, 'news', 'package_news', 'main', ''),
(11179, 1, 'acp_users', 'package_acp_users', 'editUserFields', 'acp'),
(11178, 1, 'acp_users', 'package_acp_users', 'addUser', 'acp'),
(11177, 1, 'acp_users', 'package_acp_users', 'searchUser', 'acp'),
(11176, 1, 'acp_users', 'package_acp_users', 'editUser', 'acp'),
(11175, 1, 'acp_users', 'package_acp_users', 'main', 'acp'),
(11174, 1, 'acp_projects', 'package_acp_projects', 'deleteRelease', 'acp'),
(11170, 1, 'acp_projects', 'package_acp_projects', 'deleteProjectNotSure', 'acp'),
(11171, 1, 'acp_projects', 'package_acp_projects', 'uploadRelease', 'acp'),
(11172, 1, 'acp_projects', 'package_acp_projects', 'uploadReleaseSave', 'acp'),
(11164, 1, 'acp_projects', 'package_acp_projects', 'main', 'acp'),
(11165, 1, 'acp_projects', 'package_acp_projects', 'createProject', 'acp'),
(11166, 1, 'acp_projects', 'package_acp_projects', 'createProjectSave', 'acp'),
(11167, 1, 'acp_projects', 'package_acp_projects', 'editProject', 'acp'),
(11180, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpNavigation', 'acp'),
(11173, 1, 'acp_projects', 'package_acp_projects', 'deleteReleaseNotSure', 'acp'),
(11169, 1, 'acp_projects', 'package_acp_projects', 'deleteProject', 'acp'),
(11168, 1, 'acp_projects', 'package_acp_projects', 'editProjectSave', 'acp'),
(11162, 1, 'acp_login', 'package_acp_login', 'loginSubmit', 'acp'),
(11163, 1, 'acp_main', 'package_acp_main', 'main', 'acp'),
(11161, 1, 'acp_login', 'package_acp_login', 'main', 'acp');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_projects`
--

CREATE TABLE IF NOT EXISTS `lttx1_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `owner` int(11) NOT NULL,
  `creationTime` int(11) NOT NULL,
  `downloads` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `lttx1_projects`
--

INSERT INTO `lttx1_projects` (`id`, `name`, `description`, `owner`, `creationTime`, `downloads`) VALUES
(8, 'Test', 'Das ist ein Test', 1, 1290803534, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_projects_releases`
--

CREATE TABLE IF NOT EXISTS `lttx1_projects_releases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectID` int(11) NOT NULL,
  `uploader` int(255) NOT NULL,
  `version` varchar(11) NOT NULL,
  `platform` varchar(11) NOT NULL,
  `changelog` text NOT NULL,
  `time` int(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `downloads` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `lttx1_projects_releases`
--

INSERT INTO `lttx1_projects_releases` (`id`, `projectID`, `uploader`, `version`, `platform`, `changelog`, `time`, `file`, `downloads`) VALUES
(15, 6, 4, '0.1.0', '0.8.x', '- Initial Release', 1289059924, 'files/packages/6/0.8.x/0.1.0.zip', 0),
(14, 6, 4, '0.1.0', '0.7.x', '- Initial Release', 1289059914, 'files/packages/6/0.7.x/0.1.0.zip', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_ressources`
--

CREATE TABLE IF NOT EXISTS `lttx1_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raceID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_ressources`
--

INSERT INTO `lttx1_ressources` (`ID`, `raceID`, `name`) VALUES
(1, 1, 'Holz');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_sessions`
--

CREATE TABLE IF NOT EXISTS `lttx1_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sessionID` varchar(128) CHARACTER SET latin1 NOT NULL COMMENT 'hashed due to privacy',
  `userID` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `currentIP` varchar(39) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`sessionID`,`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_sessions`
--

INSERT INTO `lttx1_sessions` (`id`, `time`, `sessionID`, `userID`, `username`, `currentIP`, `message`) VALUES
(1, '2010-12-31 15:08:17', '814e84110edb76571fb7820fa0fef1451bce215a7da7ef80cf2fca5885a5beccee966ee8cafc941ddd7f0c7a9a069bbc4f6e1365d98bb32be0c164adaf88d5ca', 1, 'gh1234', '::1:', 'New user registred');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_territory`
--

CREATE TABLE IF NOT EXISTS `lttx1_territory` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_territory`
--

INSERT INTO `lttx1_territory` (`ID`, `userID`, `name`) VALUES
(1, 1, 'Meine Stadt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_territoryBuildings`
--

CREATE TABLE IF NOT EXISTS `lttx1_territoryBuildings` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `territoryID` int(11) NOT NULL,
  `buildingID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `territoryID` (`territoryID`),
  KEY `buildingID` (`buildingID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_territoryBuildings`
--

INSERT INTO `lttx1_territoryBuildings` (`ID`, `territoryID`, `buildingID`, `level`) VALUES
(1, 1, 1, 25);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_territoryExplores`
--

CREATE TABLE IF NOT EXISTS `lttx1_territoryExplores` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `territoryID` int(11) NOT NULL,
  `buildingID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `territoryID` (`territoryID`),
  KEY `buildingID` (`buildingID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_territoryExplores`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_territoryRessources`
--

CREATE TABLE IF NOT EXISTS `lttx1_territoryRessources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` float NOT NULL,
  `limit` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `lttx1_territoryRessources`
--

INSERT INTO `lttx1_territoryRessources` (`ID`, `resID`, `raceID`, `sourceID`, `resNum`, `limit`) VALUES
(3, 1, 1, 1, 999960, 1000000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_tplModificationSort`
--

CREATE TABLE IF NOT EXISTS `lttx1_tplModificationSort` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `position` varchar(100) COLLATE utf8_bin NOT NULL,
  `active` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `packageDir` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `paramNum` (`active`),
  KEY `class` (`class`),
  KEY `hookName` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=930 ;

--
-- Daten für Tabelle `lttx1_tplModificationSort`
--

INSERT INTO `lttx1_tplModificationSort` (`ID`, `class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES
(161, 'package_acp_navigation', 'displayAcpNavigation', 'acpNavi', 1, 0, 'acp'),
(929, 'package_tplSwitcher', 'showTemplateSwitch', 'right', 1, 0, ''),
(928, 'package_register', 'showRegisterLink', 'left', 1, 3, ''),
(927, 'package_login', 'showLoginBox', 'left', 1, 2, ''),
(926, 'package_edit_profile', 'showProfileLink', 'left', 1, 1, ''),
(925, 'package_news', 'showNewsBlock', 'left', 1, 0, ''),
(924, 'package_sample3', 'sample3', 'none', 0, 2, ''),
(923, 'package_sample1', 'sample1', 'none', 0, 1, ''),
(922, 'package_sample2', 'sample2', 'none', 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_userFields`
--

CREATE TABLE IF NOT EXISTS `lttx1_userFields` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_bin NOT NULL,
  `type` varchar(100) COLLATE utf8_bin NOT NULL,
  `extra` text COLLATE utf8_bin NOT NULL,
  `optional` tinyint(1) NOT NULL,
  `display` tinyint(1) NOT NULL,
  `editable` tinyint(1) NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `position` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_userFields`
--

INSERT INTO `lttx1_userFields` (`ID`, `key`, `type`, `extra`, `optional`, `display`, `editable`, `package`) VALUES
(1, 'test', 'string', '', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_userGroupConnections`
--

CREATE TABLE IF NOT EXISTS `lttx1_userGroupConnections` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_userGroupConnections`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_userGroups`
--

CREATE TABLE IF NOT EXISTS `lttx1_userGroups` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `userNumber` int(11) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `default` (`default`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_userGroups`
--

INSERT INTO `lttx1_userGroups` (`ID`, `name`, `description`, `userNumber`, `default`) VALUES
(1, 'Guest', 'Guests are usually unregistered players\r\n\r\nDepending on the set up they might be registered players as well but this is unusual, thought.', 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_users`
--

CREATE TABLE IF NOT EXISTS `lttx1_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userGroup` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `password` varchar(128) CHARACTER SET latin1 NOT NULL,
  `dynamicSalt` varchar(100) CHARACTER SET latin1 NOT NULL,
  `race` int(11) DEFAULT NULL,
  `lastActive` date DEFAULT NULL,
  `isActive` TINYINT( 1 ) NOT NULL DEFAULT '1' ,
  `registerDate` date DEFAULT NULL,
  `serverAdmin` tinyint(1) NOT NULL,
  `bannedDate` TIMESTAMP NULL DEFAULT NULL,
  `bannedReason` TEXT NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `userGroup` (`userGroup`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `lttx1_users`
--

INSERT INTO `lttx1_users` (`ID`, `userGroup`, `username`, `email`, `password`, `dynamicSalt`, `race`, `lastActive`, `registerDate`, `serverAdmin`) VALUES
(1, 0, 'gh1234', 'jonas.schwabe@gmail.com', '3656ee5bdd89a43e40ba34dd397eb0998c0e83b3e218a98287073305bf6828e93c379fe2466ffc24aef7298dc41d3abac41a8410ec7c2aa78d571eecf2184f38', '_###bcßß?ßß?//\\$_baßß?//\\`cba//\\//\\1337', 0, NULL, '2010-09-15', 1),
(2, 0, 'snoop', '1111@freebg.de', '9b4e0080c66dac73d68ee2b465bd8d06aecd577901d0a4ba1a83f611a52d7f5ec769540fbbcd2f8bacbab57aa21fa0a5da544fffea6ed15f2948f7af83dfd8f3', 'b_..§§cc``mwe$b_1337ßß?_$mwe§mwe', 0, NULL, '2010-09-15', 0);
-- new
ALTER TABLE  `lttx1_packageList` ADD  `changelog` BLOB NOT NULL;

CREATE TABLE `litotex`.`lttx1_userfields_userdata` (
`field_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`value` TEXT NOT NULL ,
UNIQUE (
`field_id` ,
`user_id`
)
) ENGINE = MYISAM ;