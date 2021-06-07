CREATE DATABASE IF NOT EXISTS `mydb`;

USE `mydb`;

CREATE TABLE `locations` (
    `id` VARCHAR(64) NOT NULL,
    `weather` JSON NULL,
    `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
