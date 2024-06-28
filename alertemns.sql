-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 28 juin 2024 à 13:40
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `alertemns`
--

-- --------------------------------------------------------

--
-- Structure de la table `table_channel`
--

DROP TABLE IF EXISTS `table_channel`;
CREATE TABLE IF NOT EXISTS `table_channel` (
  `channel_id` int NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(50) NOT NULL,
  `channel_description` varchar(50) DEFAULT NULL,
  `channel_icon` varchar(50) DEFAULT NULL,
  `channel_creation_date` datetime NOT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_channel`
--

INSERT INTO `table_channel` (`channel_id`, `channel_name`, `channel_description`, `channel_icon`, `channel_creation_date`) VALUES
(1, 'Actualités', 'Canal pour tenir aux courants les utilisateurs des', 'newspaper.png', '2024-03-27 14:10:54'),
(2, 'Forum', 'Canal pour poser toutes vos questions.', 'chat_bubbles.png', '2024-03-27 14:13:41'),
(11, 'Général', 'Canal pour parler de tout avec tout le monde', 'global.png', '2024-03-27 17:28:56'),
(12, 'bsd-2', 'Canal des bachelor smart developer 2', 'suitcase.png', '2024-04-05 07:37:31'),
(14, '1-15', NULL, NULL, '2024-04-09 10:53:44'),
(15, 'bsd-1', 'canal des bachelor smart developer 1', 'suitcase.png', '2024-04-09 12:20:21'),
(30, '18-1', NULL, NULL, '2024-04-11 22:00:03'),
(31, '14-1', NULL, NULL, '2024-04-11 22:00:11'),
(32, '25-1', NULL, NULL, '2024-06-28 15:21:32');

-- --------------------------------------------------------

--
-- Structure de la table `table_event`
--

DROP TABLE IF EXISTS `table_event`;
CREATE TABLE IF NOT EXISTS `table_event` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `event_title` varchar(50) NOT NULL,
  `event_description` varchar(50) DEFAULT NULL,
  `event_begin_at` datetime NOT NULL,
  `event_end_at` datetime NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_event`
--

INSERT INTO `table_event` (`event_id`, `event_title`, `event_description`, `event_begin_at`, `event_end_at`) VALUES
(1, 'Devoirs', '', '2024-06-25 20:00:00', '2024-06-25 22:00:00'),
(3, 'Cinéma', NULL, '2024-06-12 13:00:00', '2024-06-12 16:00:00'),
(4, 'Cours C#', '', '2024-06-06 12:00:00', '2024-06-06 13:00:00'),
(5, 'Bowliing', NULL, '2024-01-19 17:00:00', '2024-01-19 20:00:00'),
(6, 'Férié', 'C\'est férié', '2024-05-01 08:00:00', '2024-05-01 09:00:00'),
(10, 'Rendez vous avec Juline', '', '2024-06-19 16:00:00', '2024-06-19 21:00:00'),
(11, 'Manger', '', '2024-06-03 19:00:00', '2024-06-03 22:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `table_message`
--

DROP TABLE IF EXISTS `table_message`;
CREATE TABLE IF NOT EXISTS `table_message` (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `message_content` varchar(2000) NOT NULL,
  `message_date` datetime NOT NULL,
  `message_channel_id` int NOT NULL,
  `message_user_id` int NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `message_channel_id` (`message_channel_id`),
  KEY `message_user_id` (`message_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_message`
--

INSERT INTO `table_message` (`message_id`, `message_content`, `message_date`, `message_channel_id`, `message_user_id`) VALUES
(1, 'Bonjour tout le monde ! Comment ça va aujourd\'hui ?', '2024-03-27 17:44:19', 1, 1),
(2, 'Salut Harry ! Ça va bien, merci. Et toi ?', '2024-03-27 17:44:45', 1, 18),
(6, 'Bonjour ! Moi aussi ça va, j\'ai eu une journée assez tranquille. Et toi, Harry ?', '2024-04-13 12:29:55', 1, 14),
(7, 'On part en vacances', '2024-06-26 19:24:35', 2, 15),
(8, 'Hein ???', '2024-06-26 19:24:59', 2, 25),
(11, 'Salut à tous! Je vais bien, merci. J\'ai eu une réunion un peu longue ce matin, mais ça s\'est bien passé. Quoi de neuf de votre côté ?', '2024-06-28 13:27:14', 1, 25),
(13, 'J\'ai enfin terminé ce projet dont je vous parlais la semaine dernière. Un vrai soulagement !', '2024-06-28 15:28:26', 1, 1),
(14, 'Félicitations, Alice! Tu dois être contente. Moi, je suis en train de planifier mes vacances. J\'hésite encore entre la montagne et la plage.', '2024-06-28 15:28:52', 1, 15),
(15, 'Les deux options sont tentantes ! Personnellement, j\'irais pour la montagne, ça change un peu.', '2024-06-28 15:29:34', 1, 14),
(16, 'Salut ', '2024-06-28 15:33:53', 14, 1),
(17, 'Coucou', '2024-06-28 15:34:00', 31, 1),
(18, 'Bonsoir', '2024-06-28 15:34:04', 32, 1),
(19, 'Hey', '2024-06-28 15:34:08', 30, 1),
(20, 'T\'as déjà mangé un ours ?', '2024-06-28 15:37:55', 14, 15),
(21, 'Non jamais', '2024-06-28 15:39:14', 14, 1);

-- --------------------------------------------------------

--
-- Structure de la table `table_message_reaction`
--

DROP TABLE IF EXISTS `table_message_reaction`;
CREATE TABLE IF NOT EXISTS `table_message_reaction` (
  `message_reaction_id` int NOT NULL AUTO_INCREMENT,
  `message_reaction_message_id` int DEFAULT NULL,
  `message_reaction_reaction_id` int DEFAULT NULL,
  `message_reaction_rection_quantity` int NOT NULL,
  PRIMARY KEY (`message_reaction_id`),
  KEY `message_reaction_message_id` (`message_reaction_message_id`),
  KEY `message_reaction_reaction_id` (`message_reaction_reaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_notification`
--

DROP TABLE IF EXISTS `table_notification`;
CREATE TABLE IF NOT EXISTS `table_notification` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `notification_user_id` int DEFAULT NULL,
  `notification_message_id` int DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `notification_user_id` (`notification_user_id`),
  KEY `notification_message_id` (`notification_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_poll_option`
--

DROP TABLE IF EXISTS `table_poll_option`;
CREATE TABLE IF NOT EXISTS `table_poll_option` (
  `option_id` int NOT NULL AUTO_INCREMENT,
  `option_value` varchar(50) DEFAULT NULL,
  `option_message_id` int NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `option_message_id` (`option_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_reaction`
--

DROP TABLE IF EXISTS `table_reaction`;
CREATE TABLE IF NOT EXISTS `table_reaction` (
  `reaction_id` int NOT NULL AUTO_INCREMENT,
  `reaction_unicode` varchar(50) NOT NULL,
  PRIMARY KEY (`reaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_role`
--

DROP TABLE IF EXISTS `table_role`;
CREATE TABLE IF NOT EXISTS `table_role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `role_badge` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_role`
--

INSERT INTO `table_role` (`role_id`, `role_name`, `role_badge`) VALUES
(1, 'administrateur', 'administrateur.png'),
(3, 'staff', 'staff.png'),
(10, 'stagiaire', 'stagiaire.png');

-- --------------------------------------------------------

--
-- Structure de la table `table_user`
--

DROP TABLE IF EXISTS `table_user`;
CREATE TABLE IF NOT EXISTS `table_user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_lastname` varchar(50) NOT NULL,
  `user_firstname` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_picture` varchar(255) NOT NULL,
  `user_creation_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_user`
--

INSERT INTO `table_user` (`user_id`, `user_lastname`, `user_firstname`, `user_email`, `user_password`, `user_picture`, `user_creation_date`) VALUES
(1, 'POTTER', 'Harry', 'harry.potter@gmail.com', '$2y$10$JT.JiNZGGmcwHS.dYTTL.eskj8a3.vpM2B8Z.KrOhbykgc7Eng6Cu', 'harry-potter.jpg', '2024-03-25 13:53:09'),
(14, 'SKYWALKER', 'Anakin', 'anakin.skywalker@gmail.com', '$2y$10$J/Qau23OGeimT9pvapHnJOVCNM9LrtZXR/IJgmLNQ8.ZX6DKX37D2', 'anakin-skywalker.jpg', '2024-03-26 11:56:49'),
(15, 'MOUSE', 'Mickey', 'mickey.mouse@gmail.com', '$2y$10$JT.JiNZGGmcwHS.dYTTL.eskj8a3.vpM2B8Z.KrOhbykgc7Eng6Cu', 'mickey-mouse(2).png', '2024-03-26 11:57:17'),
(17, 'POTTER', 'Harry', 'harry.p@gmail.com', '$2y$10$JT.JiNZGGmcwHS.dYTTL.eskj8a3.vpM2B8Z.KrOhbykgc7Eng6Cu', 'harry-potter(6).png', '2024-03-26 13:53:58'),
(18, 'VADOR', 'Dark', 'dark.vador@gmail.com', '$2y$10$JT.JiNZGGmcwHS.dYTTL.eskj8a3.vpM2B8Z.KrOhbykgc7Eng6Cu', 'dark-vador(2).jpg', '2024-03-26 15:44:01'),
(20, 'POTTER', 'Harrye', 'test@gmail.com', '$2y$10$aBp0pRVT7SrZ3ADfO7QP1OMIfVOptzkRcKXj5gHsmTFz8/WObhi6e', '', '2024-03-26 16:22:58'),
(24, 'POTTER', 'Mickey', 'harry.potter@gmail.come', '$2y$10$63TEvetS3K31G8qbi/ZIQeomGoRpLsDUXy2JAFzQDEV9mLLwSUiM.', '', '2024-04-07 20:46:10'),
(25, 'POPPINS', 'Mary', 'mary.poppins@gmail.com', '$2y$10$Q0N8.YL9zseD54scnrFbUuqCRPW4Fd/m8D5r20h99I8hX328i4cu2', '', '2024-04-07 20:48:54');

-- --------------------------------------------------------

--
-- Structure de la table `table_user_channel`
--

DROP TABLE IF EXISTS `table_user_channel`;
CREATE TABLE IF NOT EXISTS `table_user_channel` (
  `user_channel_id` int NOT NULL AUTO_INCREMENT,
  `user_channel_user_id` int DEFAULT NULL,
  `user_channel_channel_id` int DEFAULT NULL,
  PRIMARY KEY (`user_channel_id`),
  KEY `user_channel_user_id` (`user_channel_user_id`),
  KEY `user_channel_channel_id` (`user_channel_channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_user_channel`
--

INSERT INTO `table_user_channel` (`user_channel_id`, `user_channel_user_id`, `user_channel_channel_id`) VALUES
(1, 18, 2),
(2, 18, 1),
(3, 1, 2),
(4, 1, 1),
(6, 17, 11),
(7, 20, 11),
(9, 14, 1),
(12, 24, 2),
(13, 25, 2),
(14, 25, 11),
(15, 17, 12),
(16, 1, 14),
(18, 17, 15),
(19, 15, 1),
(20, 15, 11),
(21, 15, 14),
(46, 1, 30),
(47, 18, 30),
(48, 1, 31),
(49, 14, 31),
(50, 1, 32),
(51, 25, 32),
(52, 25, 1);

-- --------------------------------------------------------

--
-- Structure de la table `table_user_event`
--

DROP TABLE IF EXISTS `table_user_event`;
CREATE TABLE IF NOT EXISTS `table_user_event` (
  `user_event_id` int NOT NULL AUTO_INCREMENT,
  `user_event_user_id` int DEFAULT NULL,
  `user_event_event_id` int DEFAULT NULL,
  PRIMARY KEY (`user_event_id`),
  KEY `user_event_user_id` (`user_event_user_id`),
  KEY `user_event_event_id` (`user_event_event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_user_event`
--

INSERT INTO `table_user_event` (`user_event_id`, `user_event_user_id`, `user_event_event_id`) VALUES
(1, 1, 1),
(3, 15, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(12, 1, 10),
(13, 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `table_user_message`
--

DROP TABLE IF EXISTS `table_user_message`;
CREATE TABLE IF NOT EXISTS `table_user_message` (
  `user_message_id` int NOT NULL AUTO_INCREMENT,
  `user_message_user_id` int DEFAULT NULL,
  `user_message_message_id` int DEFAULT NULL,
  PRIMARY KEY (`user_message_id`),
  KEY `user_message_user_id` (`user_message_user_id`),
  KEY `user_message_message_id` (`user_message_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_user_poll`
--

DROP TABLE IF EXISTS `table_user_poll`;
CREATE TABLE IF NOT EXISTS `table_user_poll` (
  `user_poll_id` int NOT NULL AUTO_INCREMENT,
  `user_poll_user_id` int DEFAULT NULL,
  `user_poll_option_id` int DEFAULT NULL,
  `user_poll_date` datetime NOT NULL,
  PRIMARY KEY (`user_poll_id`),
  KEY `user_poll_user_id` (`user_poll_user_id`),
  KEY `user_poll_option_id` (`user_poll_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_user_role`
--

DROP TABLE IF EXISTS `table_user_role`;
CREATE TABLE IF NOT EXISTS `table_user_role` (
  `user_role_id` int NOT NULL AUTO_INCREMENT,
  `user_role_user_id` int DEFAULT NULL,
  `user_role_role_id` int DEFAULT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `user_role_user_id` (`user_role_user_id`),
  KEY `user_role_role_id` (`user_role_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `table_user_role`
--

INSERT INTO `table_user_role` (`user_role_id`, `user_role_user_id`, `user_role_role_id`) VALUES
(7, 17, 10),
(9, 17, 3),
(22, 20, 10),
(27, 1, 3),
(28, 18, 1),
(29, 18, 10),
(30, 1, 1),
(31, 14, 3),
(33, 24, 10),
(34, 25, 3),
(35, 15, 10);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `table_message`
--
ALTER TABLE `table_message`
  ADD CONSTRAINT `table_message_ibfk_1` FOREIGN KEY (`message_channel_id`) REFERENCES `table_channel` (`channel_id`),
  ADD CONSTRAINT `table_message_ibfk_2` FOREIGN KEY (`message_user_id`) REFERENCES `table_user` (`user_id`);

--
-- Contraintes pour la table `table_message_reaction`
--
ALTER TABLE `table_message_reaction`
  ADD CONSTRAINT `table_message_reaction_ibfk_1` FOREIGN KEY (`message_reaction_message_id`) REFERENCES `table_message` (`message_id`),
  ADD CONSTRAINT `table_message_reaction_ibfk_2` FOREIGN KEY (`message_reaction_reaction_id`) REFERENCES `table_reaction` (`reaction_id`);

--
-- Contraintes pour la table `table_notification`
--
ALTER TABLE `table_notification`
  ADD CONSTRAINT `table_notification_ibfk_1` FOREIGN KEY (`notification_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_notification_ibfk_2` FOREIGN KEY (`notification_message_id`) REFERENCES `table_message` (`message_id`);

--
-- Contraintes pour la table `table_poll_option`
--
ALTER TABLE `table_poll_option`
  ADD CONSTRAINT `table_poll_option_ibfk_1` FOREIGN KEY (`option_message_id`) REFERENCES `table_message` (`message_id`);

--
-- Contraintes pour la table `table_user_channel`
--
ALTER TABLE `table_user_channel`
  ADD CONSTRAINT `table_user_channel_ibfk_1` FOREIGN KEY (`user_channel_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_user_channel_ibfk_2` FOREIGN KEY (`user_channel_channel_id`) REFERENCES `table_channel` (`channel_id`);

--
-- Contraintes pour la table `table_user_event`
--
ALTER TABLE `table_user_event`
  ADD CONSTRAINT `table_user_event_ibfk_1` FOREIGN KEY (`user_event_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_user_event_ibfk_2` FOREIGN KEY (`user_event_event_id`) REFERENCES `table_event` (`event_id`);

--
-- Contraintes pour la table `table_user_message`
--
ALTER TABLE `table_user_message`
  ADD CONSTRAINT `table_user_message_ibfk_1` FOREIGN KEY (`user_message_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_user_message_ibfk_2` FOREIGN KEY (`user_message_message_id`) REFERENCES `table_message` (`message_id`);

--
-- Contraintes pour la table `table_user_poll`
--
ALTER TABLE `table_user_poll`
  ADD CONSTRAINT `table_user_poll_ibfk_1` FOREIGN KEY (`user_poll_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_user_poll_ibfk_2` FOREIGN KEY (`user_poll_option_id`) REFERENCES `table_poll_option` (`option_id`);

--
-- Contraintes pour la table `table_user_role`
--
ALTER TABLE `table_user_role`
  ADD CONSTRAINT `table_user_role_ibfk_1` FOREIGN KEY (`user_role_user_id`) REFERENCES `table_user` (`user_id`),
  ADD CONSTRAINT `table_user_role_ibfk_2` FOREIGN KEY (`user_role_role_id`) REFERENCES `table_role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
