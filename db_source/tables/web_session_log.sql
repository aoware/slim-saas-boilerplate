CREATE TABLE `web_session_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_token` char(32) DEFAULT NULL COMMENT 'Session used by the user during this http call',
  `user_id` int(11) DEFAULT NULL COMMENT 'the user_id logged to perform the particular action',
  `ip` varchar(40) NOT NULL COMMENT 'ip address sent by the browser. In ip4 or ip6 format',
  `endpoint` varchar(255) NOT NULL COMMENT 'This is the endpoint/url receiving the payload',
  `method` enum('GET','POST') NOT NULL COMMENT 'Http method sent by the browser',
  `content_type` varchar(32) DEFAULT NULL COMMENT 'Content type sent by the browser',
  `payload` text COMMENT 'payload content',
  `http_return_code` int(11) DEFAULT NULL COMMENT 'http return code. If null, pretty likely the response failed',
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amended_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_web_session_log_user_id` (`user_id`),
  CONSTRAINT `web_session_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3