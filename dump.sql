-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Oct 30, 2024 at 05:51 AM
-- Server version: 11.4.2-MariaDB-ubu2404
-- PHP Version: 8.2.20

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
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `slug`, `author`, `cover`, `description`, `available`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Harry Potter à l\'école des Sorciers', 'harry-potter-a-l-ecole-des-sorciers', 'JK Rowling', NULL, 'Harry Potter à l’école des sorciers raconte l’histoire d’Harry, un garçon orphelin maltraité par sa famille adoptive, les Dursley, qui découvre qu’il est un sorcier. À onze ans, il reçoit une lettre d’admission à Poudlard, une école de magie où il rencontre des amis, comme Ron et Hermione, ainsi que de puissants ennemis. Au cours de sa première année, Harry apprend à utiliser la magie, explore les mystères de l’école et découvre des secrets sur ses parents et leur mort mystérieuse. Il se confronte également aux dangers liés au retour potentiel du sorcier maléfique Voldemort, responsable de la mort de ses parents. Dans une ambiance à la fois magique et mystérieuse, Harry se forge peu à peu un destin unique, devenant plus qu’un simple enfant sorcier et réalisant l’ampleur des défis qu’il aura à surmonter.', 1, 1, '2024-10-30 05:43:30', '2024-10-30 05:43:30'),
(2, 'Harry Potter et la Chambre des Secrets', 'harry-potter-et-la-chambre-des-secrets', 'JK Rowling', NULL, 'Dans Harry Potter et la Chambre des Secrets, Harry entame sa deuxième année à Poudlard, malgré des avertissements mystérieux d\'un elfe nommé Dobby, qui tente de l’empêcher d’y retourner. Une fois à l’école, des événements étranges surviennent : des élèves sont retrouvés pétrifiés, et un sinistre message annonce la réouverture de la Chambre des Secrets, un lieu légendaire construit par Salazar Serpentard, destiné à abriter un monstre capable de purger l’école des \"Sang-de-Bourbe\". Aidé de Ron et Hermione, Harry mène l’enquête pour découvrir la vérité derrière les attaques et la mystérieuse Chambre. Il apprend davantage sur les origines sombres de l\'école, ainsi que sur Voldemort, qui semble lié aux incidents. Plongé dans un danger grandissant, Harry découvre finalement la Chambre des Secrets et y affronte le basilic, le monstre qui terrorise Poudlard, tout en perçant un peu plus les mystères de son passé.', 0, 1, '2024-10-30 05:44:32', '2024-10-30 05:44:32'),
(3, 'Harry Potter et le Prisonnier d\'Azkaban', 'harry-potter-et-le-prisonnier-d-azkaban', 'JK Rowling', NULL, 'Dans Harry Potter et le Prisonnier d\'Azkaban, Harry entame sa troisième année à Poudlard sous la menace d\'un dangereux prisonnier échappé : Sirius Black, un ancien allié de Voldemort, qui semble vouloir le tuer. En parallèle, de mystérieux Détraqueurs, gardiens de la prison d’Azkaban, surveillent l\'école, plongeant Harry dans des états de terreur à chaque rencontre. Avec l’aide de ses amis, Ron et Hermione, il découvre que Sirius Black est lié à son passé et à la trahison ayant conduit à la mort de ses parents. En menant ses recherches, Harry apprend que les apparences peuvent être trompeuses et que les liens familiaux sont plus complexes qu’il ne le pensait. Entre secrets enfouis et révélations surprenantes, Harry découvre la vérité sur Sirius et leur lien inattendu. Ce tome explore le courage, la justice, et la force des amitiés, avec une touche de noirceur et de mystère qui marque un tournant dans la série.', 1, 2, '2024-10-30 05:45:54', '2024-10-30 05:45:54'),
(4, 'Harry Potter et la Coupe de Feu', 'harry-potter-et-la-coupe-de-feu', 'JK Rowling', NULL, 'Dans Harry Potter et la Coupe de Feu, Harry entame sa quatrième année à Poudlard et participe à la Coupe du Monde de Quidditch, où une attaque de Mangemorts annonce le retour des forces de Voldemort. Peu après, Poudlard accueille un événement exceptionnel : le Tournoi des Trois Sorciers, une compétition prestigieuse réunissant trois écoles de magie. Contre toute attente, le nom de Harry est mystérieusement inscrit dans la Coupe de Feu, le forçant à participer malgré son jeune âge et le danger des épreuves. Entre dragons, créatures aquatiques et labyrinthes piégés, Harry affronte des défis périlleux aux côtés de ses amis, tout en essayant de percer l’identité de celui qui l’a inscrit dans ce tournoi risqué. La finale du tournoi le confronte à la résurrection de Voldemort, marquant le début d’une sombre ère pour le monde des sorciers. Ce quatrième tome explore des thèmes plus sombres, mêlant courage, trahison et préparation aux épreuves inévitables à venir.', 1, 2, '2024-10-30 05:46:55', '2024-10-30 05:46:55'),
(5, 'Harry Potter et l\'Ordre du Phoenix', 'harry-potter-et-l-ordre-du-phoenix', 'JK Rowling', NULL, 'Dans Harry Potter et l\'Ordre du Phénix, Harry entre dans sa cinquième année à Poudlard, confronté à l’incrédulité du monde magique qui refuse de croire au retour de Voldemort. Sous la surveillance stricte de Dolores Ombrage, une nouvelle professeure envoyée par le ministère de la Magie, l’école est soumise à des règles répressives, limitant les cours de défense et la liberté des élèves. Sentant la menace grandir, Harry, avec l’aide de ses amis, forme secrètement \"l\'Armée de Dumbledore\", un groupe d\'élèves s\'entraînant pour résister aux forces obscures. Entre visions inquiétantes et attaques psychiques de Voldemort, Harry découvre une connexion profonde et troublante avec le mage noir. L\'Ordre du Phénix, une organisation secrète fondée pour contrer Voldemort, lutte aussi pour protéger le monde sorcier. Ce tome explore la rébellion, l\'amitié et les lourds sacrifices que Harry doit accepter dans sa bataille contre les ténèbres.', 0, 3, '2024-10-30 05:48:21', '2024-10-30 05:48:21'),
(6, 'Harry Potter et le Prince de Sang-Mêlé', 'harry-potter-et-le-prince-de-sang-mele', 'JK Rowling', NULL, 'Dans Harry Potter et le Prince de Sang-Mêlé, Harry entame sa sixième année à Poudlard alors que le monde sorcier sombre dans la peur face au retour de Voldemort. Avec l’aide de Dumbledore, il explore le passé du mage noir pour comprendre les secrets de son immortalité, en particulier les Horcruxes, des objets ensorcelés où Voldemort a caché des fragments de son âme. Parallèlement, Harry découvre un mystérieux livre de potions annoté par un certain \"Prince de Sang-Mêlé\", qui l’aide à exceller en classe, tout en soulevant des questions inquiétantes sur l’identité de cet ancien élève. Tandis que les forces de Voldemort infiltrent même Poudlard, Harry et ses amis doivent faire face aux trahisons et aux pertes dévastatrices. Ce tome plonge plus profondément dans la psychologie des personnages et explore les thèmes de l’amour, de la loyauté et du sacrifice, alors que le combat final approche inexorablement.', 1, 3, '2024-10-30 05:49:30', '2024-10-30 05:49:30'),
(7, 'Harry Potter et les Reliques de la Mort', 'harry-potter-et-les-reliques-de-la-mort', 'JK Rowling', NULL, 'Dans Harry Potter et les Reliques de la Mort, Harry, Ron et Hermione quittent Poudlard pour une mission périlleuse : détruire les Horcruxes, ces objets renfermant des fragments de l\'âme de Voldemort, pour le rendre mortel. Guidés par des indices laissés par Dumbledore, ils découvrent également l’existence des Reliques de la Mort, trois artefacts légendaires qui confèrent un pouvoir immense à leur possesseur. Poursuivis par les forces de Voldemort et en proie aux tensions, le trio affronte des défis qui mettent à l’épreuve leur amitié et leur détermination. Tandis que la guerre éclate dans le monde magique, Harry se retrouve face à son destin et à un ultime sacrifice, découvrant le véritable sens du courage et de l’amour. Ce dernier tome, sombre et intense, mène à la confrontation finale entre Harry et Voldemort, clôturant l’histoire avec des thèmes de loyauté, de rédemption et d’espoir.', 1, 4, '2024-10-30 05:50:36', '2024-10-30 05:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
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
  `content` longtext NOT NULL,
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
  `username` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'JohnDoe', 'john@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$WHBTS0gwZk5wTDdVOGRKeg$R24Byvxsst4BYtHc8G4v3srQgqPp3LKsMD0AjhjsaBM', NULL, '2024-10-30 05:40:44', '2024-10-30 05:40:44'),
(2, 'JaneDoe', 'jane@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$ME9MczBqWXNsN2ZHRVJjbA$TKO1GfROrTbzEFoDY125hCZ5aUNeafsghaE3K3J4K7k', NULL, '2024-10-30 05:41:10', '2024-10-30 05:41:10'),
(3, 'JohannaDoe', 'johanna@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$Vi5BeU1ZTWU0dUpralZ5Sg$ABvXgtOoyq2lx8AKI1ctuTYDHjEgJStEN4s4QYYAZmM', NULL, '2024-10-30 05:41:36', '2024-10-30 05:41:36'),
(4, 'JackDoe', 'jack@doe.fr', '$argon2id$v=19$m=65536,t=4,p=1$eHhSVjVGaFpZWVVFRXA5ag$anrrp+1nMg5kp2CkQmBlEqh/9v+G62RW5kR934/A8CE', NULL, '2024-10-30 05:42:00', '2024-10-30 05:42:00');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
