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

# Dumping data for table litotex.lttx1_acp_navigation: 17 rows
INSERT INTO `lttx1_acp_navigation` (`ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `sort`, `tab`) VALUES (1, NULL, 'Home', 'Weil jeder wieder einmal gerne zuhause sein will.', 'home.png', '', '', 0, 0), (2, NULL, 'Einstellungen', 'Generelle Einstellungen betreffend des Litotex Core Systems.', 'process.png', '', '', 1, 0), (3, NULL, 'Statistiken', 'Genaue Informationen über Zugriffe und Systemnachrichten.', 'chart.png', '', '', 2, 0), (4, NULL, 'User', 'Einstellungen zu Usern und Gruppen.', 'user.png', '', '', 3, 0), (5, NULL, 'Applikationen', 'Paketmanager und systemnahe Einstellungen.', 'add.png', '', '', 4, 0), (6, 4, 'Usermanager', 'User hinzufügen, editieren, löschen etc.', '', 'acp_users', 'main', 1, 0), (7, 6, 'User hinzufügen', 'Neue User erstellen', '', 'acp_users', 'addUser', 2, 2), (8, 1, 'Übersichtsseite', 'Weil jeder wieder einmal gerne zuhause sein will.', '', 'acp_main', 'main', 1, 0), (9, 5, 'Paketmanager', 'Updates, neue Software, Informationen, direkt vom Team!', '', 'acp_packageManager', 'main', 0, 0), (10, 9, 'Installierte Pakete', 'Liste mit allen installierten Paketen', '', 'acp_packageManager', 'listInstalled', 1, 0), (11, 9, 'Updates anzeigen', 'Zeigt eine Liste mit allen Update (kritisch und unkritisch) an und erlaubt gleichzeitig das einspielen dieser.', '', 'acp_packageManager', 'listUpdates', 2, 0), (12, 4, 'Gruppen', 'Berechtigungsgruppen', 'nothing.png', 'acp_groups', 'main', 3, 1), (13, 3, 'Logging', 'LogFunktion', 'nothing.png', 'acp_log_viewer', 'main', 0, 0), (14, 13, 'SQL Fehler', 'Anzeige von SQL Fehlermeldungen', 'nothing', 'acp_log_viewer', 'show_log_database', 1, 0), (15, 2, 'Optionen', 's', 'nothing', 'acp_options', 'main', 1, 0), (16, 2, 'News', 'Newseditor', 'nothing', 'acp_news', 'main', 1, 0), (17, 16, 'News Kategorien', 'Anzeigen und bearbeiten der Kategorien', 'nothing', 'acp_news', 'categories_list', 1, 0);


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

# Dumping data for table litotex.lttx1_buildings: 0 rows


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

# Dumping data for table litotex.lttx1_building_dependencies: 0 rows

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

# Dumping data for table litotex.lttx1_building_ressources: 1 rows
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

# Dumping data for table litotex.lttx1_cron: 0 rows


# Dumping structure for table litotex.lttx1_error_log
DROP TABLE IF EXISTS `lttx1_error_log`;
CREATE TABLE IF NOT EXISTS `lttx1_error_log` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `traced` tinyint(1) NOT NULL,
  `backtrace` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_error_log: 4 rows


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

# Dumping data for table litotex.lttx1_explores: 0 rows


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

# Dumping data for table litotex.lttx1_explore_dependencies: 0 rows


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

# Dumping data for table litotex.lttx1_explore_ressources: 0 rows


# Dumping structure for table litotex.lttx1_file_hash
DROP TABLE IF EXISTS `lttx1_file_hash`;
CREATE TABLE IF NOT EXISTS `lttx1_file_hash` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(500) COLLATE utf8_bin NOT NULL,
  `hash` varchar(40) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `file` (`file`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_file_hash: 0 rows


# Dumping structure for table litotex.lttx1_log
DROP TABLE IF EXISTS `lttx1_log`;
CREATE TABLE IF NOT EXISTS `lttx1_log` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` text COLLATE utf8_bin NOT NULL,
  `log_type` int(3) DEFAULT '0',
  `package` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `package_action` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_log: 40 rows


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

# Dumping data for table litotex.lttx1_navigation: 10 rows
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

# Dumping data for table litotex.lttx1_news: 11 rows
INSERT INTO `lttx1_news` (`ID`, `title`, `text`, `category`, `date`, `writtenBy`, `active`, `allow_comments`) VALUES (1, 'neuer Test mit dem ACP Editor', '<p>\r\n	Hier ein kurzer Test .<br />\r\n	Die News wurde im ACP Geschrieben.<br />\r\n	<strong>ACHTUNG !!!</strong><br />\r\n	Da hier das Recht zum kommentieren gegeben ist,<br />\r\n	kann jeder diese News mit einem Kommentar versehen.</p>\r\n<p>\r\n	<br />\r\n	<br />\r\n	Ich geh mal davon aus, das sich da jemand finden wird :)<br />\r\n	<span style="color:#000000;">Das</span> <span style="color:#2f4f4f;">geht </span><span style="color:#40e0d0;">nat&uuml;rlich </span><span style="color:#0000ff;">auch </span>in <span style="color:#daa520;">allen </span><span style="color: rgb(255, 0, 0);">m&ouml;glichen </span><span style="color:#dda0dd;">Farben</span>.<br />\r\n	&nbsp;</p>\r\n', 1, '2010-05-14 22:05:40', 1, 1, 1), (2, 'Neuer TEST1!', '<div>\r\n	Wie siehts denn bisher aus?111</div>\r\n', 12, '2010-05-15 21:05:59', 0, 1, 0), (3, 'Neuer TEST!', '<html>\r\n	<head>\r\n		<title></title>\r\n	</head>\r\n	<body>\r\n		<p>\r\n			Wie siehts denn bisher aus?<br />\r\n			<br />\r\n			Ja, passt soweit ganz jut hier.<br />\r\n			<br />\r\n			noch irgend ein Problem mit [enter] aber ok</p>\r\n	</body>\r\n</html>\r\n', 1, '2010-05-15 21:05:01', 0, 0, 0), (4, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 13, '2010-05-15 21:05:25', 0, 0, 0), (6, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 1, '2010-05-15 21:05:06', 0, 0, 0), (7, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:30', 0, 0, 0), (8, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 21:05:56', 0, 1, 0), (9, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 13, '2010-05-15 21:05:13', 0, 0, 0), (10, 'Neuer TEST!', 'Wie siehts denn bisher aus?', 12, '2010-05-15 22:05:50', 0, 1, 0), (13, 'hg', '<div>\r\n	gfhgfhgfhgfh</div>\r\n', 0, '2012-01-12 15:01:49', 2, 0, 0), (14, 'und noch eine news', '<div>\r\n	diesmal aber mit einem bild<br />\r\n	oder doch nichgt :)</div>\r\n', 13, '2012-01-12 20:01:32', 1, 1, 1);


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

# Dumping data for table litotex.lttx1_news_categories: 3 rows
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
  `author_name` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `author_mail` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_news_comments: 5 rows
INSERT INTO `lttx1_news_comments` (`ID`, `title`, `text`, `date`, `news`, `writer`, `read_allowed`, `IP`, `author_name`, `author_mail`) VALUES (1, 'Test', 'Testen wir auch die Comments mal???', '2010-05-17 17:16:54', 1, 1, 0, '123.134.123.192', NULL, NULL), (3, '', 'sdsaf', '2012-01-17 13:20:34', 14, 4, 1, '::1:', '', ''), (4, '', 'das ist mein kommentar dazu', '2012-01-17 13:20:36', 14, 0, 1, '::1:', 'SiSoSnooP', 'das ist mein kommentar dazu'), (5, '', 'das ist mein nÃ¤chster kommentar', '2012-01-17 13:20:37', 14, 0, 1, '::1:', 'SiSoSnooP', 'ssnoopy@web.de'), (6, '', 'asdasd', '2012-01-17 13:50:57', 14, 4, 1, '::1:', '', '');


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
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_options: 13 rows
INSERT INTO `lttx1_options` (`ID`, `package`, `key`, `value`, `default`) VALUES (1, 'news', 'autoInformMail', '1', '1'), (2, 'news', 'autoInformPM', '1', '1'), (3, 'news', 'commentsPerSite', '50', '50'), (4, 'news', 'newsPerSite', '10', '10'), (5, 'tplSwitcher', 'tpl', 'default', 'default'), (6, 'mail', 'AdminEmailName', 'adminEmailName', 'Admin'), (7, 'mail', 'AdminEmail', 'info@freebg.de', 'info@freebg.de'), (73, 'Impressum', 'ImpressumMail', 'mustermann@musterfirma.de', 'mustermann@musterfirma.de'), (74, 'Impressum', 'ImpressumName', 'Max Mustermann', 'Max Mustermann'), (75, 'Impressum', 'ImpressumStreet', 'Musterstraße 111', 'Musterstraße 111'), (76, 'Impressum', 'ImpressumCity', '90210 Musterstadt', '90210 Musterstadt'), (77, 'Impressum', 'ImpressumTel', '+49 (0) 123 44 55 661', '+49 (0) 123 44 55 66'), (82, 'impressum', 'ImpressumFax', '+49 (0) 123 44 55 99', '+49 (0) 123 44 55 99');


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

# Dumping data for table litotex.lttx1_package_list: 2 rows
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
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_permissions: 135 rows
INSERT INTO `lttx1_permissions` (`ID`, `permissionLevel`, `associateType`, `associateID`, `package`, `function`, `class`) VALUES (1, 1, 2, 1, 'main', 'main', ''), (2, 1, 2, 1, 'acp_main', 'main', ''), (3, 1, 2, 1, 'news', 'showNewsBlock', 'package_news'), (4, 1, 2, 1, 'news', 'main', 'package_news'), (5, 1, 2, 1, 'login', 'forget_submit', 'package_login'), (6, 1, 2, 1, 'login', 'forget', 'package_login'), (7, 1, 2, 1, 'login', 'logout', 'package_login'), (8, 1, 2, 1, 'login', 'loginsubmit', 'package_login'), (9, 1, 2, 1, 'login', 'main', 'package_login'), (10, 1, 2, 1, 'login', 'showLoginBox', 'package_login'), (11, 1, 2, 1, 'acp_login', 'loginSubmit', 'package_acp_login'), (12, 1, 2, 1, 'acp_login', 'main', 'package_acp_login'), (13, 1, 2, 1, 'acp_main', 'main', 'package_acp_main'), (14, 0, 2, 1, 'acp_navigation', 'displayAcpNavigation', 'package_acp_navigation'), (15, 0, 2, 1, 'acp_projects', 'createProject', 'package_acp_projects'), (16, 0, 2, 1, 'acp_projects', 'createProjectSave', 'package_acp_projects'), (17, 0, 2, 1, 'acp_projects', 'deleteProject', 'package_acp_projects'), (18, 0, 2, 1, 'acp_projects', 'deleteProjectNotSure', 'package_acp_projects'), (19, 0, 2, 1, 'acp_projects', 'deleteRelease', 'package_acp_projects'), (20, 0, 2, 1, 'acp_projects', 'deleteReleaseNotSure', 'package_acp_projects'), (21, 0, 2, 1, 'acp_projects', 'editProject', 'package_acp_projects'), (22, 0, 2, 1, 'acp_projects', 'editProjectSave', 'package_acp_projects'), (23, 0, 2, 1, 'acp_projects', 'main', 'package_acp_projects'), (24, 0, 2, 1, 'acp_projects', 'uploadRelease', 'package_acp_projects'), (25, 0, 2, 1, 'acp_projects', 'uploadReleaseSave', 'package_acp_projects'), (26, 0, 2, 1, 'acp_users', 'addUser', 'package_acp_users'), (27, 0, 2, 1, 'acp_users', 'editUser', 'package_acp_users'), (28, 0, 2, 1, 'acp_users', 'editUserFields', 'package_acp_users'), (29, 0, 2, 1, 'acp_users', 'main', 'package_acp_users'), (30, 0, 2, 1, 'acp_users', 'searchUser', 'package_acp_users'), (31, 0, 2, 1, 'edit_profile', 'main', 'package_edit_profile'), (32, 0, 2, 1, 'edit_profile', 'profile_submit', 'package_edit_profile'), (33, 0, 2, 1, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (34, 1, 2, 1, 'errorPage', '404', 'package_errorPage'), (35, 1, 2, 1, 'mail', 'main', 'package_mail'), (36, 1, 2, 1, 'main', 'main', 'package_main'), (37, 1, 2, 1, 'news', 'showComments', 'package_news'), (38, 0, 2, 1, 'projects', 'getList', 'package_projects'), (39, 0, 2, 1, 'projects', 'main', 'package_projects'), (40, 1, 2, 1, 'register', 'main', 'package_register'), (41, 1, 2, 1, 'register', 'register_submit', 'package_register'), (42, 1, 2, 1, 'register', 'showRegisterLink', 'package_register'), (43, 0, 2, 1, 'sample1', 'sample1', 'package_sample1'), (44, 0, 2, 1, 'sample2', 'sample2', 'package_sample2'), (45, 0, 2, 1, 'sample3', 'main', 'package_sample3'), (46, 0, 2, 1, 'sample3', 'sample3', 'package_sample3'), (47, 1, 2, 1, 'tplSwitcher', 'main', 'package_tplSwitcher'), (48, 1, 2, 1, 'tplSwitcher', 'save', 'package_tplSwitcher'), (49, 1, 2, 1, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (50, 0, 2, 2, 'edit_profile', 'main', 'package_edit_profile'), (51, 0, 2, 2, 'edit_profile', 'profile_submit', 'package_edit_profile'), (52, 0, 2, 2, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (53, 1, 2, 2, 'errorPage', '404', 'package_errorPage'), (54, 1, 2, 2, 'login', 'forget', 'package_login'), (55, 1, 2, 2, 'login', 'forget_submit', 'package_login'), (56, 1, 2, 2, 'login', 'loginsubmit', 'package_login'), (57, 1, 2, 2, 'login', 'logout', 'package_login'), (58, 1, 2, 2, 'login', 'main', 'package_login'), (59, 1, 2, 2, 'login', 'showLoginBox', 'package_login'), (60, 0, 2, 2, 'mail', 'main', 'package_mail'), (61, 0, 2, 2, 'main', 'main', 'package_main'), (62, 0, 2, 2, 'news', 'main', 'package_news'), (63, 0, 2, 2, 'news', 'showComments', 'package_news'), (64, 0, 2, 2, 'news', 'showNewsBlock', 'package_news'), (65, 0, 2, 2, 'projects', 'getList', 'package_projects'), (66, 0, 2, 2, 'projects', 'main', 'package_projects'), (67, 0, 2, 2, 'register', 'main', 'package_register'), (68, 0, 2, 2, 'register', 'register_submit', 'package_register'), (69, 0, 2, 2, 'register', 'showRegisterLink', 'package_register'), (70, 0, 2, 2, 'sample1', 'sample1', 'package_sample1'), (71, 0, 2, 2, 'sample2', 'sample2', 'package_sample2'), (72, 0, 2, 2, 'sample3', 'main', 'package_sample3'), (73, 0, 2, 2, 'sample3', 'sample3', 'package_sample3'), (74, 0, 2, 2, 'tplSwitcher', 'main', 'package_tplSwitcher'), (75, 0, 2, 2, 'tplSwitcher', 'save', 'package_tplSwitcher'), (76, 0, 2, 2, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (77, 0, 1, 1, 'edit_profile', 'main', 'package_edit_profile'), (78, 0, 1, 1, 'edit_profile', 'profile_submit', 'package_edit_profile'), (79, 0, 1, 1, 'edit_profile', 'showProfileLink', 'package_edit_profile'), (80, 0, 1, 1, 'errorPage', '404', 'package_errorPage'), (81, 0, 1, 1, 'login', 'forget', 'package_login'), (82, 0, 1, 1, 'login', 'forget_submit', 'package_login'), (83, 0, 1, 1, 'login', 'loginsubmit', 'package_login'), (84, 0, 1, 1, 'login', 'logout', 'package_login'), (85, 0, 1, 1, 'login', 'main', 'package_login'), (86, 0, 1, 1, 'login', 'showLoginBox', 'package_login'), (87, 0, 1, 1, 'mail', 'main', 'package_mail'), (88, 0, 1, 1, 'main', 'main', 'package_main'), (89, 0, 1, 1, 'news', 'main', 'package_news'), (90, 0, 1, 1, 'news', 'showComments', 'package_news'), (91, 0, 1, 1, 'news', 'showNewsBlock', 'package_news'), (92, 0, 1, 1, 'projects', 'getList', 'package_projects'), (93, 0, 1, 1, 'projects', 'main', 'package_projects'), (94, 0, 1, 1, 'register', 'main', 'package_register'), (95, 0, 1, 1, 'register', 'register_submit', 'package_register'), (96, 0, 1, 1, 'register', 'showRegisterLink', 'package_register'), (97, 0, 1, 1, 'sample1', 'sample1', 'package_sample1'), (98, 0, 1, 1, 'sample2', 'sample2', 'package_sample2'), (99, 0, 1, 1, 'sample3', 'main', 'package_sample3'), (100, 0, 1, 1, 'sample3', 'sample3', 'package_sample3'), (101, 0, 1, 1, 'tplSwitcher', 'main', 'package_tplSwitcher'), (102, 0, 1, 1, 'tplSwitcher', 'save', 'package_tplSwitcher'), (103, 0, 1, 1, 'tplSwitcher', 'showTemplateSwitch', 'package_tplSwitcher'), (104, 1, 2, 1, 'content', 'main', 'package_content'), (105, 0, 2, 1, 'core_acp', 'main', 'package_core_acp'), (106, 0, 2, 1, 'core_acp', 'tplModSave', 'package_core_acp'), (107, 0, 2, 1, 'core_acp', 'tplModSort', 'package_core_acp'), (108, 1, 2, 1, 'navigation', 'displayTopNavigation', 'package_navigation'), (109, 0, 2, 1, 'navigation', 'main', 'package_navigation'), (110, 1, 2, 2, 'content', 'main', 'package_content'), (111, 0, 2, 2, 'core_acp', 'main', 'package_core_acp'), (112, 0, 2, 2, 'core_acp', 'tplModSave', 'package_core_acp'), (113, 0, 2, 2, 'core_acp', 'tplModSort', 'package_core_acp'), (114, 1, 2, 2, 'navigation', 'displayTopNavigation', 'package_navigation'), (115, 0, 2, 2, 'navigation', 'main', 'package_navigation'), (116, 1, 2, 1, 'navigation', 'displaySubNavigation', 'package_navigation'), (117, 1, 2, 2, 'navigation', 'displaySubNavigation', 'package_navigation'), (118, 1, 2, 1, 'impressum', 'main', 'package_impressum'), (119, 1, 2, 2, 'impressum', 'main', 'package_impressum'), (120, 1, 2, 1, 'privacy_policy', 'main', 'package_privacy_policy'), (121, 1, 2, 1, 'terms_and_conditions', 'main', 'package_terms_and_conditions'), (122, 1, 2, 2, 'privacy_policy', 'main', 'package_privacy_policy'), (123, 1, 2, 2, 'terms_and_conditions', 'main', 'package_terms_and_conditions'), (124, 1, 2, 1, 'screenshots', 'acp', 'package_screenshots'), (125, 1, 2, 1, 'screenshots', 'frontend', 'package_screenshots'), (126, 0, 2, 1, 'screenshots', 'main', 'package_screenshots'), (127, 1, 2, 2, 'screenshots', 'acp', 'package_screenshots'), (128, 1, 2, 2, 'screenshots', 'frontend', 'package_screenshots'), (129, 0, 2, 2, 'screenshots', 'main', 'package_screenshots'), (130, 1, 2, 1, 'game_rules', 'main', 'package_game_rules'), (131, 1, 2, 2, 'game_rules', 'main', 'package_game_rules'), (132, 0, 2, 1, 'core_buildings', 'main', 'package_core_buildings'), (133, 1, 2, 1, 'news', 'comment_submit', 'package_news'), (134, 0, 2, 2, 'core_buildings', 'main', 'package_core_buildings'), (135, 0, 2, 2, 'news', 'comment_submit', 'package_news');

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
) ENGINE=MyISAM AUTO_INCREMENT=345804 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_permissions_available: 117 rows
INSERT INTO `lttx1_permissions_available` (`ID`, `type`, `package`, `class`, `function`, `packageDir`) VALUES (342793, 1, 'tplSwitcher', 'package_tplSwitcher', 'save', ''), (342792, 1, 'tplSwitcher', 'package_tplSwitcher', 'main', ''), (342791, 1, 'terms_and_conditions', 'package_terms_and_conditions', 'main', ''), (342789, 1, 'screenshots', 'package_screenshots', 'frontend', ''), (345801, 1, 'acp_users', 'package_acp_users', 'delField', 'acp'), (345803, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpSubNavigation', 'acp'), (345802, 2, 'acp_navigation', 'package_acp_navigation', 'displayAcpTopNavigation', 'acp'), (345800, 1, 'acp_users', 'package_acp_users', 'sortFields', 'acp'), (345799, 1, 'acp_users', 'package_acp_users', 'addField', 'acp'), (345798, 1, 'acp_users', 'package_acp_users', 'fields', 'acp'), (345797, 1, 'acp_users', 'package_acp_users', 'del', 'acp'), (345796, 1, 'acp_users', 'package_acp_users', 'unban', 'acp'), (345795, 1, 'acp_users', 'package_acp_users', 'ban', 'acp'), (345794, 1, 'acp_users', 'package_acp_users', 'save', 'acp'), (345793, 1, 'acp_users', 'package_acp_users', 'list', 'acp'), (345792, 1, 'acp_users', 'package_acp_users', 'edit', 'acp'), (345790, 1, 'acp_users', 'package_acp_users', 'main', 'acp'), (345791, 1, 'acp_users', 'package_acp_users', 'new', 'acp'), (345789, 1, 'acp_tplmods', 'package_acp_tplmods', 'frame', 'acp'), (345788, 1, 'acp_tplmods', 'package_acp_tplmods', 'main', 'acp'), (345787, 1, 'acp_projects', 'package_acp_projects', 'deleteRelease', 'acp'), (345786, 1, 'acp_projects', 'package_acp_projects', 'deleteReleaseNotSure', 'acp'), (345785, 1, 'acp_projects', 'package_acp_projects', 'uploadReleaseSave', 'acp'), (345784, 1, 'acp_projects', 'package_acp_projects', 'uploadRelease', 'acp'), (345783, 1, 'acp_projects', 'package_acp_projects', 'deleteProjectNotSure', 'acp'), (345782, 1, 'acp_projects', 'package_acp_projects', 'deleteProject', 'acp'), (345781, 1, 'acp_projects', 'package_acp_projects', 'editProjectSave', 'acp'), (345780, 1, 'acp_projects', 'package_acp_projects', 'editProject', 'acp'), (345779, 1, 'acp_projects', 'package_acp_projects', 'createProjectSave', 'acp'), (345778, 1, 'acp_projects', 'package_acp_projects', 'createProject', 'acp'), (342790, 1, 'screenshots', 'package_screenshots', 'acp', ''), (342788, 1, 'screenshots', 'package_screenshots', 'main', ''), (342787, 1, 'sample3', 'package_sample3', 'main', ''), (345777, 1, 'acp_projects', 'package_acp_projects', 'main', 'acp'), (342786, 1, 'register', 'package_register', 'register_submit', ''), (342785, 1, 'register', 'package_register', 'main', ''), (342783, 1, 'projects', 'package_projects', 'main', ''), (342784, 1, 'projects', 'package_projects', 'getList', ''), (345776, 1, 'acp_permissions', 'package_acp_permissions', 'save', 'acp'), (345775, 1, 'acp_permissions', 'package_acp_permissions', 'main', 'acp'), (345774, 1, 'acp_packageManager', 'package_acp_packageManager', 'setQueueDetails', 'acp'), (345773, 1, 'acp_packageManager', 'package_acp_packageManager', 'displayUpdateQueue', 'acp'), (345772, 1, 'acp_packageManager', 'package_acp_packageManager', 'installPackage', 'acp'), (345770, 1, 'acp_packageManager', 'package_acp_packageManager', 'processUpdates', 'acp'), (345771, 1, 'acp_packageManager', 'package_acp_packageManager', 'processUpdateQueue', 'acp'), (345769, 1, 'acp_packageManager', 'package_acp_packageManager', 'updateRemoteList', 'acp'), (342782, 1, 'privacy_policy', 'package_privacy_policy', 'main', ''), (342781, 1, 'news', 'package_news', 'comment_submit', ''), (345768, 1, 'acp_packageManager', 'package_acp_packageManager', 'listUpdates', 'acp'), (345766, 1, 'acp_packageManager', 'package_acp_packageManager', 'main', 'acp'), (345767, 1, 'acp_packageManager', 'package_acp_packageManager', 'listInstalled', 'acp'), (342780, 1, 'news', 'package_news', 'showComments', ''), (342779, 1, 'news', 'package_news', 'main', ''), (342778, 1, 'main', 'package_main', 'main', ''), (342777, 1, 'mail', 'package_mail', 'main', ''), (342776, 1, 'login', 'package_login', 'forget_submit', ''), (345765, 1, 'acp_options', 'package_acp_options', 'editSubmit', 'acp'), (345764, 1, 'acp_options', 'package_acp_options', 'edit', 'acp'), (345763, 1, 'acp_options', 'package_acp_options', 'main', 'acp'), (345762, 1, 'acp_news', 'package_acp_news', 'categories_show_list', 'acp'), (345761, 1, 'acp_news', 'package_acp_news', 'categories_delete', 'acp'), (345760, 1, 'acp_news', 'package_acp_news', 'categories_save', 'acp'), (345759, 1, 'acp_news', 'package_acp_news', 'categories_edit', 'acp'), (345758, 1, 'acp_news', 'package_acp_news', 'categories_list', 'acp'), (345757, 1, 'acp_news', 'package_acp_news', 'forbid_comments', 'acp'), (345756, 1, 'acp_news', 'package_acp_news', 'allow_comments', 'acp'), (345755, 1, 'acp_news', 'package_acp_news', 'deactivate', 'acp'), (345754, 1, 'acp_news', 'package_acp_news', 'activate', 'acp'), (345753, 1, 'acp_news', 'package_acp_news', 'delete', 'acp'), (345752, 1, 'acp_news', 'package_acp_news', 'save', 'acp'), (345751, 1, 'acp_news', 'package_acp_news', 'list', 'acp'), (345750, 1, 'acp_news', 'package_acp_news', 'edit', 'acp'), (345749, 1, 'acp_news', 'package_acp_news', 'new', 'acp'), (345748, 1, 'acp_news', 'package_acp_news', 'main', 'acp'), (342775, 1, 'login', 'package_login', 'forget', ''), (342774, 1, 'login', 'package_login', 'logout', ''), (342773, 1, 'login', 'package_login', 'loginsubmit', ''), (342772, 1, 'login', 'package_login', 'main', ''), (345747, 1, 'acp_main', 'package_acp_main', 'main_redirect', 'acp'), (342803, 2, 'tplSwitcher', 'package_tplSwitcher', 'showTemplateSwitch', ''), (342770, 1, 'game_rules', 'package_game_rules', 'main', ''), (342802, 2, 'sample3', 'package_sample3', 'sample3', ''), (342801, 2, 'sample2', 'package_sample2', 'sample2', ''), (342800, 2, 'sample1', 'package_sample1', 'sample1', ''), (342799, 2, 'register', 'package_register', 'showRegisterLink', ''), (342798, 2, 'news', 'package_news', 'showNewsBlock', ''), (342771, 1, 'impressum', 'package_impressum', 'main', ''), (342797, 2, 'navigation', 'package_navigation', 'displaySubNavigation', ''), (342767, 1, 'edit_profile', 'package_edit_profile', 'main', ''), (342796, 2, 'navigation', 'package_navigation', 'displayTopNavigation', ''), (342769, 1, 'errorPage', 'package_errorPage', '404', ''), (342768, 1, 'edit_profile', 'package_edit_profile', 'profile_submit', ''), (342766, 1, 'core_buildings', 'package_core_buildings', 'main', ''), (342765, 1, 'core_acp', 'package_core_acp', 'tplModSave', ''), (342795, 2, 'login', 'package_login', 'showLoginBox', ''), (345746, 1, 'acp_main', 'package_acp_main', 'main', 'acp'), (345745, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'del_logs', 'acp'), (345744, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'database', 'acp'), (345743, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'show_log', 'acp'), (345742, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'show_log_database', 'acp'), (345741, 1, 'acp_log_viewer', 'package_acp_log_viewer', 'main', 'acp'), (345740, 1, 'acp_login', 'package_acp_login', 'loginSubmit', 'acp'), (345739, 1, 'acp_login', 'package_acp_login', 'main', 'acp'), (345738, 1, 'acp_groups', 'package_acp_groups', 'del', 'acp'), (345737, 1, 'acp_groups', 'package_acp_groups', 'save', 'acp'), (345736, 1, 'acp_groups', 'package_acp_groups', 'list', 'acp'), (345735, 1, 'acp_groups', 'package_acp_groups', 'edit', 'acp'), (345733, 1, 'acp_groups', 'package_acp_groups', 'main', 'acp'), (345734, 1, 'acp_groups', 'package_acp_groups', 'new', 'acp'), (345731, 1, 'acp_diff', 'package_acp_diff', 'main', 'acp'), (345732, 1, 'acp_errorPage', 'package_acp_errorPage', '404', 'acp'), (345730, 1, 'acp_buildings', 'package_acp_buildings', 'list', 'acp'), (345729, 1, 'acp_buildings', 'package_acp_buildings', 'main', 'acp'), (342794, 2, 'edit_profile', 'package_edit_profile', 'showProfileLink', ''), (342764, 1, 'core_acp', 'package_core_acp', 'tplModSort', ''), (342763, 1, 'core_acp', 'package_core_acp', 'main', ''), (342762, 1, 'content', 'package_content', 'main', '');


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

# Dumping data for table litotex.lttx1_projects: 1 rows
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

# Dumping data for table litotex.lttx1_projects_releases: 2 rows
INSERT INTO `lttx1_projects_releases` (`id`, `projectID`, `uploader`, `version`, `platform`, `changelog`, `time`, `file`, `downloads`) VALUES (15, 6, 4, '0.1.0', '0.8.x', '- Initial Release', 1289059924, 'files/packages/6/0.8.x/0.1.0.zip', 0), (14, 6, 4, '0.1.0', '0.7.x', '- Initial Release', 1289059914, 'files/packages/6/0.7.x/0.1.0.zip', 0);


# Dumping structure for table litotex.lttx1_ressources
DROP TABLE IF EXISTS `lttx1_ressources`;
CREATE TABLE IF NOT EXISTS `lttx1_ressources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raceID` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_ressources: 1 rows
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
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_sessions: 54 rows
INSERT INTO `lttx1_sessions` (`id`, `time`, `sessionID`, `userID`, `username`, `currentIP`, `message`) VALUES (54, '2012-01-15 21:21:07', '7fa2f261b8478bb74d7b67d8a65715edd4ad04fffda7c4c4e0bd3d100d9c4c4b13574ba6a5f5c025c197e69d944703fcab166d5ef63c8c9fe821d2247006f353', 4, '11111', '::1:', 'New user registred'), (55, '2012-01-15 21:54:33', '1f6137d768257ceafbc47f6a591eb16206ffdce4de3fd07817e518fd380c9316c41b8da42c89965e7660e1d2f037beacb8bcb54e36f36ae10fd0b1e74ad04fbc', 4, '11111', '::1:', 'New user registred'), (56, '2012-01-16 11:31:33', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (57, '2012-01-16 12:59:31', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (58, '2012-01-16 16:40:21', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (59, '2012-01-16 16:45:17', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (60, '2012-01-16 16:45:48', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (61, '2012-01-16 17:07:05', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (62, '2012-01-16 17:11:04', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (63, '2012-01-16 17:11:29', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (64, '2012-01-16 17:11:50', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (65, '2012-01-16 17:13:00', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (66, '2012-01-16 17:15:15', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (67, '2012-01-16 17:16:59', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (68, '2012-01-16 17:18:14', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (69, '2012-01-16 17:18:41', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (70, '2012-01-16 17:20:44', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (71, '2012-01-16 17:21:13', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (72, '2012-01-16 17:21:59', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (73, '2012-01-16 17:24:46', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (74, '2012-01-16 17:24:55', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (75, '2012-01-16 17:25:45', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (76, '2012-01-16 17:26:27', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (77, '2012-01-16 17:27:58', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (78, '2012-01-16 17:30:29', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (79, '2012-01-16 17:31:17', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (80, '2012-01-16 17:32:05', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (81, '2012-01-16 17:32:31', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (82, '2012-01-16 17:33:43', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (83, '2012-01-16 17:33:58', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (84, '2012-01-16 17:34:54', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (85, '2012-01-16 17:35:20', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (86, '2012-01-16 17:38:38', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (87, '2012-01-16 17:40:02', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (88, '2012-01-16 17:42:44', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (89, '2012-01-16 17:42:59', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (90, '2012-01-16 17:43:26', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (91, '2012-01-16 17:45:57', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (92, '2012-01-16 17:49:00', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (93, '2012-01-16 17:50:42', 'f089c9f8b79f6e80eeac5d298d33104cb84e73e16bc36530bda1aff17ab591dc9a5acf2e1611b7cbec763f5046a67c21d7bf9a83d2235ba287944fa3ca764307', 4, '11111', '::1:', 'New user registred'), (94, '2012-01-17 09:29:09', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (95, '2012-01-17 09:59:05', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (96, '2012-01-17 11:44:46', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (97, '2012-01-17 11:44:57', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (98, '2012-01-17 11:45:25', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (99, '2012-01-17 11:45:34', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (100, '2012-01-17 12:22:20', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (101, '2012-01-17 13:50:41', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (102, '2012-01-17 14:46:42', 'aa315ffa276e7fc211f6d56315f0e48a19b8d1a7ffd644ed1c5e4d687c60faf2d4f8be67ddb898bbe578ec57a7065e5e0d45be835ad54619190de003384c7e1d', 4, '11111', '::1:', 'New user registred'), (103, '2012-01-18 11:07:12', 'f2773e96864be78d82a5a4453f9e30332a05080a72a471b93f2310bb2a1b5e75fb9f61fa4ecdb175f35f3821972f1a9d460351a74f6a890afcad6a78de098f3e', 4, '11111', '::1:', 'New user registred'), (104, '2012-01-18 12:29:07', '278335b00417ec3b7bdea4516d8095c7bca81d127ade3a319ce260a460d887fbfa7462406a4038f9d195b745a3676fc9a23b7866bd1d3f91351bec471040bce0', 4, '11111', '::1:', 'New user registred'), (105, '2012-01-18 16:20:22', '278335b00417ec3b7bdea4516d8095c7bca81d127ade3a319ce260a460d887fbfa7462406a4038f9d195b745a3676fc9a23b7866bd1d3f91351bec471040bce0', 4, '11111', '127.0.0.1:', 'IP changed'), (106, '2012-01-18 16:50:44', '36a867dd2bea3986d5adcabc62ebe6df13d2212492cce0f5517a06b0d269fdc888a5ab50a7f6eeb78762e84183a56c7f9a8173648330f5237f861b32710c98f9', 4, '11111', '::1:', 'New user registred'), (107, '2012-01-18 17:26:51', '5be80a354b7dc5f005dd4111d4234610a387f8b33d20b71445e9ea856a8dd6a622c80ed28f74c0ff0c9c13f0ce8a288e68bdff0edb6850e05a37a1a648c53a43', 4, '11111', '::1:', 'New user registred');


# Dumping structure for table litotex.lttx1_territory
DROP TABLE IF EXISTS `lttx1_territory`;
CREATE TABLE IF NOT EXISTS `lttx1_territory` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_territory: 1 rows
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

# Dumping data for table litotex.lttx1_territory_buildings: 1 rows
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

# Dumping data for table litotex.lttx1_territory_explores: 0 rows


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

# Dumping data for table litotex.lttx1_territory_ressources: 1 rows
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
) ENGINE=MyISAM AUTO_INCREMENT=84604 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_tpl_modification_sort: 12 rows
INSERT INTO `lttx1_tpl_modification_sort` (`ID`, `class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES (84523, 'package_navigation', 'displaySubNavigation', 'SubNavi', 1, 0, ''), (84522, 'package_navigation', 'displayTopNavigation', 'TopNavi', 1, 0, ''), (84603, 'package_acp_navigation', 'displayAcpTopNavigation', 'acpTopNavi', 1, 0, 'acp'), (84521, 'package_news', 'showNewsBlock', 'content', 1, 0, ''), (84602, 'package_acp_navigation', 'displayAcpSubNavigation', 'acpSubNavi', 1, 0, 'acp'), (84520, 'package_edit_profile', 'showProfileLink', 'right', 1, 2, ''), (84519, 'package_tplSwitcher', 'showTemplateSwitch', 'right', 1, 1, ''), (84518, 'package_login', 'showLoginBox', 'right', 1, 0, ''), (84517, 'package_register', 'showRegisterLink', 'none', 0, 3, ''), (84516, 'package_sample3', 'sample3', 'none', 0, 2, ''), (84515, 'package_sample1', 'sample1', 'none', 0, 1, ''), (84514, 'package_sample2', 'sample2', 'none', 0, 0, '');


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

# Dumping data for table litotex.lttx1_userfields: 4 rows
INSERT INTO `lttx1_userfields` (`ID`, `key`, `type`, `extra`, `optional`, `display`, `editable`, `package`, `position`) VALUES (2, 'Vorname', 'input', '', 1, 1, 1, '', 0), (3, 'Nachname', 'input', '', 1, 1, 1, '', 1), (7, 'Beschrebung', 'textarea', '', 1, 1, 1, '', 0), (6, 'Meister', 'checkbox', '', 1, 1, 0, '', 0);


# Dumping structure for table litotex.lttx1_userfields_userdata
DROP TABLE IF EXISTS `lttx1_userfields_userdata`;
CREATE TABLE IF NOT EXISTS `lttx1_userfields_userdata` (
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `field_id` (`field_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_userfields_userdata: 4 rows
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
  `lastActive` datetime DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `registerDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `serverAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `bannedDate` timestamp NULL DEFAULT NULL,
  `bannedReason` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `userGroup` (`userGroup`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_users: 4 rows
INSERT INTO `lttx1_users` (`ID`, `userGroup`, `username`, `email`, `password`, `dynamicSalt`, `race`, `lastActive`, `isActive`, `registerDate`, `serverAdmin`, `bannedDate`, `bannedReason`) VALUES
(1, 0, 'tester', 'tester@example.org', 'a41d3bc2e2a28c0f7d65a473f52fb053833c9d5bd43ccb7ed874e7cbc3367e78468becb4ec996c245a2c9822016e9de4c2b5c2539858a49ec737b24f69a5aaa0', 'ßß?###$..mwe###`1337_`mwe`//\\_ccmwe..b1337', 0, NULL, 1, '2012-11-23 21:51:27', 0, NULL, NULL),
(2, 0, 'admin', 'admin@example.org', '0ec51d22002962759b68dc49edd25818229f6299cc98ef315373b673c2d28fe8bb60a9b1bff9726ab814fa73ebe388bbd38ac55cccc3252fd145d7e51718bd50', 'c§ßß?$1337mwe..b$ßß?..`$mwe``$_//\\..', 0, NULL, 1, '2012-11-23 21:51:43', 1, NULL, NULL);


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

# Dumping data for table litotex.lttx1_user_groups: 2 rows
INSERT INTO `lttx1_user_groups` (`ID`, `name`, `description`, `userNumber`, `default`) VALUES (1, 'Guest', 'Guests are usually unregistered players\r\n\r\nDepending on the set up they might be registered players as well but this is unusual, thought.', 0, 1), (2, 'Administratoren', '', 0, 0);

# Dumping structure for table litotex.lttx1_user_group_connections
DROP TABLE IF EXISTS `lttx1_user_group_connections`;
CREATE TABLE IF NOT EXISTS `lttx1_user_group_connections` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `groupID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table litotex.lttx1_user_group_connections: 7 rows
INSERT INTO `lttx1_user_group_connections` (`ID`, `userID`, `groupID`) VALUES (17, 3, 2), (33, 1, 1), (32, 1, 0), (38, 0, 1), (19, 2, 1), (31, 1, 2), (39, 4, 2);
