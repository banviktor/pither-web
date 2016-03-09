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
INSERT INTO permissions VALUES ('access_heating_log','Access heating log');
INSERT INTO permissions VALUES ('access_rules','Access rules and overrides');
INSERT INTO permissions VALUES ('access_sensor_log','Access sensor log');
INSERT INTO permissions VALUES ('access_settings','Access settings');
INSERT INTO permissions VALUES ('add_guests','Add guests');
INSERT INTO permissions VALUES ('add_users','Add users');
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
INSERT INTO roles_permissions VALUES ('owner','add_guests');
INSERT INTO roles_permissions VALUES ('owner','add_users');
INSERT INTO roles_permissions VALUES ('owner','manage_overrides');
INSERT INTO roles_permissions VALUES ('owner','manage_rules');
INSERT INTO roles_permissions VALUES ('owner','manage_settings');
INSERT INTO roles_permissions VALUES ('owner','manage_users');
INSERT INTO roles_permissions VALUES ('user','access_heating_log');
INSERT INTO roles_permissions VALUES ('user','access_rules');
INSERT INTO roles_permissions VALUES ('user','access_sensor_log');
INSERT INTO roles_permissions VALUES ('user','add_guests');
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

DROP TABLE IF EXISTS `sensor_log`;
CREATE TABLE `sensor_log` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` decimal(4,1) NOT NULL,
  PRIMARY KEY (`date`)
);

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
