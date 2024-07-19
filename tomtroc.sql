-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jul 19, 2024 at 08:55 AM
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
(2, 'Harry Potter et le prisonnier d\'Azkaban', 'harry-potter-et-le-prisonnier-d-azkaban', 'JK Rowling', 'https://placehold.co/400', '\"Harry Potter et le Prisonnier d\'Azkaban\" suit Harry lors de sa quatrième année à Poudlard, où il est mystérieusement inscrit au prestigieux et périlleux Tournoi des Trois Sorciers.', 1, 6, '2024-06-14 11:11:33', '2024-06-14 11:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `uuid`, `archived`, `created_at`, `updated_at`) VALUES
(1, '22d87de7-542b-44d0-a91f-f217ba11bc07', 0, '2024-07-01 08:00:00', '2024-07-01 08:30:00');

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
  `parent_id` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `parent_id`, `content`, `sender_id`, `receiver_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Bonjour Jane ! C\'est John, comment vas-tu ?', 1, 2, '2024-07-01 08:00:00', '2024-07-01 08:00:00'),
(2, NULL, 'Bonjour John ! Je vais bien et toi ?', 2, 1, '2024-07-01 08:05:00', '2024-07-01 08:05:00'),
(5, NULL, 'Tu as eu le temps de lire Harry Potter et la Coupe de Feu ?', 1, 2, '2024-07-01 08:10:00', '2024-07-01 08:10:00'),
(6, NULL, 'Je ne l\'ai pas terminé encore mais j\'en suis ravie !', 2, 1, '2024-07-01 08:15:00', '2024-07-01 08:15:00'),
(7, NULL, 'Super alors ! Bonne continuation :)', 1, 2, '2024-07-01 08:20:00', '2024-07-01 08:20:00'),
(8, NULL, 'Merci !', 2, 1, '2024-07-01 08:25:00', '2024-07-01 08:25:00'),
(9, NULL, 'Hello, comment vas-tu aujourd\'hui ?', 1, 2, '2024-07-01 08:30:00', '2024-07-01 08:30:00'),
(19, NULL, 'Au moins ça fonctionne maintenant', 1, 2, '2024-07-05 14:01:02', '2024-07-05 14:01:02');

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
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `display_name`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'kiwodd', 'Johndoe', 'contact@kiwodd.art', '$argon2id$v=19$m=65536,t=4,p=1$MzF1Z1ZRMUltUmszZGI3bg$dH7qH3IdKcV0P3B0MJF4Rbr+VnNhNmKkgMOyWZdhUC4', './storage/avatars/483d142fa2a4e30c.png', '2024-06-23 14:05:44', '2024-06-14 08:04:33'),
(2, 'janedoe', 'Janedoe', 'jane@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$VktBVXlLQ1loNlhYVVZSSw$nNQ1cIkfRHCYcte9qUyHh79bMA0Q6O64DUiE+mgYHCE', './storage/avatars/968bda07f8b51769.jpg', '2024-06-23 14:05:47', '2024-06-14 07:59:29'),
(6, 'johanna', 'Johanna', 'johanna@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$S21VaWxaVEZRTjY5V3pjOA$wJhyxOz4x/oZMESTpPoc94mm81nNV7DS7asgdMx7ZD8', NULL, '2024-07-06 07:39:43', '2024-07-06 07:39:43'),
(7, 'jackie', 'Jackie', 'jackie@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$U0tjU05JY3RqN29NSHhWLg$KTA1lFF8O+NnKwm6Gwi3ECdVHipw2GM4XX35IV3MkHs', NULL, '2024-07-16 04:38:29', '2024-07-16 04:38:29');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
