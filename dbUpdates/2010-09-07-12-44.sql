CREATE TABLE IF NOT EXISTS `lttx1_permissions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `associateType` int(11) NOT NULL,
  `associateID` int(11) NOT NULL,
  `package` varchar(100) COLLATE utf8_bin NOT NULL,
  `function` varchar(100) COLLATE utf8_bin NOT NULL,
  `class` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
CREATE TABLE `litotex`.`lttx1_permissionsAvailable` (
`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`package` VARCHAR( 100 ) NOT NULL ,
`class` VARCHAR( 100 ) NOT NULL ,
`function` VARCHAR( 100 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `lttx1_permissionsAvailable` ADD `type` INT NOT NULL AFTER `ID` ;
ALTER TABLE `lttx1_permissionsAvailable` CHANGE `type` `type` INT( 11 ) NOT NULL COMMENT '1 = action 2 = hook';

