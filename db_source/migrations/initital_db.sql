CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_accounts_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `config_definition` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` enum('number','string','boolean','html','integer','array') NOT NULL DEFAULT 'string' COMMENT 'n = number, s = string, b = boolean, h=html, i=integer, b=boolean, a=array',
  `comment` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config_definition` (`id`, `group`, `name`, `type`, `comment`) VALUES
(1,     'smtp',      'CONF_smtp_host',            'string', ''),
(2, 	'smtp',      'CONF_smtp_port',            'integer',''),
(3, 	'smtp',      'CONF_smtp_username',        'string', ''),
(4, 	'smtp',      'CONF_smtp_password',        'string', ''),
(5, 	'smtp',      'CONF_smtp_sender_email',    'string', ''),
(6, 	'smtp',      'CONF_smtp_sender_name',     'string', ''),
(7, 	'telegram',  'CONF_telegram_bot_id',      'integer',''),
(8, 	'telegram',  'CONF_telegram_bot_token',   'string', ''),
(9, 	'telegram',  'CONF_telegram_admin_id',    'integer',''),
(10,    'telegram',  'CONF_telegram_alert_ids',   'array',  ''),
(11,    'bac',       'CONF_bac_key',              'string', ''),
(12,    'bac',       'CONF_bac_password',         'string', ''),
(13,    'jwt',       'CONF_jwt_secret',           'string', ''),
(14,    'jwt', 		 'CONF_jwt_secret',           'bolean', ''),
(15,    'encryption','CONF_encryption_key',       'string', ''),
(16,    'encryption','CONF_encryption_method',    'string', ''),
(17,    'tracking',  'CONF_tracking',             'boolean',''),
(18,    'tracking',  'CONF_tracking_type',        'string', ''),
(19,    'tracking',  'CONF_tracking_url',         'string', ''),
(20,    'tracking',  'CONF_tracking_code',        'string', '');
(21,    'tracking',  'CONF_tracking_api_key',     'string', ''),
(22,    'tracking',  'CONF_tracking_api_auth',    'string', ''),
(23,    'aws',       'CONF_aws_key',              'string', ''),
(24,    'aws',       'CONF_aws_secret',           'string', ''),
(25,    'aws',       'CONF_aws_region',           'string', ''),
(26,    'aws',       'CONF_aws_account_id',       'string', ''),
(27,    'cloudflare','CONF_cloudflare_account_id','string', '');

CREATE TABLE `config_value` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_definition_id` int NOT NULL,
  `profile` varchar(16) NOT NULL DEFAULT '',
  `effective_start_date` datetime DEFAULT NULL,
  `effective_end_date` datetime DEFAULT NULL,
  `key` varchar(16) NOT NULL DEFAULT '' COMMENT 'Only required for ENGINE array',
  `value` text,
  PRIMARY KEY (`id`),
  KEY `IDX_config_value_config_definition_id` (`config_definition_id`),
  CONSTRAINT `CONST_config_value_config_definition` FOREIGN KEY (`config_definition_id`) REFERENCES `config_definition` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config_value` (`id`, `config_definition_id`, `profile`, `effective_start_date`, `effective_end_date`, `key`, `value`) VALUES
