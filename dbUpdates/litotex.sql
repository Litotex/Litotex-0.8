-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. September 2010 um 14:47
-- Server Version: 5.1.41
-- PHP-Version: 5.3.2-1ubuntu4.2

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
  `increaseFormula` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
  `name` varchar(100) NOT NULL,
  `race` int(11) NOT NULL,
  `plugin` text NOT NULL,
  `pluginPreferences` text NOT NULL,
  `timeFormula` varchar(1000) NOT NULL,
  `pointsFormula` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `race` (`race`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
  `textID` varchar(100) NOT NULL,
  `serialized` text NOT NULL,
  `function` varchar(100) NOT NULL,
  `params` text NOT NULL,
  `nextInt` int(11) NOT NULL,
  `interval` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `blockUserID` varchar(100) NOT NULL,
  `dependencies` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `textID` (`textID`),
  KEY `interval` (`interval`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=756 ;

--
-- Daten für Tabelle `lttx1_cron`
--

INSERT INTO `lttx1_cron` (`ID`, `textID`, `serialized`, `function`, `params`, `nextInt`, `interval`, `userID`, `blockUserID`, `dependencies`) VALUES
(752, 'ressource1_1_1', 'O:9:"ressource":12:{s:23:"\0ressource\0_initialized";b:1;s:16:"\0ressource\0_race";i:1;s:15:"\0ressource\0_res";a:1:{i:1;d:999950;}s:22:"\0ressource\0_resFormula";a:0:{}s:23:"\0ressource\0_saveChanges";b:1;s:15:"\0ressource\0_src";i:1;s:17:"\0ressource\0_table";s:9:"territory";s:19:"\0ressource\0_changed";a:1:{i:0;i:1;}s:26:"\0ressource\0_resNameFetched";b:0;s:17:"\0ressource\0_limit";a:1:{i:1;s:7:"1000000";}s:30:"\0ressource\0_useIncreaseFormula";b:0;s:20:"\0ressource\0_useLimit";b:1;}', 'simpleAddition', 'a:4:{i:0;i:1;i:1;d:0.138888888888888895056794581250869669020175933837890625;i:2;s:7:"$intNum";i:3;b:1;}', 1283690514, 1, 1, '', 'a:1:{i:0;s:14:"core_buildings";}');

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
  `increaseFormula` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_exploreRessources`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_explores`
--

CREATE TABLE IF NOT EXISTS `lttx1_explores` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `race` int(11) NOT NULL,
  `plugin` text NOT NULL,
  `pluginPreferences` text NOT NULL,
  `timeFormula` varchar(1000) NOT NULL,
  `pointsFormula` varchar(1000) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `race` (`race`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lttx1_explores`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_news`
--

CREATE TABLE IF NOT EXISTS `lttx1_news` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `category` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `commentNum` int(11) NOT NULL,
  `writtenBy` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `category` (`category`),
  KEY `title` (`title`,`date`,`writtenBy`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `newsNum` int(11) NOT NULL,
  `newsLastDate` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `title` (`title`,`newsLastDate`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

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
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `news` int(11) NOT NULL,
  `writer` int(11) NOT NULL,
  `IP` varchar(39) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
  `package` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `default` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `package` (`package`,`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `lttx1_options`
--

INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES
(1, 'news', 'autoInformMail', '1', '1'),
(2, 'news', 'autoInformPM', '1', '1'),
(3, 'news', 'commentsPerSite', '50', '50'),
(4, 'news', 'newsPerSite', '10', '10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_ressources`
--

CREATE TABLE IF NOT EXISTS `lttx1_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raceID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
  `sessionID` varchar(128) NOT NULL COMMENT 'hashed due to privacy',
  `userID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `currentIP` varchar(39) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`sessionID`,`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Daten für Tabelle `lttx1_sessions`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_territory`
--

CREATE TABLE IF NOT EXISTS `lttx1_territory` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `lttx1_territoryRessources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` float NOT NULL,
  `limit` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `lttx1_territoryRessources`
--

INSERT INTO `lttx1_territoryRessources` (`ID`, `resID`, `raceID`, `sourceID`, `resNum`, `limit`) VALUES
(3, 1, 1, 1, 1e+06, 1000000);

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
  PRIMARY KEY (`ID`),
  KEY `paramNum` (`active`),
  KEY `class` (`class`),
  KEY `hookName` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_tplModificationSort`
--

INSERT INTO `lttx1_tplModificationSort` (`ID`, `class`, `function`, `position`, `active`, `sort`) VALUES
(1, 'package_news', 'showNewsBlock', 'left', 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_userGroupConnections`
--

CREATE TABLE IF NOT EXISTS `lttx1_userGroupConnections` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_userGroupConnections`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_userGroups`
--

CREATE TABLE IF NOT EXISTS `lttx1_userGroups` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `userNumber` int(11) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `default` (`default`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `lttx1_userGroups`
--

INSERT INTO `lttx1_userGroups` (`ID`, `name`, `description`, `userNumber`, `default`) VALUES
(1, 'Guest', 'Guests are usually unregistered players\r\n\r\nDepending on the set up they might be registered players as well but this is unusual, thought.', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_users`
--

CREATE TABLE IF NOT EXISTS `lttx1_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userGroup` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `dynamicSalt` varchar(100) NOT NULL,
  `race` int(11) DEFAULT NULL,
  `lastActive` date DEFAULT NULL,
  `registerDate` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `userGroup` (`userGroup`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `lttx1_users`
--

INSERT INTO `lttx1_users` (`ID`, `userGroup`, `username`, `email`, `password`, `dynamicSalt`, `race`) VALUES
(1, 0, 'GH1234', '', '39823a1dd1a2c77432afe134a89d0876014ebab95262969fe2c6a7c7b25e49696775ffef1f7742196c91bc751904d3a560876cef354654ca4078f3d342f93205', '`caÂ§Â§$_b....a1337Â§..`Â§1337Â§`..', 1),
(3, 0, 'test', 'lala', 'bb2105b07ccdc4784035d38af0201b826c4f3bb5', '$1337cÂ§bmwemwe//\\`Â§Â§###`b$1337aÃŸÃŸ?$..', 0);

-- Updates...

CREATE TABLE IF NOT EXISTS `lttx1_permissions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `associateType` int(11) NOT NULL,
  `associateID` int(11) NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
CREATE TABLE `lttx1_permissionsAvailable` (
`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`package` VARCHAR( 100 ) NOT NULL ,
`class` VARCHAR( 100 ) NOT NULL ,
`function` VARCHAR( 100 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `lttx1_permissionsAvailable` ADD `type` INT NOT NULL AFTER `ID` ;
ALTER TABLE `lttx1_permissionsAvailable` CHANGE `type` `type` INT( 11 ) NOT NULL COMMENT '1 = action 2 = hook';

CREATE TABLE `lttx1_errorLog` (
`ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
`package` VARCHAR( 100 ) NOT NULL ,
`traced` BOOLEAN NOT NULL ,
`backtrace` TEXT NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE = MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `lttx1_log` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `logdate` datetime NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `lttx1_users`;
CREATE TABLE IF NOT EXISTS `lttx1_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userGroup` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `dynamicSalt` varchar(100) NOT NULL,
  `race` int(11) DEFAULT NULL,
  `lastActive` date DEFAULT NULL,
  `registerDate` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `userGroup` (`userGroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `lttx1_users`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_buildings`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_buildingRessources`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_cron`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
TRUNCATE TABLE `lttx1_cron`;
ALTER TABLE `lttx1_exploreRessources`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_explores`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_news`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_newsCategories`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_newsComments`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_options`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_ressources`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_sessions`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_territory`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_territoryBuildings`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_territoryExplores`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_territoryRessources`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_userGroupConnections`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `lttx1_userGroups`  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES (27, 'mail', 'AdminEmail', 'info@freebg.de', 'info@freebg.de');
INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES (28, 'mail', 'AdminEmailName', 'adminEmailName', 'Admin');

