UPDATE `settings` SET `cmumversion`='3.3.0' WHERE `id`=1;
ALTER TABLE `settings` ADD `email_host` VARCHAR(255) NULL AFTER `usrorder`, ADD `email_port` VARCHAR(6) NULL AFTER `email_host`, ADD `email_secure` TINYINT(1) NULL AFTER `email_port`, ADD `email_auth` TINYINT(1) NULL AFTER `email_secure`, ADD `email_authuser` VARCHAR(254) NULL AFTER `email_auth`, ADD `email_authpass` VARCHAR(50) NULL AFTER `email_authuser`, ADD `email_fromname` VARCHAR(50) NULL AFTER `email_authpass`, ADD `email_fromaddr` VARCHAR(254) NULL AFTER `email_fromname`;
UPDATE `settings` SET `email_host`='',`email_port`='',`email_secure`='0',`email_auth`='0',`email_authuser`='',`email_authpass`='',`email_fromname`='',`email_fromaddr`='' WHERE `id`=1;
UPDATE `users` SET `startdate`=NULL WHERE `startdate`=0000-00-00;
UPDATE `users` SET `expiredate`=NULL WHERE `expiredate`=0000-00-00;
ALTER TABLE `settings` ADD `invalidcharcheck` TINYINT(1) NULL AFTER `rndstringlength`;
UPDATE `settings` SET `invalidcharcheck`='1' WHERE `id`=1;