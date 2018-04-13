USE `word-breaker`;

DROP TABLE IF EXISTS `json`;

CREATE TABLE `json` (
    `id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `source-language-id` INT(11) NOT NULL,
    `target-language-id` INT(11) NOT NULL,
    `user-id` INT(11) NOT NULL,
    `json-name` VARCHAR(128) NOT NULL,
    `json-string` TEXT NOT NULL
);
