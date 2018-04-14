USE `word_breaker`;

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
    `language_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `language_name` VARCHAR(16) NOT NULL
);
