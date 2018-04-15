USE `word_breaker`;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    `user_id` INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_email` VARCHAR(128) NOT NULL,
    `user_password` VARCHAR(128) NOT NULL
);

INSERT INTO `user`
    (`user_email`, `user_password`)
VALUES
    ('dhumdil@gmail.com', '06948d93cd1e0855ea37e75ad516a250d2d0772890b073808d831c438509190162c0d890b17001361820cffc30d50f010c387e9df943065aa8f4e92e63ff060c');
