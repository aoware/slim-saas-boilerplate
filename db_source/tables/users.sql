CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` enum('','github','facebook','google','linkedin','email') NOT NULL,
  `oauth_uid` varchar(100) NOT NULL,
  `password` char(32) NOT NULL,
  `first_name_crypted` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `last_name_crypted` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `location` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type` enum('agent','client') NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `registration_ip` varchar(40) DEFAULT NULL,
  `verification_token` char(32) DEFAULT NULL,
  `verification_date` datetime DEFAULT NULL,
  `verification_ip` varchar(40) DEFAULT NULL,
  `login_token` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_users_oauth_provider_oauth_uid` (`oauth_provider`,`oauth_uid`),
  UNIQUE KEY `IDX_users_email` (`email`),
  UNIQUE KEY `IDX_users_login_token` (`login_token`),
  UNIQUE KEY `IDX_users_verification_token` (`verification_token`),
  KEY `IDX_users_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3