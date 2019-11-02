-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 02 Novembre 2019 à 23:04
-- Version du serveur :  5.7.27-0ubuntu0.18.04.1
-- Version de PHP :  7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `Smash2`
--

-- --------------------------------------------------------

--
-- Structure de la table `combat`
--

CREATE TABLE `combat` (
  `id` int(11) NOT NULL,
  `mode` varchar(255) NOT NULL DEFAULT '1v1',
  `termine` tinyint(1) NOT NULL DEFAULT '0',
  `nbTours` int(11) NOT NULL DEFAULT '0',
  `prochainAttaquant` int(11) DEFAULT NULL,
  `prochainVictime` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(2, 'admin', '$2y$10$/tOr21XdRhhMek8T6xD/9eLMPsyxrjpXn8AmPmvQ1nUx4LkphuJ9a', '2019-10-14 18:33:30', '2019-10-14 18:33:30', NULL, 0),
(3, 'blabla', '$2y$10$1gse1/FXVkCxiMuyIAadUe48FsIq4pTG4PTOagCePxazBgVVrF01q', '2019-10-29 17:59:16', '2019-10-29 17:59:16', '2019-10-29 17:59:16', 0),
(4, 'blabla', '$2y$10$01QwiiO3Yg1QsBevJ4HMh.K2qPNwJKqeyM6VKVZJhshgJEb0BFqUe', '2019-10-29 18:01:32', '2019-10-29 18:01:32', '2019-10-29 18:01:32', 0);

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
  `poids` int(11) NOT NULL,
  `combatGagne` int(11) DEFAULT '0',
  `combatPerdu` int(11) DEFAULT '0',
  `totalDegatInflige` int(11) NOT NULL DEFAULT '0',
  `totalDegatRecu` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `entite`
--

INSERT INTO `entite` (`id`, `type`, `nom`, `prenom`, `taille`, `pointVie`, `pointAtt`, `pointDef`, `pointAgi`, `photo`, `created_at`, `updated_at`, `deleted_at`, `poids`, `combatGagne`, `combatPerdu`, `totalDegatInflige`, `totalDegatRecu`) VALUES
(1, 'personnage', 'Bros', 'Mario', 150, 100, 30, 60, 100, NULL, '2019-11-02 21:49:54', '2019-11-02 21:49:54', NULL, 60, 0, 0, 0, 0),
(2, 'personnage', 'Bros', 'Luigi', 160, 90, 30, 70, 80, NULL, '2019-11-02 21:51:03', '2019-11-02 21:51:03', NULL, 60, 0, 0, 0, 0),
(3, 'personnage', 'Capitaine', 'Falcon', 180, 70, 80, 50, 90, NULL, '2019-11-02 21:52:10', '2019-11-02 21:52:10', NULL, 80, 0, 0, 0, 0),
(4, 'personnage', 'Mac', 'Little', 190, 140, 30, 80, 120, NULL, '2019-11-02 21:52:48', '2019-11-02 21:52:48', NULL, 110, 0, 0, 0, 0),
(5, 'personnage', 'Daisy', 'princesse', 170, 60, 15, 40, 80, NULL, '2019-11-02 21:53:35', '2019-11-02 21:53:35', NULL, 50, 0, 0, 0, 0),
(6, 'personnage', 'Peach', 'princesse', 170, 50, 15, 45, 90, NULL, '2019-11-02 21:54:12', '2019-11-02 21:54:12', NULL, 45, 0, 0, 0, 0),
(7, 'personnage', 'man', 'pac', 80, 140, 20, 150, 150, NULL, '2019-11-02 21:54:57', '2019-11-02 21:54:57', NULL, 9999, 0, 0, 0, 0),
(8, 'personnage', 'pit', 'ange', 175, 130, 45, 65, 170, NULL, '2019-11-02 21:55:56', '2019-11-02 21:55:56', NULL, 30, 0, 0, 0, 0),
(9, 'personnage', 'Zelda', 'princesse', 169, 80, 25, 60, 70, NULL, '2019-11-02 21:56:30', '2019-11-02 21:56:30', NULL, 69, 0, 0, 0, 0),
(10, 'personnage', 'vert', 'Link', 150, 150, 50, 80, 100, NULL, '2019-11-02 21:57:07', '2019-11-02 21:57:07', NULL, 60, 0, 0, 0, 0),
(11, 'monstre', 'Le gros', 'Bowser', 230, 200, 70, 200, 50, NULL, '2019-11-02 21:57:46', '2019-11-02 21:57:46', NULL, 400, 0, 0, 0, 0),
(12, 'monstre', 'Junior', 'bowser', 80, 120, 30, 80, 100, NULL, '2019-11-02 21:58:12', '2019-11-02 21:58:12', NULL, 40, 0, 0, 0, 0),
(13, 'monstre', 'Knight', 'meta', 30, 50, 70, 30, 150, NULL, '2019-11-02 21:58:47', '2019-11-02 21:58:47', NULL, 50, 0, 0, 0, 0),
(14, 'monstre', 'dadidou ', 'roi', 100, 50, 200, 100, 30, NULL, '2019-11-02 21:59:25', '2019-11-02 21:59:25', NULL, 200, 0, 0, 0, 0),
(15, 'monstre', 'didi', 'kong', 80, 70, 25, 30, 200, NULL, '2019-11-02 21:59:54', '2019-11-02 22:00:29', NULL, 40, 0, 0, 0, 0),
(16, 'monstre', 'Donkey', 'kong', 200, 300, 60, 100, 70, NULL, '2019-11-02 22:00:23', '2019-11-02 22:00:23', NULL, 300, 0, 0, 0, 0),
(17, 'monstre', 'le gros', 'wario', 150, 200, 70, 90, 60, NULL, '2019-11-02 22:01:20', '2019-11-02 22:01:20', NULL, 200, 0, 0, 0, 0),
(18, 'monstre', 'le maigre', 'waluigi', 190, 90, 20, 40, 150, NULL, '2019-11-02 22:01:45', '2019-11-02 22:01:45', NULL, 60, 0, 0, 0, 0),
(19, 'monstre', 'l\'apirateur', 'kirby', 30, 250, 10, 100, 80, NULL, '2019-11-02 22:02:24', '2019-11-02 22:02:24', NULL, 9999, 0, 0, 0, 0),
(20, 'monstre', 'ganon', 'gano', 300, 400, 60, 100, 60, NULL, '2019-11-02 22:03:10', '2019-11-02 22:03:10', NULL, 150, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `combat_id` int(11) NOT NULL,
  `entite_id` int(11) NOT NULL,
  `pointVie` int(11) NOT NULL,
  `nbAttaqueInflige` int(11) DEFAULT '0',
  `nbAttaqueRecu` int(11) DEFAULT '0',
  `degatInflige` int(11) DEFAULT '0',
  `degatRecu` int(11) DEFAULT '0',
  `defensif` tinyint(1) NOT NULL DEFAULT '0',
  `gagner` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Index pour la table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `combat`
--
ALTER TABLE `combat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `compteAdmin`
--
ALTER TABLE `compteAdmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `entite`
--
ALTER TABLE `entite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
