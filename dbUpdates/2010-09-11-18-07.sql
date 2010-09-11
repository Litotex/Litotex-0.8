ALTER TABLE `lttx1_users` ADD `lastActive` DATE NOT NULL ;
ALTER TABLE `lttx1_users` ADD `registerDate` DATE NOT NULL ;

CREATE TABLE `litotex`.`lttx1_errorLog` (
`ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
`package` VARCHAR( 100 ) NOT NULL ,
`traced` BOOLEAN NOT NULL ,
`backtrace` TEXT NOT NULL
) ENGINE = MYISAM ;
