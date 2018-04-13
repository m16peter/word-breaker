USE `word-breaker`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `password` VARCHAR(128) NOT NULL
);
