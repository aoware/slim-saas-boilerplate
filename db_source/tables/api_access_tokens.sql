CREATE TABLE `api_access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(512) NOT NULL COMMENT 'Unique Token valid for a session',
  `api_access_key_id` int(11) NOT NULL COMMENT 'Foreign key to api_access_keys table',
  `expires_at` datetime NOT NULL COMMENT 'Expiry of the token',
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amended_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_api_access_tokens_token` (`token`),
  KEY `IDX_api_access_tokens_api_access_key_id` (`api_access_key_id`),
  CONSTRAINT `api_access_tokens_ibfk_1` FOREIGN KEY (`api_access_key_id`) REFERENCES `api_access_keys` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Api Access Tokens generated after a valid login by an integrator'