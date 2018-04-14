USE `word_breaker`;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    `user_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_email` VARCHAR(128) NOT NULL,
    `user_password` VARCHAR(128) NOT NULL
);
