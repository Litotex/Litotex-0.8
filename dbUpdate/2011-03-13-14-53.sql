-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 13. März 2011 um 14:53
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `lttx1_acpNavigation`
--

INSERT INTO `lttx1_acpNavigation` (`ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `sort`, `tab`) VALUES
(9, 5, 'Paketmanager', 'Updates, neue Software, Informationen, direkt vom Team!', '', 'acp_packageManager', 'main', 0, 0),
(10, 9, 'Installierte Pakete', 'Liste mit allen installierten Paketen', '', 'acp_packageManager', 'listInstalled', 1, 0),
(11, 9, 'Updates anzeigen', 'Zeigt eine Liste mit allen Update (kritisch und unkritisch) an und erlaubt gleichzeitig das einspielen dieser.', '', 'acp_packageManager', 'listUpdates', 2, 0);

