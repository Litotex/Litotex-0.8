-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 20. September 2010 um 21:12
-- Server Version: 5.1.41
-- PHP-Version: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `litotex`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lttx1_tplModificationSort`
--

DROP TABLE IF EXISTS `lttx1_tplModificationSort`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=409 ;

--
-- Daten für Tabelle `lttx1_tplModificationSort`
--

INSERT INTO `lttx1_tplModificationSort` (`ID`, `class`, `function`, `position`, `active`, `sort`, `packageDir`) VALUES
(408, 'package_sample3', 'sample3', 'content', 1, 0, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(406, 'package_register', 'showRegisterLink', 'left', 1, 2, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(407, 'package_news', 'showNewsBlock', 'left', 1, 3, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(405, 'package_edit_profile', 'showProfileLink', 'left', 1, 1, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(404, 'package_login', 'showLoginBox', 'left', 1, 0, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(402, 'package_sample2', 'sample2', 'right', 1, 1, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(403, 'package_tplSwitcher', 'showTemplateSwitch', 'right', 1, 2, '/home/jonas/Dokumente/PHP/Litotex8/packages/'),
(401, 'package_sample1', 'sample1', 'right', 1, 0, '/home/jonas/Dokumente/PHP/Litotex8/packages/');

