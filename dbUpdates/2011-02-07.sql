-- phpMyAdmin SQL Dump
-- version 3.3.7deb3build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Februar 2011 um 20:14
-- Server Version: 5.1.49
-- PHP-Version: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `litotex`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_acpNavigation`
--

DROP TABLE IF EXISTS `lttx1_acpNavigation`;
CREATE TABLE IF NOT EXISTS `lttx1_acpNavigation` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` varchar(500) COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) COLLATE utf8_bin NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `action` varchar(100) COLLATE utf8_bin NOT NULL,
  `sort` int(11) NOT NULL,
  `tab` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `lttx1_acpNavigation`
--

INSERT INTO `lttx1_acpNavigation` (`ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `sort`, `tab`) VALUES
(1, NULL, 'Home', 'Weil jeder wieder einmal gerne zuhause sein will.', 'home.png', '', '', 0, 0),
(2, NULL, 'Einstellungen', 'Generelle Einstellungen betreffend des Litotex Core Systems.', 'process.png', '', '', 1, 0),
(3, NULL, 'Statistiken', 'Genaue Informationen über Zugriffe und Systemnachrichten.', 'chart.png', '', '', 2, 0),
(4, NULL, 'User', 'Einstellungen zu Usern und Gruppen.', 'user.png', '', '', 3, 0),
(5, NULL, 'Applikationen', 'Paketmanager und systemnahe Einstellungen.', 'add.png', '', '', 4, 0),
(6, 4, 'Usermanager', 'User hinzufügen, editieren, löschen etc.', '', 'acp_users', 'main', 1, 0),
(7, 6, 'User hinzufügen', 'Neue User erstellen', '', 'acp_users', 'addUser', 2, 2),
(8, 1, 'Übersichtsseite', 'Weil jeder wieder einmal gerne zuhause sein will.', '', 'main', 'main', 1, 0);

UPDATE `lttx1_tplModificationSort` SET `active` = 1, `position` = 'acpTopNavi' WHERE `class` = 'package_acp_navigation' AND `function` = 'displayAcpTopNavigation';
UPDATE `lttx1_tplModificationSort` SET `active` = 1, `position` = 'acpSubNavi' WHERE `class` = 'package_acp_navigation' AND `function` = 'displayAcpSubNavigation';
