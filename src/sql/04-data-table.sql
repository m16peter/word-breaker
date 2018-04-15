USE `word_breaker`;

DROP TABLE IF EXISTS `data`;

CREATE TABLE `data` (
    `language_id_source` INT(11) NOT NULL,
    `language_id_target` INT(11) NOT NULL,
    `data_source` TEXT,
    `data_target` TEXT
);
