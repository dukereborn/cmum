SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE DATABASE IF NOT EXISTS `%cmumdbname%` DEFAULT CHARACTER SET %charset% COLLATE %charset%_general_ci;
USE `%cmumdbname%`;
CREATE TABLE IF NOT EXISTS `admins` (`id` int(11) NOT NULL,`user` varchar(25) NOT NULL,`pass` varchar(128) NOT NULL,`name` varchar(40) NOT NULL,`enabled` tinyint(1) NOT NULL,`admlvl` tinyint(1) NOT NULL,`ugroup` tinyint(6) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=2 ;
CREATE TABLE IF NOT EXISTS `groups` (`id` int(11) NOT NULL,`name` varchar(25) DEFAULT NULL,`comment` varchar(50) DEFAULT NULL,`enabled` tinyint(1) NULL DEFAULT '1') ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `log_activity` (`id` int(11) NOT NULL,`activity` tinyint(1) DEFAULT NULL,`adminid` int(11) DEFAULT NULL,`userid` int(11) DEFAULT NULL,`timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `log_genxmlreq` (`id` int(11) NOT NULL,`status` tinyint(1) NOT NULL,`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`ip` varchar(15) DEFAULT NULL,`genxmlkey` varchar(50) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `log_login` (`id` int(11) NOT NULL,`status` tinyint(1) NOT NULL,`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`ip` varchar(15) NOT NULL,`user` varchar(25) NOT NULL,`pass` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `profiles` (`id` int(11) NOT NULL,`name` varchar(25) DEFAULT NULL,`cspvalue` varchar(25) DEFAULT NULL,`comment` varchar(50) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `settings` (`id` int(1) NOT NULL,`cmumversion` varchar(8) NOT NULL,`dbversion` varchar(8) NOT NULL,`servername` varchar(25) DEFAULT NULL,`timeout` varchar(4) NOT NULL,`rndstring` varchar(75) DEFAULT NULL,`rndstringlength` varchar(2) DEFAULT NULL,`loglogins` tinyint(1) DEFAULT NULL,`logactivity` tinyint(1) DEFAULT NULL,`cleanlogin` tinyint(1) DEFAULT NULL,`genxmlkey` varchar(50) DEFAULT NULL,`genxmlusrgrp` tinyint(1) DEFAULT NULL,`genxmllogreq` tinyint(1) DEFAULT NULL,`genxmldateformat` varchar(10) DEFAULT NULL,`genxmlintstrexp` tinyint(1) DEFAULT NULL,`def_autoload` tinyint(1) DEFAULT NULL,`def_ipmask` varchar(15) DEFAULT NULL,`def_profiles` varchar(500) DEFAULT NULL,`def_maxconn` varchar(4) DEFAULT NULL,`def_admin` varchar(1) DEFAULT NULL,`def_enabled` varchar(1) DEFAULT NULL,`def_mapexc` varchar(1) DEFAULT NULL,`def_debug` varchar(1) DEFAULT NULL,`def_custcspval` varchar(255) DEFAULT NULL,`def_ecmrate` varchar(4) DEFAULT NULL,`fetchcsp` tinyint(1) DEFAULT NULL,`cspsrv_ip` varchar(255) DEFAULT NULL,`cspsrv_port` varchar(6) DEFAULT NULL,`cspsrv_user` varchar(30) DEFAULT NULL,`cspsrv_pass` varchar(30) DEFAULT NULL,`cspsrv_protocol` tinyint(1) DEFAULT NULL,`comptables` tinyint(1) DEFAULT NULL,`extrausrtbl` tinyint(1) DEFAULT NULL,`notstartusrorder` varchar(4) DEFAULT NULL,`expusrorder` varchar(4) DEFAULT NULL,`soonexpusrorder` varchar(4) DEFAULT NULL) ENGINE=InnoDB  DEFAULT CHARSET=%charset% AUTO_INCREMENT=2 ;
CREATE TABLE IF NOT EXISTS `users` (`id` int(11) NOT NULL,`user` varchar(30) DEFAULT NULL,`password` varchar(30) DEFAULT NULL,`displayname` varchar(50) DEFAULT NULL,`ipmask` varchar(15) DEFAULT NULL,`profiles` varchar(500) DEFAULT NULL,`maxconn` varchar(4) DEFAULT NULL,`admin` varchar(1) DEFAULT NULL,`enabled` varchar(1) DEFAULT NULL,`mapexclude` varchar(1) DEFAULT NULL,`debug` varchar(1) DEFAULT NULL,`comment` varchar(255) DEFAULT NULL,`email` varchar(254) DEFAULT NULL,`customvalues` varchar(255) DEFAULT NULL,`ecmrate` varchar(4) DEFAULT NULL,`startdate` date DEFAULT NULL,`expiredate` date DEFAULT NULL,`usrgroup` varchar(6) DEFAULT NULL,`boxtype` varchar(30) DEFAULT NULL,`macaddress` varchar(23) DEFAULT NULL,`serialnumber` varchar(128) DEFAULT NULL,`added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,`addedby` varchar(4) DEFAULT NULL,`changed` timestamp NULL DEFAULT NULL,`changedby` varchar(4) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=%charset% AUTO_INCREMENT=1 ;
INSERT INTO `admins` (`id`, `user`, `pass`, `name`, `enabled`, `admlvl`, `ugroup`) VALUES (1, '%aname%', '%apass%', '', 1, 0, 0);
INSERT INTO `settings` (`id`, `cmumversion`, `dbversion`, `servername`, `timeout`, `rndstring`, `rndstringlength`, `loglogins`, `logactivity`, `cleanlogin`, `genxmlkey`, `genxmlusrgrp`, `genxmllogreq`, `genxmldateformat`, `genxmlintstrexp`, `def_autoload`, `def_ipmask`, `def_profiles`, `def_maxconn`, `def_admin`, `def_enabled`, `def_mapexc`, `def_debug`, `def_custcspval`, `def_ecmrate`, `fetchcsp`, `cspsrv_ip`, `cspsrv_port`, `cspsrv_user`, `cspsrv_pass`, `cspsrv_protocol`, `comptables`, `extrausrtbl`, `notstartusrorder`, `expusrorder`, `soonexpusrorder`) VALUES (1, '3.2.0', '3.2.0', '', '600', 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789', '8', 0, 0, 0, '', 0, 0, 'd-m-Y', 0, 0, '', 'N;', '', '', '', '', '', '', '', 0, '', '', '', '', 0, 0, 0, 'asc', 'asc', 'asc');
ALTER TABLE `admins` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `groups` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `log_activity` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `log_genxmlreq` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `log_login` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `profiles` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `settings` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `admins` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `groups` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log_activity` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log_genxmlreq` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log_login` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `profiles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings` MODIFY `id` int(1) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;