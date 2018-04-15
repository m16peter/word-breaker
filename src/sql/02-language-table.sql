USE `word_breaker`;

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
    `language_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `language_key` VARCHAR(4) NOT NULL,
    `language_name` VARCHAR(128) NOT NULL
);

INSERT INTO `language`
    (`language_key`, `language_name`)
VALUES
    ('sk', 'Slovak'), ('ro', 'Romanian'), ('en', 'English'), ('cs', 'Czech'), ('hu', 'Hungarian');
