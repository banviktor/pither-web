DROP TABLE IF EXISTS `log_target`;
CREATE TABLE `log_target` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`date`)
);

DROP TABLE IF EXISTS `log_temp`;
CREATE TABLE `log_temp` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` decimal(4,1) NOT NULL,
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
INSERT INTO permissions VALUES ('access_heating_log','Access heating log');
INSERT INTO permissions VALUES ('access_rules','Access rules and overrides');
INSERT INTO permissions VALUES ('access_sensor_log','Access sensor log');
INSERT INTO permissions VALUES ('access_settings','Access settings');
INSERT INTO permissions VALUES ('create_users','Create users');
INSERT INTO permissions VALUES ('manage_overrides','Manage overrides');
INSERT INTO permissions VALUES ('manage_rules','Manage rules');
INSERT INTO permissions VALUES ('manage_settings','Manage settings');
INSERT INTO permissions VALUES ('manage_users','Manage users');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`)
);
INSERT INTO roles VALUES ('anon','Anonymous');
INSERT INTO roles VALUES ('guest','Guest');
INSERT INTO roles VALUES ('owner','Owner');
INSERT INTO roles VALUES ('user','User');

DROP TABLE IF EXISTS `roles_permissions`;
CREATE TABLE `roles_permissions` (
  `role_id` varchar(32) NOT NULL,
  `perm_id` varchar(32) NOT NULL,
  PRIMARY KEY (`role_id`,`perm_id`)
);
INSERT INTO roles_permissions VALUES ('anon','access_rules');
INSERT INTO roles_permissions VALUES ('guest','access_rules');
INSERT INTO roles_permissions VALUES ('guest','manage_overrides');
INSERT INTO roles_permissions VALUES ('owner','access_heating_log');
INSERT INTO roles_permissions VALUES ('owner','access_rules');
INSERT INTO roles_permissions VALUES ('owner','access_sensor_log');
INSERT INTO roles_permissions VALUES ('owner','access_settings');
INSERT INTO roles_permissions VALUES ('owner','create_users');
INSERT INTO roles_permissions VALUES ('owner','manage_overrides');
INSERT INTO roles_permissions VALUES ('owner','manage_rules');
INSERT INTO roles_permissions VALUES ('owner','manage_settings');
INSERT INTO roles_permissions VALUES ('owner','manage_users');
INSERT INTO roles_permissions VALUES ('user','access_heating_log');
INSERT INTO roles_permissions VALUES ('user','access_rules');
INSERT INTO roles_permissions VALUES ('user','access_sensor_log');
INSERT INTO roles_permissions VALUES ('user','access_settings');
INSERT INTO roles_permissions VALUES ('user','manage_overrides');
INSERT INTO roles_permissions VALUES ('user','manage_rules');

DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day` tinyint(1) NOT NULL DEFAULT '1',
  `start` time NOT NULL,
  `end` time NOT NULL,
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id`)
);
INSERT INTO `rules` VALUES( 1, 1, '05:00', '08:00', 21.5);
INSERT INTO `rules` VALUES( 2, 1, '16:00', '23:00', 21.5);
INSERT INTO `rules` VALUES( 3, 2, '05:00', '08:00', 21.5);
INSERT INTO `rules` VALUES( 4, 2, '16:00', '23:00', 21.5);
INSERT INTO `rules` VALUES( 5, 3, '05:00', '08:00', 21.5);
INSERT INTO `rules` VALUES( 6, 3, '16:00', '23:00', 21.5);
INSERT INTO `rules` VALUES( 7, 4, '05:00', '08:00', 21.5);
INSERT INTO `rules` VALUES( 8, 4, '16:00', '23:00', 21.5);
INSERT INTO `rules` VALUES( 9, 5, '05:00', '08:00', 21.5);
INSERT INTO `rules` VALUES(10, 5, '16:00', '23:00', 21.5);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` varchar(32) NOT NULL,
  `value` blob,
  PRIMARY KEY (`id`)
);
INSERT INTO settings VALUES ('default_unit','c');
INSERT INTO settings VALUES ('fallback_temp','17');

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
INSERT INTO users (`id`, `name`, email, pass) VALUES (1,'admin','admin@example.com',SHA1('admin'));

DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `user_id` int NOT NULL,
  `role_id` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
);
INSERT INTO users_roles VALUES (0,'anon');
INSERT INTO users_roles VALUES (1,'owner');
