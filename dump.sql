-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Aug 11, 2024 at 08:31 AM
-- Server version: 10.4.4-MariaDB-1:10.4.4+maria~bionic
-- PHP Version: 8.2.15

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

/******************************************************************/
/***************************** USERS ******************************/
/******************************************************************/

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Isaias Hermann', 'napoleon71@example.com', '$argon2id$v=19$m=65536,t=4,p=1$OEwzU1FHUms1R1JNZ200TQ$pgVKFC9bJK0amRKC30xPAOuZdv7mNw7C4qa+zqVSUhs', NULL, '2024-07-26 12:56:18', '2024-07-26 12:56:18'),
(2, 'Hannah Homenick', 'adolfo82@example.net', '$argon2id$v=19$m=65536,t=4,p=1$OS5pMlpDTDNkRkp6WFIveA$cqdszP/BRzQE8MkTYSzimW7r2XLwLiBY/MAEbD23ByI', NULL, '2024-07-26 12:56:18', '2024-07-26 12:56:18'),
(3, 'Mr. Newell Treutel DVM', 'yhodkiewicz@example.org', '$argon2id$v=19$m=65536,t=4,p=1$Rmppck5tbUlQVUExRG0wSw$3Wg23MpQHqFwo5ZCyAyCNl6kf9hiRjDoiP5jIcl91rU', NULL, '2024-07-26 12:56:18', '2024-07-26 12:56:18'),
(4, 'Mr. Tyrique Gislason IV', 'viviane09@example.com', '$argon2id$v=19$m=65536,t=4,p=1$Rmppck5tbUlQVUExRG0wSw$3Wg23MpQHqFwo5ZCyAyCNl6kf9hiRjDoiP5jIcl91rU', NULL, '2024-07-26 12:56:18', '2024-07-26 12:56:18'),
(5, 'Dorothy Tromp', 'grady.ericka@example.com', '$argon2id$v=19$m=65536,t=4,p=1$Rmppck5tbUlQVUExRG0wSw$3Wg23MpQHqFwo5ZCyAyCNl6kf9hiRjDoiP5jIcl91rU', NULL, '2024-07-26 12:56:18', '2024-07-26 12:56:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=502;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/******************************************************************/
/***************************** BOOKS ******************************/
/******************************************************************/

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `slug` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `author` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cover` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `description` longtext COLLATE latin1_general_ci NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


INSERT INTO `books` (`id`, `title`, `slug`, `author`, `cover`, `description`, `available`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Enim architecto rerum id quisquam quas.', 'enim-architecto-rerum-id-quisquam-quas', 'Mr. Rickie Ratke', NULL, 'Soluta voluptates ad harum eum. Laboriosam impedit provident accusamus consequatur dolorum.', 1, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(2, 'Earum ut quia cumque est aut cumque optio.', 'earum-ut-quia-cumque-est-aut-cumque-optio', 'Prof. Marley Hirthe', NULL, 'Iste illo ut illo autem. Voluptas alias velit facere soluta dolore consequatur. Non ad facilis nam ea rerum velit tempora.', 1, 4, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(3, 'Vel dolores dicta minus distinctio rem est dolore qui.', 'vel-dolores-dicta-minus-distinctio-rem-est-dolore-qui', 'Demario Larson', NULL, 'Deserunt accusamus ullam totam porro et et doloremque. Iure ut mollitia saepe minus. Architecto minus molestias officiis est. Enim ipsam hic ut dolorem. Sunt velit ab aspernatur vel vel cumque.', 1, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(4, 'Beatae a deserunt pariatur omnis voluptatem.', 'beatae-a-deserunt-pariatur-omnis-voluptatem', 'Ms. Leatha Parker Sr.', NULL, 'Deserunt laborum molestiae corrupti repudiandae. Aut ut laudantium suscipit et blanditiis consequatur a veritatis. Nam adipisci praesentium adipisci veniam omnis. Dignissimos et consectetur beatae dicta cumque fugit quo.', 0, 2, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(5, 'Quo explicabo in vel sunt ratione.', 'quo-explicabo-in-vel-sunt-ratione', 'Ottilie Blanda', NULL, 'Dignissimos eligendi quod deleniti eos perferendis iusto. Et in laboriosam nisi libero consectetur non dicta. Et unde quia adipisci. Illum magni eum dicta. Officiis sint enim sit excepturi laborum veniam rem doloribus.', 0, 2, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(6, 'Sit rerum qui sunt nihil eos rem.', 'sit-rerum-qui-sunt-nihil-eos-rem', 'Justice Armstrong', NULL, 'Dolor qui perferendis qui impedit laborum et. Et labore natus similique possimus tempore fugit. Voluptate ut necessitatibus reiciendis accusamus aspernatur quas dolor.', 1, 2, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(7, 'Sint et dolorum neque.', 'sint-et-dolorum-neque', 'Prof. Declan Casper Jr.', NULL, 'Non et similique aliquam. Voluptatem consequatur quod fugiat consectetur. Praesentium soluta dolorum nostrum et qui.', 1, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(8, 'Tempore maxime sit ipsa aperiam temporibus veniam assumenda.', 'tempore-maxime-sit-ipsa-aperiam-temporibus-veniam-assumenda', 'Mrs. Aida McDermott', NULL, 'Nisi praesentium deserunt ea qui facere eius. Alias quas est voluptatum omnis. Saepe laboriosam nulla et animi et omnis.', 0, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(9, 'Numquam sint autem delectus nostrum provident facere consequuntur.', 'numquam-sint-autem-delectus-nostrum-provident-facere-consequuntur', 'Raven Mueller', NULL, 'Omnis cum optio impedit. Nihil et unde nemo non voluptas. Aut officia est qui impedit eos harum voluptatem alias. Voluptates consectetur aliquam in impedit.', 1, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(10, 'Ab tenetur molestiae repellat.', 'ab-tenetur-molestiae-repellat', 'Shaun Nolan', NULL, 'Totam est consequatur alias non temporibus. Quo consequuntur dolore sit quod asperiores. Quidem sunt ratione ab rerum et cupiditate vitae. Culpa modi consequatur qui veniam odit voluptatibus dicta.', 1, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(11, 'Blanditiis enim sed et est temporibus.', 'blanditiis-enim-sed-et-est-temporibus', 'Melany Thompson', NULL, 'Quo nisi quisquam quasi quia voluptatem maiores quisquam. Quod omnis doloremque hic harum commodi recusandae. Ut sed rem voluptatum sunt debitis voluptates. Vel blanditiis corrupti vel sed.', 1, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(12, 'Quam quia in aut officiis voluptatum praesentium.', 'quam-quia-in-aut-officiis-voluptatum-praesentium', 'Davin Borer', NULL, 'Excepturi dignissimos inventore ratione nisi dolore assumenda deleniti. In non dignissimos aut autem accusamus nostrum omnis. Esse ullam nostrum libero et et. Corporis est autem eveniet expedita est.', 0, 3, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(13, 'Quod quas dolorem vel.', 'quod-quas-dolorem-vel', 'Toy Kshlerin', NULL, 'Voluptatem debitis ipsam vel neque non. Et dolore est eligendi voluptates et. Cupiditate eius aliquam magnam aspernatur.', 1, 3, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(14, 'Corrupti aperiam molestias et optio et.', 'corrupti-aperiam-molestias-et-optio-et', 'Ayla Maggio', NULL, 'Dolorum saepe adipisci rem. In rem in aspernatur et excepturi est. Vel iste sint maiores in numquam consequuntur delectus temporibus. Est voluptatem in fugit non nisi labore eaque ut.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(15, 'Et doloribus qui dolor.', 'et-doloribus-qui-dolor', 'Libbie Will', NULL, 'Et maiores laborum vitae est temporibus voluptatibus sed voluptates. Et tempore iure mollitia quibusdam occaecati et et in. Rem nam facere impedit voluptates maiores minus minus. Mollitia accusantium optio tempore illo quibusdam dolorum sint. Doloremque ex atque dolor sint vel sequi.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(16, 'Nihil accusantium et molestiae et omnis laudantium molestiae.', 'nihil-accusantium-et-molestiae-et-omnis-laudantium-molestiae', 'Elton Little V', NULL, 'Nobis aliquam aut pariatur magni. In quis et illo. Id et ad enim quibusdam. Dolorem in velit rem placeat vel.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(17, 'A et non facilis repudiandae aut aut.', 'a-et-non-facilis-repudiandae-aut-aut', 'Dr. Dustin McGlynn I', NULL, 'Pariatur iure perspiciatis saepe ipsa doloremque. Est non iste architecto non ipsum. Laborum molestiae repellat ut ad itaque velit reprehenderit. Suscipit vero doloremque et accusamus fugiat non amet.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(18, 'Totam porro ducimus accusantium dicta laudantium.', 'totam-porro-ducimus-accusantium-dicta-laudantium', 'Brandon Grimes Sr.', NULL, 'Tenetur suscipit aut molestiae aspernatur quisquam recusandae nesciunt adipisci. Reprehenderit tenetur modi fugiat repellendus et id consectetur. Repudiandae temporibus minima est impedit veritatis et ullam. Voluptatem architecto quis vitae quo aut dolor distinctio dicta.', 0, 3, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(19, 'Quisquam voluptas nobis ea maiores quia exercitationem unde.', 'quisquam-voluptas-nobis-ea-maiores-quia-exercitationem-unde', 'Miss Rossie Brekke IV', NULL, 'Corrupti inventore ipsa doloremque minus asperiores veritatis omnis quae. Quis aut molestiae doloremque alias quos voluptates dicta sunt. A et quos itaque numquam. Asperiores ut modi provident illo magnam.', 1, 1, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(20, 'Voluptatem saepe saepe earum itaque et sequi cupiditate veniam.', 'voluptatem-saepe-saepe-earum-itaque-et-sequi-cupiditate-veniam', 'Ladarius Beier', NULL, 'Cupiditate dolor voluptatem dignissimos temporibus labore ut. Sequi reiciendis maiores occaecati exercitationem. Rerum ea molestiae explicabo.', 0, 2, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(21, 'Quis ipsa dolorum omnis.', 'quis-ipsa-dolorum-omnis', 'Miss Faye Halvorson DDS', NULL, 'Incidunt vero laborum nihil dolor aut. Autem omnis quisquam explicabo. Sint rem consectetur aut voluptates omnis sit et ut. Eligendi accusantium voluptas omnis.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(22, 'Ut iusto exercitationem et facere vel asperiores.', 'ut-iusto-exercitationem-et-facere-vel-asperiores', 'Dr. Josh Goyette', NULL, 'Ab sed consequatur sed consequatur sit molestiae. Ut molestias labore ullam. Illo aliquam voluptatem et excepturi quasi exercitationem accusantium. Perferendis accusantium neque dolor odit saepe debitis.', 0, 5, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(23, 'Est et accusamus dolorum voluptates excepturi.', 'est-et-accusamus-dolorum-voluptates-excepturi', 'Miss Dianna Denesik', NULL, 'Non libero blanditiis reiciendis numquam nemo. Impedit nihil qui eos molestias dolore tempora dolores. Consequatur quisquam quod pariatur unde maiores ut enim. Aut dolor ab ipsam sint ut excepturi.', 0, 4, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(24, 'Ullam magni voluptas deleniti fugit voluptas earum consequatur.', 'ullam-magni-voluptas-deleniti-fugit-voluptas-earum-consequatur', 'Prof. Kenyatta Reinger V', NULL, 'Nulla sit unde perferendis et enim. Excepturi assumenda eaque molestias. Alias sequi maiores omnis beatae sit velit.', 0, 4, '2024-07-26 12:56:25', '2024-07-26 12:56:25'),
(25, 'Doloribus suscipit animi accusamus quas voluptatem reiciendis aliquam.', 'doloribus-suscipit-animi-accusamus-quas-voluptatem-reiciendis-aliquam', 'Deangelo McCullough', NULL, 'Ea nemo voluptas eaque sit. Eos debitis quidem vel. Nesciunt qui quaerat praesentium. Quo quo laboriosam blanditiis.', 1, 4, '2024-07-26 12:56:25', '2024-07-26 12:56:25');

/******************************************************************/
/************************* CONVERSATIONS **************************/
/******************************************************************/

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `receiver_id` bigint(20) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/******************************************************************/
/*********************** CONVERSATION_USER ************************/
/******************************************************************/

CREATE TABLE `conversation_user` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*******************************************************************/
/**************************** MESSAGES *****************************/
/*******************************************************************/

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE latin1_general_ci NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
