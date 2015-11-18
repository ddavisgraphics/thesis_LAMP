CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';

DROP DATABASE IF EXISTS `timeTracker`;
CREATE DATABASE IF NOT EXISTS `timeTracker`;
GRANT ALL PRIVILEGES ON `timeTracker`.* TO 'username'@'localhost';
USE `timeTracker`;