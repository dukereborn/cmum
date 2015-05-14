UPDATE `settings` SET `cmumversion`='3.2.0', `dbversion`='3.2.0' WHERE `id`=1;
ALTER TABLE `groups` ADD `enabled` tinyint(1) NULL DEFAULT '1' AFTER `comment`;
ALTER TABLE `settings` DROP `dbversion`;
ALTER TABLE `settings` ADD `autoupdcheck` TINYINT(1) NULL AFTER `soonexpusrorder`;
UPDATE `settings` SET `autoupdcheck`='0' WHERE `id`=1;