(1,      1,  'Live', NULL,   NULL,   '', ''),
(2,      2,  'Live', NULL,   NULL,   '', ''),
(3,      3,  'Live', NULL,   NULL,   '', ''),
(4,      4,  'Live', NULL,   NULL,   '', ''),
(5,      5,  'Live', NULL,   NULL,   '', 'administrator@aoware.co.uk'),
(6,      6,  'Live', NULL,   NULL,   '', 'Slim Saas Boilerplate'),
(7,      7,  'Live', NULL,   NULL,   '', ''),
(8,      8,  'Live', NULL,   NULL,   '', ''),
(9,      9,  'Live', NULL,   NULL,   '', ''),
(10,    10,  'Live', NULL,   NULL,   '0',    ''),
(11,    11,  'Live', NULL,   NULL,   '', 'guest'),
(12,    12,  'Live', NULL,   NULL,   '', 'guest'),
(13,    13,  'Live', NULL,   NULL,   '', ''),
(14,    14,  'Live', NULL,   NULL,   '', 'true'),
(15,    14,  'Test', NULL,   NULL,   '', 'false'),
(16,    15,  'Live', NULL,   NULL,   '', 'ThisIsAnEncryptionKey'),
(17,    16,  'Live', NULL,   NULL,   '', 'aes-256-cbc-hmac-sha256'),
(18,    17,  'Live', NULL,   NULL,   '', 'true'),
(19,    17,  'Test', NULL,   NULL,   '', 'false'),
(20,    18,  'Live', NULL,   NULL,   '', 'owa'),
(21,    19,  'Live', NULL,   NULL,   '', ''),
(22,    20,  'Live', NULL,   NULL,   '', ''),
(23,    21,  'Live', NULL,   NULL,   '', ''),
(24,    22,  'Live', NULL,   NULL,   '', ''),
(25,    23,  'Live', NULL,   NULL,   '', ''),
(26,    24,  'Live', NULL,   NULL,   '', ''),
(27,    25,  'Live', NULL,   NULL,   '', 'eu-west-2'),
(28,    26,  'Live', NULL,   NULL,   '', ''),
(29,    27,  'Live', NULL,   NULL,   '', '');

CREATE TABLE `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_online_id` char(32) NOT NULL,
  `recipient_email` varchar(255) NOT NULL DEFAULT '',
  `recipient_name` varchar(64) NOT NULL,
  `creation_date` datetime NOT NULL,
  `trigger_date` datetime NOT NULL COMMENT 'Different than creation_date when required to send in the future',
  `sent_date` datetime DEFAULT NULL COMMENT 'date when the communication is sent to the SMTP server or the SMS gateway',
  `status` enum('awaiting','processing','sent','error') NOT NULL COMMENT 'Communication status. Possible value are ''awaiting'', ''processing'', ''sent'' and ''error''',
  `status_description` varchar(256) DEFAULT NULL COMMENT 'status description. Should only be populated when status = ''error''',
  `subject` varchar(256) NOT NULL COMMENT 'email subject',
  `content` text NOT NULL COMMENT 'rendered version ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_emails_view_online_id` (`view_online_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Record all emails sent from the system';

CREATE TABLE `email_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email_id` int NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '0',
  `mime_type` varchar(128) NOT NULL DEFAULT '',
  `filename` varchar(128) NOT NULL DEFAULT '',
  `encoding` varchar(128) NOT NULL DEFAULT '',
  `content` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_email_attachments_email_id_sequence` (`email_id`,`sequence`),
  KEY `IDX_email_attachments_email_id` (`email_id`),
  CONSTRAINT `email_attachments_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='save attachments for a given email';

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` enum('','github','facebook','google','linkedin','email') NOT NULL,
  `oauth_uid` varchar(100) NOT NULL,
  `password` char(32) NOT NULL,
  `first_name_crypted` varchar(250) NOT NULL,
  `last_name_crypted` varchar(250) NOT NULL,
  `email` varchar(255) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `oauth_provider`, `oauth_uid`, `password`, `first_name`, `last_name`, `username`, `email`, `location`, `picture`, `link`, `type`, `created`, `modified`, `last_login`, `registration_ip`, `verification_token`, `verification_date`, `verification_ip`, `login_token`) VALUES
(1,	'email',	'administrator@aoware.co.uk',	'3cc31cd246149aec68079241e71e98f6',	'Administrator',	'',	'',	'administrator@aoware.co.uk',	'',	'',	'',	'agent',	'0000-00-00 00:00:00',	NULL,	'2022-11-08 21:59:47',	NULL,	NULL,	NULL,	NULL,	NULL);

CREATE TABLE `account_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_account_users_account_id_user_id` (`account_id`,`user_id`),
  KEY `IDX_account_users_account_id` (`account_id`),
  KEY `IDX_account_users_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;