USE `word_breaker`;

DROP TABLE IF EXISTS `json`;

CREATE TABLE `json` (
    `json_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `language_id_source` INT(11) NOT NULL,
    `language_id_target` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `json_name` VARCHAR(128) NOT NULL,
    `json_string` TEXT NOT NULL
);
