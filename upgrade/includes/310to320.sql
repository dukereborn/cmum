UPDATE `settings` SET `cmumversion`='3.2.0', `dbversion`='3.2.0' WHERE `id`=1;
ALTER TABLE `groups` ADD `enabled` tinyint(1) NULL DEFAULT '1' AFTER `comment`;