ALTER TABLE `lttx1_userfields` ADD `position` INT NOT NULL DEFAULT '0'

CREATE TABLE `litotex`.`lttx1_userfields_userdata` (
`field_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`value` TEXT NOT NULL ,
UNIQUE (
`field_id` ,
`user_id`
)
) ENGINE = MYISAM ;