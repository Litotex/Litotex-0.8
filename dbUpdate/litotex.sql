# Dumping structure for table litotex.lttx1_acp_navigation
DROP TABLE IF EXISTS `lttx1_acp_navigation`;
CREATE TABLE IF NOT EXISTS `lttx1_acp_navigation` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT '0',
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` varchar(500) COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) COLLATE utf8_bin NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `action` varchar(100) COLLATE utf8_bin NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `tab` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_acp_navigation` (`ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `sort`, `tab`) VALUES (1, NULL, 'Home', 'Weil jeder wieder einmal gerne zuhause sein will.', 'home.png', '', '', 0, 0), (2, NULL, 'Einstellungen', 'Generelle Einstellungen betreffend des Litotex Core Systems.', 'process.png', '', '', 1, 0), (3, NULL, 'Statistiken', 'Genaue Informationen über Zugriffe und Systemnachrichten.', 'chart.png', '', '', 2, 0), (4, NULL, 'User', 'Einstellungen zu Usern und Gruppen.', 'user.png', '', '', 3, 0), (5, NULL, 'Applikationen', 'Paketmanager und systemnahe Einstellungen.', 'add.png', '', '', 4, 0), (6, 4, 'Usermanager', 'User hinzufügen, editieren, löschen etc.', '', 'acp_users', 'main', 1, 0), (7, 6, 'User hinzufügen', 'Neue User erstellen', '', 'acp_users', 'addUser', 2, 2), (8, 1, 'Übersichtsseite', 'Weil jeder wieder einmal gerne zuhause sein will.', '', 'main', 'main', 1, 0), (9, 5, 'Paketmanager', 'Updates, neue Software, Informationen, direkt vom Team!', '', 'acp_packageManager', 'main', 0, 0), (10, 9, 'Installierte Pakete', 'Liste mit allen installierten Paketen', '', 'acp_packageManager', 'listInstalled', 1, 0), (11, 9, 'Updates anzeigen', 'Zeigt eine Liste mit allen Update (kritisch und unkritisch) an und erlaubt gleichzeitig das einspielen dieser.', '', 'acp_packageManager', 'listUpdates', 2, 0), (12, 4, 'Gruppen', 'Berechtigungsgruppen', 'nothing.png', 'acp_groups', 'main', 3, 1), (13, 3, 'Logging', 'LogFunktion', 'nothing.png', 'acp_log_viewer', 'main', 0, 0), (14, 13, 'SQL Fehler', 'Anzeige von SQL Fehlermeldungen', 'nothing', 'acp_log_viewer', 'show_log_database', 1, 0), (15, 2, 'Optionen', 's', 'nothing', 'acp_options', 'main', 1, 0), (16, 2, 'News', 'Newseditor', 'nothing', 'acp_news', 'main', 1, 0), (17, 16, 'News Kategorien', 'Anzeigen und bearbeiten der Kategorien', 'nothing', 'acp_news', 'categories_list', 1, 0);


# Dumping structure for table litotex.lttx1_buildings
DROP TABLE IF EXISTS `lttx1_buildings`;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_building_dependencies
DROP TABLE IF EXISTS `lttx1_building_dependencies`;
CREATE TABLE IF NOT EXISTS `lttx1_building_dependencies` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sourceID` int(11) NOT NULL,
  `plugin` varchar(100) COLLATE utf8_bin NOT NULL,
  `pluginPreferences` text COLLATE utf8_bin NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_building_ressources
DROP TABLE IF EXISTS `lttx1_building_ressources`;
CREATE TABLE IF NOT EXISTS `lttx1_building_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` int(11) NOT NULL,
  `increaseFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_building_ressources` (`ID`, `resID`, `raceID`, `sourceID`, `resNum`, `increaseFormula`) VALUES (1, 1, 1, 1, 20, '2*x');


# Dumping structure for table litotex.lttx1_cron
DROP TABLE IF EXISTS `lttx1_cron`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping structure for table litotex.lttx1_error_log
DROP TABLE IF EXISTS `lttx1_error_log`;
CREATE TABLE IF NOT EXISTS `lttx1_error_log` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `traced` tinyint(1) NOT NULL,
  `backtrace` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping structure for table litotex.lttx1_explores
DROP TABLE IF EXISTS `lttx1_explores`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dumping structure for table litotex.lttx1_explore_dependencies
DROP TABLE IF EXISTS `lttx1_explore_dependencies`;
CREATE TABLE IF NOT EXISTS `lttx1_explore_dependencies` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sourceID` int(11) NOT NULL,
  `plugin` varchar(100) COLLATE utf8_bin NOT NULL,
  `pluginPreferences` text COLLATE utf8_bin NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping structure for table litotex.lttx1_explore_ressources
DROP TABLE IF EXISTS `lttx1_explore_ressources`;
CREATE TABLE IF NOT EXISTS `lttx1_explore_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` int(11) NOT NULL,
  `increaseFormula` varchar(1000) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_file_hash
DROP TABLE IF EXISTS `lttx1_file_hash`;
CREATE TABLE IF NOT EXISTS `lttx1_file_hash` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(500) COLLATE utf8_bin NOT NULL,
  `hash` varchar(40) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `file` (`file`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_log
DROP TABLE IF EXISTS `lttx1_log`;
CREATE TABLE IF NOT EXISTS `lttx1_log` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` text COLLATE utf8_bin NOT NULL,
  `log_type` int(3) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_navigation
DROP TABLE IF EXISTS `lttx1_navigation`;
CREATE TABLE IF NOT EXISTS `lttx1_navigation` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT '0',
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` varchar(500) COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `package` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `action` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `tab` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_navigation` (`ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `sort`, `tab`) VALUES (1, NULL, 'Home', 'Startseite', 'home.png', '', '', 0, 0), (2, NULL, 'Registrieren', 'Registrierungsseite', 'process.png', 'register', '', 1, 0), (3, NULL, 'Screenshots', 'diverse Screenshots', 'chart.png', '', '', 2, 0), (4, NULL, 'Impressum', 'Impressum', 'add.png', '', '', 3, 0), (6, 3, 'Frontend', 'Screenshots des Frontends', '', 'screenshots', 'frontend', 2, 0), (7, 3, 'ACP', 'Screenshots des Administrationszuganges', '', 'screenshots', 'acp', 1, 0), (8, 4, 'Datenschutz', 'Datenschutzhiunweise', '', 'privacy_policy', '', 0, 0), (9, 4, 'Impressum', 'Impressum', '', 'impressum', 'main', 1, 0), (15, 4, 'AGB', 'Allgemeine Geschäftsbedingungen', NULL, 'terms_and_conditions', 'main', NULL, NULL), (16, 4, 'Spielregeln', 'Spielregeln', NULL, 'game_rules', 'main', NULL, NULL);


# Dumping structure for table litotex.lttx1_news
DROP TABLE IF EXISTS `lttx1_news`;
CREATE TABLE IF NOT EXISTS `lttx1_news` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `category` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `writtenBy` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL,
  `allow_comments` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `category` (`category`),
  KEY `title` (`title`,`date`,`writtenBy`),
  KEY `active` (`active`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_news` (`ID`, `title`, `text`, `category`, `date`, `writtenBy`, `active`, `allow_comments`) VALUES (1, 'neuer Test mit dem ACP Editor', '<p>\r\n	Hier ein kurzer Test .<br />\r\n	Die News wurde im ACP Geschrieben.<br />\r\n	<strong>ACHTUNG !!!</strong><br />\r\n	Da hier das Recht zum kommentieren gegeben ist,<br />\r\n	kann jeder diese News mit einem Kommentar versehen.</p>\r\n<p>\r\n	<br />\r\n	<br />\r\n	Ich geh mal davon aus, das sich da jemand finden wird :)<br />\r\n	<span style="color:#000000;">Das</span> <span style="color:#2f4f4f;">geht </span><span style="color:#40e0d0;">nat&uuml;rlich </span><span style="color:#0000ff;">auch </span>in <span style="color:#daa520;">allen </span><span style="color: rgb(255, 0, 0);">m&ouml;glichen </span><span style="color:#dda0dd;">Farben</span>.<br />\r\n	&nbsp;</p>\r\n', 1, '2010-05-14 22:05:40', 1, 1, 1), (2, 'Neuer TEST1!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:59', 0, 1, 0), (3, 'Neuer TEST!', '<html>\r\n	<head>\r\n		<title></title>\r\n	</head>\r\n	<body>\r\n		<p>\r\n			Wie siehts denn bisher aus?<br />\r\n			<br />\r\n			Ja, passt soweit ganz jut hier.<br />\r\n			<br />\r\n			noch irgend ein Problem mit [enter] aber ok</p>\r\n	</body>\r\n</html>\r\n', 1, '2010-05-15 21:05:01', 0, 0, 0), (4, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 13, '2010-05-15 21:05:25', 0, 0, 0), (6, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 1, '2010-05-15 21:05:06', 0, 0, 0), (7, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:30', 0, 0, 0), (8, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:56', 0, 1, 0), (9, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 13, '2010-05-15 21:05:13', 0, 0, 0), (10, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 22:05:50', 0, 1, 0), (13, 'hg', '<div>\r\n	gfhgfh<img alt="" src="http://localhost/08/files/news/_Image.png" style="width: 128px; height: 128px;" />gfhgfh</div>\r\n', 0, '2012-01-12 15:01:49', 2, 0, 0), (14, 'und noch eine news', '<div>\r\n	diesmal aber mit einem bild<br />\r\n	oder doch nichgt :)</div>\r\n', 13, '2012-01-12 20:01:32', 4, 1, 1);


# Dumping structure for table litotex.lttx1_news_categories
DROP TABLE IF EXISTS `lttx1_news_categories`;
CREATE TABLE IF NOT EXISTS `lttx1_news_categories` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `newsNum` int(11) NOT NULL DEFAULT '0',
  `newsLastDate` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `title` (`title`,`newsLastDate`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_news_categories` (`ID`, `title`, `description`, `newsNum`, `newsLastDate`) VALUES (1, 'Litotex', 'Infos über Litotex', 0, '0000-00-00 00:00:00'), (12, 'Kategorie 1', 'Nur eine Test Kategorie', 0, '2010-05-17 16:05:56'), (13, 'uu', 'uu', 0, '0000-00-00 00:00:00');


# Dumping structure for table litotex.lttx1_news_comments
DROP TABLE IF EXISTS `lttx1_news_comments`;
CREATE TABLE IF NOT EXISTS `lttx1_news_comments` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `news` int(11) NOT NULL DEFAULT '0',
  `writer` int(11) NOT NULL DEFAULT '0',
  `read_allowed` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(39) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_news_comments` (`ID`, `title`, `text`, `date`, `news`, `writer`, `read_allowed`, `IP`) VALUES (1, 'Test', 'Testen wir auch die Comments mal???', '2010-05-17 17:16:54', 1, 1, 0, '123.134.123.192');


# Dumping structure for table litotex.lttx1_options
DROP TABLE IF EXISTS `lttx1_options`;
CREATE TABLE IF NOT EXISTS `lttx1_options` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(100) CHARACTER SET latin1 NOT NULL,
  `key` varchar(100) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  `default` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `package` (`package`,`key`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES (1, 'news', 'autoInformMail', '1', '1'), (2, 'news', 'autoInformPM', '1', '1'), (3, 'news', 'commentsPerSite', '50', '50'), (4, 'news', 'newsPerSite', '10', '10'), (5, 'tplSwitcher', 'tpl', 'default', 'default'), (6, 'mail', 'AdminEmailName', 'adminEmailName', 'Admin'), (7, 'mail', 'AdminEmail', 'info@freebg.de', 'info@freebg.de'), (73, 'Impressum', 'ImpressumMail', 'mustermann@musterfirma.de', 'mustermann@musterfirma.de'), (74, 'Impressum', 'ImpressumName', 'Max Mustermann', 'Max Mustermann'), (75, 'Impressum', 'ImpressumStreet', 'Musterstraße 111', 'Musterstraße 111'), (76, 'Impressum', 'ImpressumCity', '90210 Musterstadt', '90210 Musterstadt'), (77, 'Impressum', 'ImpressumTel', '+49 (0) 123 44 55 661', '+49 (0) 123 44 55 66'), (78, 'Impressum', 'ImpressumFax', '+49 (0) 123 44 55 99', '+49 (0) 123 44 55 99');

# Dumping structure for table litotex.lttx1_package_list
DROP TABLE IF EXISTS `lttx1_package_list`;
CREATE TABLE IF NOT EXISTS `lttx1_package_list` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `prefix` varchar(30) COLLATE utf8_bin NOT NULL,
  `installed` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `critupdate` tinyint(1) NOT NULL DEFAULT '0',
  `version` varchar(20) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `author` varchar(100) COLLATE utf8_bin NOT NULL,
  `authorMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `signed` tinyint(1) NOT NULL DEFAULT '0',
  `signedOld` tinyint(1) NOT NULL DEFAULT '0',
  `fullSigned` tinyint(1) NOT NULL DEFAULT '0',
  `fullSignedOld` tinyint(1) NOT NULL DEFAULT '0',
  `signInfo` blob NOT NULL,
  `releaseDate` datetime NOT NULL,
  `dependencies` blob NOT NULL,
  `changelog` blob NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_package_list` (`ID`, `name`, `prefix`, `installed`, `update`, `critupdate`, `version`, `description`, `author`, `authorMail`, `signed`, `signedOld`, `fullSigned`, `fullSignedOld`, `signInfo`, `releaseDate`, `dependencies`, `changelog`) VALUES (1, 'login', '', 1, 1, 1, '1.0.0', 'Das ist ein Test', 'gh1234', 'jonas.schwabe@gmail.com', 0, 1, 0, 1, _binary 0x613A323A7B693A303B613A333A7B733A373A2276657273696F6E223B733A353A22302E312E30223B733A31343A22636F6D706C657465526576696577223B733A313A2230223B733A373A22636F6D6D656E74223B733A33373A22446173206973742061756368206E75722065696E20776569746572657220746573742E2E2E223B7D693A313B613A333A7B733A373A2276657273696F6E223B733A353A22302E312E30223B733A31343A22636F6D706C657465526576696577223B733A313A2231223B733A373A22636F6D6D656E74223B733A373A224D656872203A29223B7D7D, '2011-01-20 00:14:20', _binary 0x613A313A7B693A303B613A333A7B733A343A226E616D65223B733A363A226C6F67696E32223B733A31303A226D696E56657273696F6E223B733A353A22312E302E30223B733A393A22696E7374616C6C6564223B693A303B7D7D, _binary 0x613A323A7B693A303B613A353A7B733A343A2274657874223B733A31373A222D20496E697469616C2052656C65617365223B733A343A2264617465223B733A31393A22323031312D30312D32302030303A31343A3230223B733A343A2263726974223B623A303B733A333A226E6577223B693A313B733A373A2276657273696F6E223B733A353A22312E302E30223B7D693A313B613A353A7B733A343A2274657874223B733A31383A224B7269746973636865722042756766697821223B733A343A2264617465223B733A31393A22323031312D30312D32302030303A31343A3230223B733A343A2263726974223B623A313B733A333A226E6577223B693A313B733A373A2276657273696F6E223B733A353A22302E312E30223B7D7D), (2, 'login2', '', 0, 0, 0, '0.1.0', 'Nur n Test :)', 'gh1234', 'jonas.schwabe@gmail.com', 0, 0, 0, 0, _binary 0x613A303A7B7D, '2011-01-20 00:14:20', _binary 0x613A303A7B7D, _binary 0x613A313A7B693A303B613A353A7B733A343A2274657874223B733A383A22476F476F476F2121223B733A343A2264617465223B733A31393A22323031312D30312D32302030303A31343A3230223B733A343A2263726974223B623A313B733A333A226E6577223B693A303B733A373A2276657273696F6E223B733A353A22302E312E30223B7D7D);


# Dumping structure for table litotex.lttx1_permissions
DROP TABLE IF EXISTS `lttx1_permissions`;
CREATE TABLE IF NOT EXISTS `lttx1_permissions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permissionLevel` int(11) NOT NULL DEFAULT '0',
  `associateType` int(11) NOT NULL DEFAULT '0',
  `associateID` int(11) NOT NULL DEFAULT '0',
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_permissions` (`ID`, `permissionLevel`, `associateType`, `associateID`, `package`, `function`, `class`) VALUES (1, 1, 2, 1, 'main', 'main', ''), (2, 1, 2, 1, 'acp_main', 'main', ''), (3, 1, 2, 1, 'news', 'showNewsBlock', 'package_news'), (4, 1, 2, 1, 'news', 'main', 'package_news'), (5, 1, 2, 1, 'login', 'forget_submit', 'package_login'), (6, 1, 2, 1, 'login', 'forget', 'package_login'), (7, 1, 2, 1, 'login', 'logout', 'package_login'), (8, 1, 2, 1, 'login', 'loginsubmit', 'package_login'), (9, 1, 2, 1, 'login', 'main', 'package_login'), (10, 1, 2, 1, 'login', 'showLoginBox', 'package_login'), (11, 1, 2, 1, 'acp_login', 'loginSubmit', 'package_acp_login'), (12, 1, 2, 1, 'acp_login', 'main', 'package_acp_login'), (13, 1, 2, 1, 'acp_main', 'main', 'package_acp_main'), (14, 0, 2, 1, 'acp_navigation', 'displayAcpNavigation', 'package_acp_navigation'), (15, 0, 2, 1, 'acp_projects', 'createProject', 'package_acp_projects'), (16, 0, 2, 1, 'acp_projects', 'createProjectSave', 'package_acp_projects'), (17, 0, 2, 1, 'acp_projects', 'deleteProject', 'package_acp_projects'), (18, 0, 2, 1, 'acp_projects', 'deleteProjectNotSure', 'package_acp_projects'), (19, 0, 2, 1, 'acp_projects', 'deleteRelease', 'package_acp_projects'), (20, 0, 2, 1, 'acp_projects', 'deleteReleaseNotSure', 'package_acp_projects'), (21, 0, 2, 1, 'acp_projects', 'editProject', 'package_acp_projects'), (22, 0, 2, 1, 'acp_projects', 'editProjectSave', 'package_acp_projects'), (23, 0, 2, 1, 'acp_projects', 'main', 'package_acp_projects'), (24, 0, 2, 1, 'acp_projects', 'uploadRelease', 'package_acp_projects'), (25, 0, 2, 1, 'acp_projects', 'uploadReleaseSave', 'package_acp_projects'), (26, 0, 2, 1, 'acp_users', 'addUser', 'package_acp_users'), (27, 0, 2, 1, 'acp_users', 'editUser', 'package_acp_users'), (28, 0, 2, 1, 'acp_users', 'editUserFields', 'package_acp_users'), (29, 0, 2, 1, 'acp_users', 'main', 'package_acp_users'), (30, 0, 2, 1, 'acp_users', 'searchUser', 'package_acp_users'), (31, 0, 2, 1, 'edit_profile', 'main', 'package_edit_profile'), (32, 0, 2, 1, 'edit_profile', 'profile_submit', 'package_edit_profile'), (33, 0, 2, 1, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (34, 1, 2, 1, 'errorPage', '404', 'package_errorPage'), (35, 1, 2, 1, 'mail', 'main', 'package_mail'), (36, 1, 2, 1, 'main', 'main', 'package_main'), (37, 1, 2, 1, 'news', 'showComments', 'package_news'), (38, 0, 2, 1, 'projects', 'getList', 'package_projects'), (39, 0, 2, 1, 'projects', 'main', 'package_projects'), (40, 1, 2, 1, 'register', 'main', 'package_register'), (41, 1, 2, 1, 'register', 'register_submit', 'package_register'), (42, 1, 2, 1, 'register', 'showRegisterLink', 'package_register'), (43, 0, 2, 1, 'sample1', 'sample1', 'package_sample1'), (44, 0, 2, 1, 'sample2', 'sample2', 'package_sample2'), (45, 0, 2, 1, 'sample3', 'main', 'package_sample3'), (46, 0, 2, 1, 'sample3', 'sample3', 'package_sample3'), (47, 1, 2, 1, 'tplSwitcher', 'main', 'package_tplSwitcher'), (48, 1, 2, 1, 'tplSwitcher', 'save', 'package_tplSwitcher'), (49, 1, 2, 1, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (50, 0, 2, 2, 'edit_profile', 'main', 'package_edit_profile'), (51, 0, 2, 2, 'edit_profile', 'profile_submit', 'package_edit_profile'), (52, 0, 2, 2, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (53, 1, 2, 2, 'errorPage', '404', 'package_errorPage'), (54, 1, 2, 2, 'login', 'forget', 'package_login'), (55, 1, 2, 2, 'login', 'forget_submit', 'package_login'), (56, 1, 2, 2, 'login', 'loginsubmit', 'package_login'), (57, 1, 2, 2, 'login', 'logout', 'package_login'), (58, 1, 2, 2, 'login', 'main', 'package_login'), (59, 1, 2, 2, 'login', 'showLoginBox', 'package_login'), (60, 0, 2, 2, 'mail', 'main', 'package_mail'), (61, 0, 2, 2, 'main', 'main', 'package_main'), (62, 0, 2, 2, 'news', 'main', 'package_news'), (63, 0, 2, 2, 'news', 'showComments', 'package_news'), (64, 0, 2, 2, 'news', 'showNewsBlock', 'package_news'), (65, 0, 2, 2, 'projects', 'getList', 'package_projects'), (66, 0, 2, 2, 'projects', 'main', 'package_projects'), (67, 0, 2, 2, 'register', 'main', 'package_register'), (68, 0, 2, 2, 'register', 'register_submit', 'package_register'), (69, 0, 2, 2, 'register', 'showRegisterLink', 'package_register'), (70, 0, 2, 2, 'sample1', 'sample1', 'package_sample1'), (71, 0, 2, 2, 'sample2', 'sample2', 'package_sample2'), (72, 0, 2, 2, 'sample3', 'main', 'package_sample3'), (73, 0, 2, 2, 'sample3', 'sample3', 'package_sample3'), (74, 0, 2, 2, 'tplSwitcher', 'main', 'package_tplSwitcher'), (75, 0, 2, 2, 'tplSwitcher', 'save', 'package_tplSwitcher'), (76, 0, 2, 2, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (77, 0, 1, 1, 'edit_profile', 'main', 'package_edit_profile'), (78, 0, 1, 1, 'edit_profile', 'profile_submit', 'package_edit_profile'), (79, 0, 1, 1, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (80, 0, 1, 1, 'errorPage', '404', 'package_errorPage'), (81, 0, 1, 1, 'login', 'forget', 'package_login'), (82, 0, 1, 1, 'login', 'forget_submit', 'package_login'), (83, 0, 1, 1, 'login', 'loginsubmit', 'package_login'), (84, 0, 1, 1, 'login', 'logout', 'package_login'), (85, 0, 1, 1, 'login', 'main', 'package_login'), (86, 0, 1, 1, 'login', 'showLoginBox', 'package_login'), (87, 0, 1, 1, 'mail', 'main', 'package_mail'), (88, 0, 1, 1, 'main', 'main', 'package_main'), (89, 0, 1, 1, 'news', 'main', 'package_news'), (90, 0, 1, 1, 'news', 'showComments', 'package_news'), (91, 0, 1, 1, 'news', 'showNewsBlock', 'package_news'), (92, 0, 1, 1, 'projects', 'getList', 'package_projects'), (93, 0, 1, 1, 'projects', 'main', 'package_projects'), (94, 0, 1, 1, 'register', 'main', 'package_register'), (95, 0, 1, 1, 'register', 'register_submit', 'package_register'), (96, 0, 1, 1, 'register', 'showRegisterLink', 'package_register'), (97, 0, 1, 1, 'sample1', 'sample1', 'package_sample1'), (98, 0, 1, 1, 'sample2', 'sample2', 'package_sample2'), (99, 0, 1, 1, 'sample3', 'main', 'package_sample3'), (100, 0, 1, 1, 'sample3', 'sample3', 'package_sample3'), (101, 0, 1, 1, 'tplSwitcher', 'main', 'package_tplSwitcher'), (102, 0, 1, 1, 'tplSwitcher', 'save', 'package_tplSwitcher'), (103, 0, 1, 1, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (104, 1, 2, 1, 'content', 'main', 'package_content'), (105, 0, 2, 1, 'core_acp', 'main', 'package_core_acp'), (106, 0, 2, 1, 'core_acp', 'tplModSave', 'package_core_acp'), (107, 0, 2, 1, 'core_acp', 'tplModSort', 'package_core_acp'), (108, 1, 2, 1, 'navigation', 'displayTopNavigation', 'package_navigation'), (109, 0, 2, 1, 'navigation', 'main', 'package_navigation'), (110, 1, 2, 2, 'content', 'main', 'package_content'), (111, 0, 2, 2, 'core_acp', 'main', 'package_core_acp'), (112, 0, 2, 2, 'core_acp', 'tplModSave', 'package_core_acp'), (113, 0, 2, 2, 'core_acp', 'tplModSort', 'package_core_acp'), (114, 1, 2, 2, 'navigation', 'displayTopNavigation', 'package_navigation'), (115, 0, 2, 2, 'navigation', 'main', 'package_navigation'), (116, 1, 2, 1, 'navigation', 'displaySubNavigation', 'package_navigation'), (117, 1, 2, 2, 'navigation', 'displaySubNavigation', 'package_navigation'), (118, 1, 2, 1, 'impressum', 'main', 'package_impressum'), (119, 1, 2, 2, 'impressum', 'main', 'package_impressum'), (120, 1, 2, 1, 'privacy_policy', 'main', 'package_privacy_policy'), (121, 1, 2, 1, 'terms_and_conditions', 'main', 'package_terms_and_conditions'), (122, 1, 2, 2, 'privacy_policy', 'main', 'package_privacy_policy'), (123, 1, 2, 2, 'terms_and_conditions', 'main', 'package_terms_and_conditions'), (124, 1, 2, 1, 'screenshots', 'acp', 'package_screenshots'), (125, 1, 2, 1, 'screenshots', 'frontend', 'package_screenshots'), (126, 0, 2, 1, 'screenshots', 'main', 'package_screenshots'), (127, 1, 2, 2, 'screenshots', 'acp', 'package_screenshots'), (128, 1, 2, 2, 'screenshots', 'frontend', 'package_screenshots'), (129, 0, 2, 2, 'screenshots', 'main', 'package_screenshots'), (130, 1, 2, 1, 'game_rules', 'main', 'package_game_rules'), (131, 1, 2, 2, 'game_rules', 'main', 'package_game_rules');


# Dumping structure for table litotex.lttx1_permissions_available
DROP TABLE IF EXISTS `lttx1_permissions_available`;
CREATE TABLE IF NOT EXISTS `lttx1_permissions_available` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1 = action 2 = hook',
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `packageDir` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=276906 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_permissions_available` (`ID`, `type`, `package`, `class`, `function`, `packageDir`) VALUES (276895, 1, 'tplSwitcher', 'package_tplSwitcher', 'save', ''), (276894, 1, 'tplSwitcher', 'package_tplSwitcher', 'main', ''), (276893, 1, 'terms_and_conditions', 'package_terms_and_conditions', 'main', ''), (276782, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpSubNavigation', 'acp'), (276780, 1, 'acp_users', 'package_acp_users', 'delField', 'acp'), (276781, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpTopNavigation', 'acp'), (276779, 1, 'acp_users', 'package_acp_users', 'sortFields', 'acp'), (276778, 1, 'acp_users', 'package_acp_users', 'addField', 'acp'), (276777, 1, 'acp_users', 'package_acp_users', 'fields', 'acp'), (276774, 1, 'acp_users', 'package_acp_users', 'ban', 'acp'), (276775, 1, 'acp_users', 'package_acp_users', 'unban', 'acp'), (276776, 1, 'acp_users', 'package_acp_users', 'del', 'acp'), (276773, 1, 'acp_users', 'package_acp_users', 'save', 'acp'), (276772, 1, 'acp_users', 'package_acp_users', 'list', 'acp'), (276771, 1, 'acp_users', 'package_acp_users', 'edit', 'acp'), (276770, 1, 'acp_users', 'package_acp_users', 'new', 'acp'), (276769, 1, 'acp_users', 'package_acp_users', 'main', 'acp'), (276768, 1, 'acp_tplmods', 'package_acp_tplmods', 'frame', 'acp'), (276767, 1, 'acp_tplmods', 'package_acp_tplmods', 'main', 'acp'), (276766, 1, 'acp_projects', 'package_acp_projects', 'deleteRelease', 'acp'), (276765, 1, 'acp_projects', 'package_acp_projects', 'deleteReleaseNotSure', 'acp'), (276764, 1, 'acp_projects', 'package_acp_projects', 'uploadReleaseSave', 'acp'), (276763, 1, 'acp_projects', 'package_acp_projects', 'uploadRelease', 'acp'), (276762, 1, 'acp_projects', 'package_acp_projects', 'deleteProjectNotSure', 'acp'), (276761, 1, 'acp_projects', 'package_acp_projects', 'deleteProject', 'acp'), (276760, 1, 'acp_projects', 'package_acp_projects', 'editProjectSave', 'acp'), (276759, 1, 'acp_projects', 'package_acp_projects', 'editProject', 'acp'), (276758, 1, 'acp_projects', 'package_acp_projects', 'createProjectSave', 'acp'), (276757, 1, 'acp_projects', 'package_acp_projects', 'createProject', 'acp'), (276756, 1, 'acp_projects', 'package_acp_projects', 'main', 'acp'), (276755, 1, 'acp_permissions', 'package_acp_permissions', 'save', 'acp'), (276754, 1, 'acp_permissions', 'package_acp_permissions', 'main', 'acp'), (276753, 1, 'acp_packageManager', 'package_acp_packageManager', 'setQueueDetails', 'acp'), (276752, 1, 'acp_packageManager', 'package_acp_packageManager', 'displayUpdateQueue', 'acp'), (276751, 1, 'acp_packageManager', 'package_acp_packageManager', 'installPackage', 'acp'), (276749, 1, 'acp_packageManager', 'package_acp_packageManager', 'processUpdates', 'acp'), (276750, 1, 'acp_packageManager', 'package_acp_packageManager', 'processUpdateQueue', 'acp'), (276892, 1, 'screenshots', 'package_screenshots', 'acp', ''), (276891, 1, 'screenshots', 'package_screenshots', 'frontend', ''), (276890, 1, 'screenshots', 'package_screenshots', 'main', ''), (276889, 1, 'sample3', 'package_sample3', 'main', ''), (276887, 1, 'register', 'package_register', 'main', ''), (276888, 1, 'register', 'package_register', 'register_submit', ''), (276748, 1, 'acp_packageManager', 'package_acp_packageManager', 'updateRemoteList', 'acp'), (276886, 1, 'projects', 'package_projects', 'getList', ''), (276885, 1, 'projects', 'package_projects', 'main', ''), (276884, 1, 'privacy_policy', 'package_privacy_policy', 'main', ''), (276883, 1, 'news', 'package_news', 'showComments', ''), (276882, 1, 'news', 'package_news', 'main', ''), (276881, 1, 'main', 'package_main', 'main', ''), (276880, 1, 'mail', 'package_mail', 'main', ''), (276746, 1, 'acp_packageManager', 'package_acp_packageManager', 'listInstalled', 'acp'), (276747, 1, 'acp_packageManager', 'package_acp_packageManager', 'listUpdates', 'acp'), (276745, 1, 'acp_packageManager', 'package_acp_packageManager', 'main', 'acp'), (276744, 1, 'acp_options', 'package_acp_options', 'save', 'acp'), (276743, 1, 'acp_options', 'package_acp_options', 'list', 'acp'), (276742, 1, 'acp_options', 'package_acp_options', 'edit', 'acp'), (276741, 1, 'acp_options', 'package_acp_options', 'main', 'acp'), (276740, 1, 'acp_news', 'package_acp_news', 'categories_show_list', 'acp'), (276739, 1, 'acp_news', 'package_acp_news', 'categories_delete', 'acp'), (276738, 1, 'acp_news', 'package_acp_news', 'categories_save', 'acp'), (276737, 1, 'acp_news', 'package_acp_news', 'categories_edit', 'acp'), (276736, 1, 'acp_news', 'package_acp_news', 'categories_list', 'acp'), (276733, 1, 'acp_news', 'package_acp_news', 'deactivate', 'acp'), (276879, 1, 'login', 'package_login', 'forget_submit', ''), (276878, 1, 'login', 'package_login', 'forget', ''), (276877, 1, 'login', 'package_login', 'logout', ''), (276735, 1, 'acp_news', 'package_acp_news', 'forbid_comments', 'acp'), (276734, 1, 'acp_news', 'package_acp_news', 'allow_comments', 'acp'), (276732, 1, 'acp_news', 'package_acp_news', 'activate', 'acp'), (276731, 1, 'acp_news', 'package_acp_news', 'delete', 'acp'), (276876, 1, 'login', 'package_login', 'loginsubmit', ''), (276875, 1, 'login', 'package_login', 'main', ''), (276874, 1, 'impressum', 'package_impressum', 'main', ''), (276873, 1, 'game_rules', 'package_game_rules', 'main', ''), (276872, 1, 'errorPage', 'package_errorPage', '404', ''), (276871, 1, 'edit_profile', 'package_edit_profile', 'profile_submit', ''), (276870, 1, 'edit_profile', 'package_edit_profile', 'main', ''), (276730, 1, 'acp_news', 'package_acp_news', 'save', 'acp'), (276729, 1, 'acp_news', 'package_acp_news', 'list', 'acp'), (276728, 1, 'acp_news', 'package_acp_news', 'edit', 'acp'), (276727, 1, 'acp_news', 'package_acp_news', 'new', 'acp'), (276726, 1, 'acp_news', 'package_acp_news', 'main', 'acp'), (276725, 1, 'acp_main', 'package_acp_main', 'main_redirect', 'acp'), (276724, 1, 'acp_main', 'package_acp_main', 'main', 'acp'), (276723, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'del_logs', 'acp'), (276722, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'database', 'acp'), (276721, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'show_log', 'acp'), (276720, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'show_log_database', 'acp'), (276719, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'main', 'acp'), (276718, 1, 'acp_login', 'package_acp_login', 'loginSubmit', 'acp'), (276717, 1, 'acp_login', 'package_acp_login', 'main', 'acp'), (276716, 1, 'acp_groups', 'package_acp_groups', 'del', 'acp'), (276715, 1, 'acp_groups', 'package_acp_groups', 'save', 'acp'), (276714, 1, 'acp_groups', 'package_acp_groups', 'list', 'acp'), (276713, 1, 'acp_groups', 'package_acp_groups', 'edit', 'acp'), (276712, 1, 'acp_groups', 'package_acp_groups', 'new', 'acp'), (276711, 1, 'acp_groups', 'package_acp_groups', 'main', 'acp'), (276710, 1, 'acp_errorPage', 'package_acp_errorPage', '404', 'acp'), (276709, 1, 'acp_diff', 'package_acp_diff', 'main', 'acp'), (276708, 1, 'acp_buildings', 'package_acp_buildings', 'list', 'acp'), (276707, 1, 'acp_buildings', 'package_acp_buildings', 'main', 'acp'), (276869, 1, 'core_buildings', 'package_core_buildings', 'main', ''), (276868, 1, 'core_acp', 'package_core_acp', 'tplModSave', ''), (276867, 1, 'core_acp', 'package_core_acp', 'tplModSort', ''), (276866, 1, 'core_acp', 'package_core_acp', 'main', ''), (276905, 2, 'tplSwitcher', 'package_tplSwitcher', 'showTemplateSwitch', ''), (276904, 2, 'sample3', 'package_sample3', 'sample3', ''), (276903, 2, 'sample2', 'package_sample2', 'sample2', ''), (276865, 1, 'content', 'package_content', 'main', ''), (276901, 2, 'register', 'package_register', 'showRegisterLink', ''), (276902, 2, 'sample1', 'package_sample1', 'sample1', ''), (276900, 2, 'news', 'package_news', 'showNewsBlock', ''), (276899, 2, 'navigation', 'package_navigation', 'displaySubNavigation', ''), (276898, 2, 'navigation', 'package_navigation', 'displayTopNavigation', ''), (276897, 2, 'login', 'package_login', 'showLoginBox', ''), (276896, 2, 'edit_profile', 'package_edit_profile', 'showProfileLink', '');


# Dumping structure for table litotex.lttx1_projects
DROP TABLE IF EXISTS `lttx1_projects`;
CREATE TABLE IF NOT EXISTS `lttx1_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `creationTime` int(11) NOT NULL DEFAULT '0',
  `downloads` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `lttx1_projects` (`id`, `name`, `description`, `owner`, `creationTime`, `downloads`) VALUES (8, 'Test', 'Das ist ein Test', 1, 1290803534, 0);


# Dumping structure for table litotex.lttx1_projects_releases
DROP TABLE IF EXISTS `lttx1_projects_releases`;
CREATE TABLE IF NOT EXISTS `lttx1_projects_releases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectID` int(11) NOT NULL DEFAULT '0',
  `uploader` int(255) NOT NULL DEFAULT '0',
  `version` varchar(11) NOT NULL,
  `platform` varchar(11) NOT NULL,
  `changelog` text NOT NULL,
  `time` int(255) NOT NULL DEFAULT '0',
  `file` varchar(255) NOT NULL,
  `downloads` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

INSERT INTO `lttx1_projects_releases` (`id`, `projectID`, `uploader`, `version`, `platform`, `changelog`, `time`, `file`, `downloads`) VALUES (15, 6, 4, '0.1.0', '0.8.x', '- Initial Release', 1289059924, 'files/packages/6/0.8.x/0.1.0.zip', 0), (14, 6, 4, '0.1.0', '0.7.x', '- Initial Release', 1289059914, 'files/packages/6/0.7.x/0.1.0.zip', 0);


# Dumping structure for table litotex.lttx1_ressources
DROP TABLE IF EXISTS `lttx1_ressources`;
CREATE TABLE IF NOT EXISTS `lttx1_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raceID` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_ressources` (`ID`, `raceID`, `name`) VALUES (1, 1, 'Holz');


# Dumping structure for table litotex.lttx1_sessions
DROP TABLE IF EXISTS `lttx1_sessions`;
CREATE TABLE IF NOT EXISTS `lttx1_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sessionID` varchar(128) CHARACTER SET latin1 NOT NULL COMMENT 'hashed due to privacy',
  `userID` int(11) NOT NULL DEFAULT '0',
  `username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `currentIP` varchar(39) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`sessionID`,`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping structure for table litotex.lttx1_territory
DROP TABLE IF EXISTS `lttx1_territory`;
CREATE TABLE IF NOT EXISTS `lttx1_territory` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_territory` (`ID`, `userID`, `name`) VALUES (1, 1, 'Meine Stadt');


# Dumping structure for table litotex.lttx1_territory_buildings
DROP TABLE IF EXISTS `lttx1_territory_buildings`;
CREATE TABLE IF NOT EXISTS `lttx1_territory_buildings` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `territoryID` int(11) NOT NULL,
  `buildingID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `territoryID` (`territoryID`),
  KEY `buildingID` (`buildingID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_territory_buildings` (`ID`, `territoryID`, `buildingID`, `level`) VALUES (1, 1, 1, 25);


# Dumping structure for table litotex.lttx1_territory_explores
DROP TABLE IF EXISTS `lttx1_territory_explores`;
CREATE TABLE IF NOT EXISTS `lttx1_territory_explores` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `territoryID` int(11) NOT NULL,
  `buildingID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `territoryID` (`territoryID`),
  KEY `buildingID` (`buildingID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


# Dumping structure for table litotex.lttx1_territory_ressources
DROP TABLE IF EXISTS `lttx1_territory_ressources`;
CREATE TABLE IF NOT EXISTS `lttx1_territory_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resID` int(11) NOT NULL,
  `raceID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `resNum` float NOT NULL,
  `limit` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `resID` (`resID`,`raceID`,`sourceID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_territory_ressources` (`ID`, `resID`, `raceID`, `sourceID`, `resNum`, `limit`) VALUES (3, 1, 1, 1, 999960, 1000000);

# Dumping structure for table litotex.lttx1_tpl_modification_sort
DROP TABLE IF EXISTS `lttx1_tpl_modification_sort`;
CREATE TABLE IF NOT EXISTS `lttx1_tpl_modification_sort` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `position` varchar(100) COLLATE utf8_bin NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `packageDir` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `paramNum` (`active`),
  KEY `class` (`class`),
  KEY `hookName` (`position`)
) ENGINE=MyISAM AUTO_INCREMENT=79616 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_tpl_modification_sort` (`ID`, `class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES (79615, 'package_news', 'showNewsBlock', 'content', 1, 0, ''), (79614, 'package_navigation', 'displayTopNavigation', 'TopNavi', 1, 0, ''), (79613, 'package_navigation', 'displaySubNavigation', 'SubNavi', 1, 0, ''), (79585, 'package_acp_navigation', 'displayAcpTopNavigation', 'acpTopNavi', 1, 0, 'acp'), (79611, 'package_tplSwitcher', 'showTemplateSwitch', 'right', 1, 1, ''), (79584, 'package_acp_navigation', 'displayAcpSubNavigation', 'acpSubNavi', 1, 0, 'acp'), (79612, 'package_edit_profile', 'showProfileLink', 'right', 1, 2, ''), (79610, 'package_login', 'showLoginBox', 'right', 1, 0, ''), (79609, 'package_register', 'showRegisterLink', 'none', 0, 3, ''), (79608, 'package_sample3', 'sample3', 'none', 0, 2, ''), (79607, 'package_sample1', 'sample1', 'none', 0, 1, ''), (79606, 'package_sample2', 'sample2', 'none', 0, 0, '');


# Dumping structure for table litotex.lttx1_userfields
DROP TABLE IF EXISTS `lttx1_userfields`;
CREATE TABLE IF NOT EXISTS `lttx1_userfields` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_bin NOT NULL,
  `type` varchar(100) COLLATE utf8_bin NOT NULL,
  `extra` text COLLATE utf8_bin NOT NULL,
  `optional` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_userfields` (`ID`, `key`, `type`, `extra`, `optional`, `display`, `editable`, `package`, `position`) VALUES (2, 'Vorname', 'input', '', 1, 1, 1, '', 0), (3, 'Nachname', 'input', '', 1, 1, 1, '', 1), (7, 'Beschrebung', 'textarea', '', 1, 1, 1, '', 0), (6, 'Meister', 'checkbox', '', 1, 1, 0, '', 0);


# Dumping structure for table litotex.lttx1_userfields_userdata
DROP TABLE IF EXISTS `lttx1_userfields_userdata`;
CREATE TABLE IF NOT EXISTS `lttx1_userfields_userdata` (
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `field_id` (`field_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_userfields_userdata` (`field_id`, `user_id`, `value`) VALUES (3, 1, 'Schwabe'), (2, 1, 'Jonas'), (6, 1, '1'), (7, 1, 'Hello folks :)');


# Dumping structure for table litotex.lttx1_users
DROP TABLE IF EXISTS `lttx1_users`;
CREATE TABLE IF NOT EXISTS `lttx1_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userGroup` int(11) NOT NULL DEFAULT '0',
  `username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `password` varchar(128) CHARACTER SET latin1 NOT NULL,
  `dynamicSalt` varchar(100) CHARACTER SET latin1 NOT NULL,
  `race` int(11) DEFAULT '0',
  `lastActive` date DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `registerDate` date DEFAULT NULL,
  `serverAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `bannedDate` timestamp NULL DEFAULT NULL,
  `bannedReason` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `userGroup` (`userGroup`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_users` (`ID`, `userGroup`, `username`, `email`, `password`, `dynamicSalt`, `race`, `lastActive`, `isActive`, `registerDate`, `serverAdmin`, `bannedDate`, `bannedReason`) VALUES (1, 0, 'gh1234', 'meinee@meinemail.com', '3c3a6455e963f07cb69821226e54e9f65bf652545f2eb32bd37b5ad0cc070bfde5531af298796116f2fb29d4c83852c6e22040dd41129e38edf2c69d61934552', '######$bmwe..//\\$###$a//\\cb###$b_§a', 0, '2011-02-27', 1, '2010-09-15', 1, NULL, ''), (2, 0, 'snoop', 'info@litotex.net', '68ddcc4abb7788bc7c112d82c9b192a1325627be7b7fbad34591898a3ac6f42516f02db3f6da582359f3a32e499efe8f1e9ba1c661ec479a642bbc5f301e0bc8', 'b$mwe###1337###//\\//\\..//\\mwe....`§b`//\\ßß?###', 0, '2012-01-12', 1, '2010-09-15', 1, NULL, ''), (3, 0, 'Meister', 'root@meister.com', 'f6ee2f2b4a21c16b71d92beb3c77bce358bb0e41e2f5036e9e317990ea6aa8d70a4c400110a8caaaab3482ee09033a8b39c9aa76ae2716652b3b06f2e04c7ae0', '..$ßß?.._b$aßß?$$§c//\\1337aab1337_', NULL, NULL, 1, NULL, 1, '0000-00-00 00:00:00', ''), (4, 0, '11111', 'testy@web.de', 'f9c84c6349e274cf5f4206c55a1b2b90baa488f360b6131d49e8e678cfd8993929b9444fd728ce62c7f7ae42c2fde93686517db6d00aa826f57c8d06efd3d863', 'mwe_$//\\//\\mwe###..###_b//\\mwemwe###`_`aÂ§', NULL, '2012-01-14', 1, NULL, 1, NULL, '');


# Dumping structure for table litotex.lttx1_user_groups
DROP TABLE IF EXISTS `lttx1_user_groups`;
CREATE TABLE IF NOT EXISTS `lttx1_user_groups` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `userNumber` int(11) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `default` (`default`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_user_groups` (`ID`, `name`, `description`, `userNumber`, `default`) VALUES (1, 'Guest', 'Guests are usually unregistered players\r\n\r\nDepending on the set up they might be registered players as well but this is unusual, thought.', 0, 1), (2, 'Administratoren', '', 0, 0);


# Dumping structure for table litotex.lttx1_user_group_connections
DROP TABLE IF EXISTS `lttx1_user_group_connections`;
CREATE TABLE IF NOT EXISTS `lttx1_user_group_connections` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `groupID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lttx1_user_group_connections` (`ID`, `userID`, `groupID`) VALUES (17, 3, 2), (33, 1, 1), (32, 1, 0), (38, 0, 1), (19, 2, 1), (31, 1, 2);
