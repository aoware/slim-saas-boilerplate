CREATE TABLE `config_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` enum('number','string','boolean','html','integer','array') NOT NULL DEFAULT 'string' COMMENT 'n = number, s = string, b = boolean, h=html, i=integer, b=boolean, a=array',
  `comment` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `config_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_definition_id` int(11) NOT NULL,
  `profile` varchar(16) NOT NULL DEFAULT '',
  `effective_start_date` datetime DEFAULT NULL,
  `effective_end_date` datetime DEFAULT NULL,
  `key` varchar(16) NOT NULL DEFAULT '' COMMENT 'Only required for ENGINE array',
  `value` text,
  PRIMARY KEY (`id`),
  KEY `IDX_config_value_config_definition_id` (`config_definition_id`),
  CONSTRAINT `CONST_config_value_config_definition` FOREIGN KEY (`config_definition_id`) REFERENCES `config_definition` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` enum('','github','facebook','google','linkedin','email') NOT NULL,
  `oauth_uid` varchar(100) NOT NULL,
  `password` char(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `type` enum('admin','agent','client') NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_token` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_users_oauth_provider_oauth_uid` (`oauth_provider`,`oauth_uid`),
  UNIQUE KEY `IDX_users_login_token` (`login_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
