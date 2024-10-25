-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: antoinelrk_database
-- Generation Time: Oct 25, 2024 at 09:19 AM
-- Server version: 10.4.4-MariaDB-1:10.4.4+maria~bionic
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+02:00";


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
                         `id` int(11) UNSIGNED NOT NULL,
                         `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
                         `slug` varchar(255) COLLATE latin1_general_ci NOT NULL,
                         `author` varchar(255) COLLATE latin1_general_ci NOT NULL,
                         `cover` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
                         `description` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                         `available` tinyint(1) NOT NULL DEFAULT 0,
                         `user_id` int(11) DEFAULT NULL,
                         `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                         `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
                                 `id` int(11) UNSIGNED NOT NULL,
                                 `receiver_id` bigint(20) NOT NULL,
                                 `sender_id` int(11) NOT NULL,
                                 `uuid` varchar(255) COLLATE latin1_general_ci NOT NULL,
                                 `archived` tinyint(1) NOT NULL,
                                 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_user`
--

CREATE TABLE `conversation_user` (
                                     `id` int(11) UNSIGNED NOT NULL,
                                     `conversation_id` int(11) DEFAULT NULL,
                                     `user_id` int(11) DEFAULT NULL,
                                     `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                     `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
                            `id` int(11) UNSIGNED NOT NULL,
                            `conversation_id` int(11) DEFAULT NULL,
                            `parent_id` int(11) DEFAULT NULL,
                            `content` longtext COLLATE latin1_general_ci NOT NULL,
                            `readed` tinyint(1) NOT NULL DEFAULT 0,
                            `sender_id` int(11) NOT NULL,
                            `receiver_id` int(11) NOT NULL,
                            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                         `id` int(11) UNSIGNED NOT NULL,
                         `username` varchar(64) COLLATE latin1_general_ci NOT NULL,
                         `email` varchar(64) COLLATE latin1_general_ci NOT NULL,
                         `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
                         `avatar` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
                         `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                         `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
    ADD PRIMARY KEY (`id`);

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
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation_user`
--
ALTER TABLE `conversation_user`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
