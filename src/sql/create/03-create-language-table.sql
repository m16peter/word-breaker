USE `word-breaker`;

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
    `id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `language` VARCHAR(16) NOT NULL
);
