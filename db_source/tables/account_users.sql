CREATE TABLE `account_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_account_users_account_id_user_id` (`account_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3