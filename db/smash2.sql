-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 14 Octobre 2019 à 20:47
-- Version du serveur :  5.7.27-0ubuntu0.18.04.1
-- Version de PHP :  7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `smash2`
--

-- --------------------------------------------------------

--
-- Structure de la table `combat`
--

CREATE TABLE `combat` (
  `id` int(11) NOT NULL,
  `idPersonnage` int(11) NOT NULL,
  `idMonstre` int(11) NOT NULL,
  `pointViePersonnage` int(11) NOT NULL,
  `pointVieMonstre` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `combat`
--

INSERT INTO `combat` (`id`, `idPersonnage`, `idMonstre`, `pointViePersonnage`, `pointVieMonstre`, `updated_at`, `created_at`, `deleted_at`) VALUES
(1, 1, 2, 28, -16, '2019-10-14 18:46:56', '2019-10-14 18:46:05', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `compteAdmin`
--

CREATE TABLE `compteAdmin` (
  `id` int(11) NOT NULL,
  `login` varchar(256) NOT NULL,
  `mdp` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `super` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `compteAdmin`
--

INSERT INTO `compteAdmin` (`id`, `login`, `mdp`, `created_at`, `updated_at`, `deleted_at`, `super`) VALUES
(1, 'root', '$2y$10$ZdhF63uUHeYsutMaDRv4VeIlw0b2B/i2Fa8c5Igzvk7QByZOOq6tG', '2019-10-14 18:33:02', NULL, NULL, 1),
(2, 'admin', '$2y$10$/tOr21XdRhhMek8T6xD/9eLMPsyxrjpXn8AmPmvQ1nUx4LkphuJ9a', '2019-10-14 18:33:30', '2019-10-14 18:33:30', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `entite`
--

CREATE TABLE `entite` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `taille` int(11) NOT NULL,
  `pointVie` int(11) NOT NULL,
  `pointAtt` int(11) NOT NULL,
  `pointDef` int(11) NOT NULL,
  `pointAgi` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `poids` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `entite`
--

INSERT INTO `entite` (`id`, `type`, `nom`, `prenom`, `taille`, `pointVie`, `pointAtt`, `pointDef`, `pointAgi`, `photo`, `created_at`, `updated_at`, `deleted_at`, `poids`) VALUES
(1, 'personnage', 'Mario', 'Bross', 120, 100, 20, 10, 100, '31353731303738353237980fd013ec49cba8.jpg', '2019-10-14 18:42:07', '2019-10-14 18:42:07', NULL, 50),
(2, 'monstre', 'Godzilla', 'The monster', 250, 200, 40, 40, 20, '313537313037383732349326703bc26c5932.jpeg', '2019-10-14 18:45:24', '2019-10-14 18:45:34', NULL, 150);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `combat`
--
ALTER TABLE `combat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `compteAdmin`
--
ALTER TABLE `compteAdmin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entite`
--
ALTER TABLE `entite`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `combat`
--
ALTER TABLE `combat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `compteAdmin`
--
ALTER TABLE `compteAdmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `entite`
--
ALTER TABLE `entite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
