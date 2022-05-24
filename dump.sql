CREATE DATABASE `telegram`;

USE `telegram`;

CREATE TABLE `rates` (
                         `id` int NOT NULL,
                         `val` int DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `telegram`.`rates` VALUES(1, 10000);

CREATE TABLE `users` (
                         `id` int unsigned NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
