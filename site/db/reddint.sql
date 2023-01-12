-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Dic 25, 2022 alle 18:49
-- Versione del server: 10.5.18-MariaDB-0+deb11u1
-- Versione PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reddint`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `content` varchar(8192) NOT NULL,
  `creation_date` datetime NOT NULL,
  `edited` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `comment`
--

INSERT INTO `comment` (`id`, `post`, `author`, `content`, `creation_date`, `edited`) VALUES
(1, 2, 2, 'comment 1', '2022-12-25 00:15:12', 0),
(2, 1, 2, 'xdddddddd', '2022-12-25 00:07:12', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `community`
--

CREATE TABLE `community` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `community`
--

INSERT INTO `community` (`id`, `author`, `name`, `description`, `creation_date`) VALUES
(1, 1, 'comm1', 'description comm1', '2022-12-21 22:04:59'),
(2, 2, 'comm2', 'description comm2', '2022-12-24 22:04:59');

-- --------------------------------------------------------

--
-- Struttura della tabella `following`
--

CREATE TABLE `following` (
  `follower` int(11) NOT NULL,
  `followed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `following`
--

INSERT INTO `following` (`follower`, `followed`) VALUES
(1, 2),
(2, 1),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `content` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `notification`
--

INSERT INTO `notification` (`id`, `user`, `seen`, `date`, `content`) VALUES
(1, 1, 1, '2022-12-21 18:10:40', 'def followed you'),
(2, 1, 0, '2022-12-24 18:10:40', 'ghi followed you');

-- --------------------------------------------------------

--
-- Struttura della tabella `participation`
--

CREATE TABLE `participation` (
  `user` int(11) NOT NULL,
  `community` int(11) NOT NULL,
  `date_joined` datetime NOT NULL,
  `date_left` datetime DEFAULT NULL,
  `reason_left` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `participation`
--

INSERT INTO `participation` (`user`, `community`, `date_joined`, `date_left`, `reason_left`) VALUES
(1, 1, '2022-12-21 22:06:13', NULL, NULL),
(2, 1, '2022-12-24 22:17:00', NULL, NULL),
(2, 2, '2022-12-24 22:06:13', NULL, NULL),
(3, 1, '2022-12-21 22:07:27', '2022-12-23 22:07:27', 'ban'),
(3, 2, '2022-12-24 22:07:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `community` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` varchar(8192) NOT NULL,
  `attachment` int(11) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `edited` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`id`, `author`, `community`, `title`, `content`, `attachment`, `creation_date`, `edited`) VALUES
(1, 1, 1, 'xd', 'first post', NULL, '2022-12-23 00:13:57', 0),
(2, 3, 2, 'lmao', 'post in comm2', NULL, '2022-12-24 00:14:15', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(512) NOT NULL,
  `username` varchar(64) NOT NULL,
  `bio` varchar(2048) DEFAULT NULL,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `username`, `bio`, `creation_date`) VALUES
(1, 'abc@gmail.com', 'pass', 'abc', 'abc bio', '2022-12-22 17:59:15'),
(2, 'def@gmail.com', 'pass', 'def', 'def bio', '2022-12-23 17:59:15'),
(3, 'ghi@gmail.com', 'pass', 'ghi', NULL, '2022-12-24 18:00:18');

-- --------------------------------------------------------

--
-- Struttura della tabella `vote_comment`
--

CREATE TABLE `vote_comment` (
  `user` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `vote` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `vote_comment`
--

INSERT INTO `vote_comment` (`user`, `comment`, `vote`) VALUES
(1, 1, -1),
(3, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `vote_post`
--

CREATE TABLE `vote_post` (
  `user` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `vote` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `vote_post`
--

INSERT INTO `vote_post` (`user`, `post`, `vote`) VALUES
(2, 1, 1),
(2, 2, -1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post` (`post`),
  ADD KEY `author` (`author`);

--
-- Indici per le tabelle `community`
--
ALTER TABLE `community`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `author` (`author`);

--
-- Indici per le tabelle `following`
--
ALTER TABLE `following`
  ADD PRIMARY KEY (`follower`,`followed`),
  ADD KEY `follower` (`follower`),
  ADD KEY `followed` (`followed`);

--
-- Indici per le tabelle `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indici per le tabelle `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`user`,`community`,`date_joined`),
  ADD KEY `user` (`user`),
  ADD KEY `community` (`community`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community` (`community`),
  ADD KEY `author` (`author`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD PRIMARY KEY (`user`,`comment`),
  ADD KEY `comment` (`comment`);

--
-- Indici per le tabelle `vote_post`
--
ALTER TABLE `vote_post`
  ADD PRIMARY KEY (`user`,`post`),
  ADD KEY `post` (`post`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `community`
--
ALTER TABLE `community`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`post`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `community`
--
ALTER TABLE `community`
  ADD CONSTRAINT `community_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `following`
--
ALTER TABLE `following`
  ADD CONSTRAINT `following_ibfk_1` FOREIGN KEY (`follower`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `following_ibfk_2` FOREIGN KEY (`followed`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `participation_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `participation_ibfk_2` FOREIGN KEY (`community`) REFERENCES `community` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`community`) REFERENCES `community` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD CONSTRAINT `vote_comment_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vote_comment_ibfk_2` FOREIGN KEY (`comment`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `vote_post`
--
ALTER TABLE `vote_post`
  ADD CONSTRAINT `vote_post_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vote_post_ibfk_2` FOREIGN KEY (`post`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
