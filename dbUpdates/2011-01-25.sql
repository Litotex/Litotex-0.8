--
-- Userdaten erweitern
--

ALTER TABLE `lttx1_users` ADD `bannedDate` TIMESTAMP NULL DEFAULT NULL
ALTER TABLE `lttx1_users` ADD `bannedReason` TEXT NOT NULL
ALTER TABLE `lttx1_users` ADD `isActive` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `lastActive` 