USE `word_breaker`;

DROP TABLE IF EXISTS `json`;

CREATE TABLE `json` (
    `json_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `language_id_source` INT(2) DEFAULT 1,
    `language_id_target` INT(2) DEFAULT 2,
    `user_id` INT(11) NOT NULL,
    `json_name` VARCHAR(128),
    `json_string` TEXT DEFAULT ''
);
