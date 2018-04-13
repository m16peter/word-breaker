USE `word-breaker`;

DROP TABLE IF EXISTS `data`;

CREATE TABLE `data` (
    `source-language-id` INT(11) NOT NULL,
    `target-language-id` INT(11) NOT NULL,
    `source` TEXT NOT NULL,
    `target` TEXT NOT NULL
);
