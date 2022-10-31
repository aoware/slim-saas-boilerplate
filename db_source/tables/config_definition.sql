CREATE TABLE `config_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` enum('number','string','boolean','html','integer','array') NOT NULL DEFAULT 'string' COMMENT 'n = number, s = string, b = boolean, h=html, i=integer, b=boolean, a=array',
  `comment` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3