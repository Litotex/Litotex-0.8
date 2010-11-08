-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. November 2010 um 19:56
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `lttx1_projects`
--

INSERT INTO `lttx1_projects` (`id`, `name`, `description`, `owner`, `creationTime`, `downloads`) VALUES
(1, 'packageServer', 'Ein Projektverwaltungspaket àla WCom.\r\n\r\nPaket Server gibt es als extra Paket.', 4, 123, 100),
(6, 'projects', 'Ein Projektverwaltungspaket àla WCom.\r\n\r\nPaket Server gibt es als extra Paket.', 4, 1289059485, 0);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
