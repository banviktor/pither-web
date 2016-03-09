DROP TABLE IF EXISTS `heating_log`;
CREATE TABLE `heating_log` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event` bit(1) NOT NULL,
  PRIMARY KEY (`date`)
);

DROP TABLE IF EXISTS `overrides`;
CREATE TABLE `overrides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`)
);

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`)
);

DROP TABLE IF EXISTS `roles_permissions`;
CREATE TABLE `roles_permissions` (
  `role_id` varchar(32) NOT NULL,
  `perm_id` varchar(32) NOT NULL,
  PRIMARY KEY (`role_id`,`perm_id`)
);

DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day` tinyint(1) NOT NULL DEFAULT '1',
  `start` time NOT NULL,
  `end` time NOT NULL,
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `sensor_log`;
CREATE TABLE `sensor_log` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`date`)
);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key` varchar(32) NOT NULL,
  `value` blob,
  PRIMARY KEY (`key`)
);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(254) NOT NULL,
  `pass` char(40) NOT NULL,
  `unit` char(1) NOT NULL DEFAULT 'c',
  `last_login` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `email_UNIQUE` (`email`)
);

DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `user_id` int NOT NULL,
  `role_id` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
);
