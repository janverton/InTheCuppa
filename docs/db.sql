-- Create ITC User database
CREATE DATABASE IF NOT EXISTS `ITC_Users` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';

USE `ITC_Users`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `userId`           INT            UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname`         VARCHAR(150)   CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '',
  `emailAddress`     VARCHAR(255)   CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '',
  PRIMARY KEY (`userId`)
) 
  ENGINE                = InnoDB
  DEFAULT CHARACTER SET = utf8mb4
  COLLATE               = utf8mb4_general_ci;