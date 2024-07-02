-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jul 02, 2024 at 06:00 AM
-- Server version: 11.4.2-MariaDB-ubu2404
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tomtroc`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `author` varchar(32) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `slug`, `author`, `cover`, `description`, `available`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Harry Potter et la coupe de feu', 'harry-potter-et-la-coupe-de-feu', 'JK Rowling', 'https://placehold.co/400', '\"Harry Potter et la Coupe de feu\" suit Harry lors de sa quatrième année à Poudlard, où il est mystérieusement inscrit au prestigieux et périlleux Tournoi des Trois Sorciers.', 1, 1, '2024-06-14 11:11:33', '2024-06-14 11:11:33'),
(2, 'Harry Potter et le prisonnier d\'Azkaban', 'harry-potter-et-le-prisonnier-d-azkaban', 'JK Rowling', 'https://placehold.co/400', '\"Harry Potter et le Prisonnier d\'Azkaban\" suit Harry lors de sa quatrième année à Poudlard, où il est mystérieusement inscrit au prestigieux et périlleux Tournoi des Trois Sorciers.', 1, 1, '2024-06-14 11:11:33', '2024-06-14 11:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `uuid`, `archived`, `owner_id`, `target_id`, `created_at`, `updated_at`) VALUES
(1, '22d87de7-542b-44d0-a91f-f217ba11bc07', 0, 1, 2, '2024-06-23 14:05:56', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `conversation_user`
--

CREATE TABLE `conversation_user` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `conversation_user`
--

INSERT INTO `conversation_user` (`id`, `conversation_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-06-23 14:07:07', '0000-00-00 00:00:00'),
(2, 1, 2, '2024-06-23 14:07:09', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `parent_id`, `content`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Bonjour Jane ! C\'est John, comment vas-tu ?', 1, '2024-06-24 04:16:42', '2024-06-14 08:02:34'),
(2, 1, NULL, 'Bonjour John ! Je vais bien et toi ?', 2, '2024-06-24 04:18:24', '2024-06-14 08:02:52'),
(5, 1, NULL, 'Tu as eu le temps de lire Harry Potter et la Coupe de Feu ?', 1, '2024-06-24 04:19:08', '0000-00-00 00:00:00'),
(6, 1, NULL, 'Je ne l\'ai pas terminé encore mais j\'en suis ravie !', 2, '2024-06-24 04:19:24', '2024-06-23 13:41:46'),
(7, 1, NULL, 'Super alors ! Bonne continuation :)', 1, '2024-06-24 04:19:39', '2024-06-23 14:08:08'),
(8, 1, NULL, 'Merci !', 2, '2024-06-24 04:19:59', '2024-06-23 14:16:29'),
(9, 1, NULL, 'Hello, comment vas-tu aujourd\'hui ?', 1, '2024-06-27 05:48:42', '2024-06-27 05:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `content` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Titre 1', 'Contenu 1', 1, '2024-06-29 06:02:45', '2024-06-29 06:02:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `display_name`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'Johndoe', 'john@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$RktyaHkydFZwWTZyeXQ2NQ$9UoqfSgJTWjCz9QGHpzNwPe0Xrvazp2qeDQCZrljRZk', 'https://placehold.co/400', '2024-06-23 14:05:44', '2024-06-14 08:04:33'),
(2, 'janedoe', 'Janedoe', 'jane@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$VktBVXlLQ1loNlhYVVZSSw$nNQ1cIkfRHCYcte9qUyHh79bMA0Q6O64DUiE+mgYHCE', 'https://placehold.co/400', '2024-06-23 14:05:47', '2024-06-14 07:59:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conversation_user`
--
ALTER TABLE `conversation_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
