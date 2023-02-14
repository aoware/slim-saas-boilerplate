CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `password_token` char(32) NOT NULL,
  `request_ip` varchar(40) NOT NULL,
  `create_date` datetime NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `reset_date` datetime DEFAULT NULL,
  `reset_ip` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_password_resets_password_token` (`password_token`),
  KEY `IDX_password_resets_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci