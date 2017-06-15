-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Lun 22 Mai 2017 à 15:59
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `portfolio`
--

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id_dep` int(11) NOT NULL,
  `code` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `departement` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `departement`
--

INSERT INTO `departement` (`id_dep`, `code`, `departement`) VALUES
(1, '01', 'Ain'),
(2, '02', 'Aisne'),
(3, '03', 'Allier'),
(4, '04', 'Alpes-de-Haute-Provence'),
(5, '05', 'Hautes-Alpes'),
(6, '06', 'Alpes-Maritimes'),
(7, '07', 'Ardèche'),
(8, '08', 'Ardennes'),
(9, '09', 'Ariège'),
(10, '10', 'Aube'),
(11, '11', 'Aude'),
(12, '12', 'Aveyron'),
(13, '13', 'Bouches-du-Rhône'),
(14, '14', 'Calvados'),
(15, '15', 'Cantal'),
(16, '16', 'Charente'),
(17, '17', 'Charente-Maritime'),
(18, '18', 'Cher'),
(19, '19', 'Corrèze'),
(20, '2a', 'Corse-du-sud'),
(21, '2b', 'Haute-corse'),
(22, '21', 'Côte-d\'or'),
(23, '22', 'Côtes-d\'armor'),
(24, '23', 'Creuse'),
(25, '24', 'Dordogne'),
(26, '25', 'Doubs'),
(27, '26', 'Drôme'),
(28, '27', 'Eure'),
(29, '28', 'Eure-et-Loir'),
(30, '29', 'Finistère'),
(31, '30', 'Gard'),
(32, '31', 'Haute-Garonne'),
(33, '32', 'Gers'),
(34, '33', 'Gironde'),
(35, '34', 'Hérault'),
(36, '35', 'Ile-et-Vilaine'),
(37, '36', 'Indre'),
(38, '37', 'Indre-et-Loire'),
(39, '38', 'Isère'),
(40, '39', 'Jura'),
(41, '40', 'Landes'),
(42, '41', 'Loir-et-Cher'),
(43, '42', 'Loire'),
(44, '43', 'Haute-Loire'),
(45, '44', 'Loire-Atlantique'),
(46, '45', 'Loiret'),
(47, '46', 'Lot'),
(48, '47', 'Lot-et-Garonne'),
(49, '48', 'Lozère'),
(50, '49', 'Maine-et-Loire'),
(51, '50', 'Manche'),
(52, '51', 'Marne'),
(53, '52', 'Haute-Marne'),
(54, '53', 'Mayenne'),
(55, '54', 'Meurthe-et-Moselle'),
(56, '55', 'Meuse'),
(57, '56', 'Morbihan'),
(58, '57', 'Moselle'),
(59, '58', 'Nièvre'),
(60, '59', 'Nord'),
(61, '60', 'Oise'),
(62, '61', 'Orne'),
(63, '62', 'Pas-de-Calais'),
(64, '63', 'Puy-de-Dôme'),
(65, '64', 'Pyrénées-Atlantiques'),
(66, '65', 'Hautes-Pyrénées'),
(67, '66', 'Pyrénées-Orientales'),
(68, '67', 'Bas-Rhin'),
(69, '68', 'Haut-Rhin'),
(70, '69', 'Rhône'),
(71, '70', 'Haute-Saône'),
(72, '71', 'Saône-et-Loire'),
(73, '72', 'Sarthe'),
(74, '73', 'Savoie'),
(75, '74', 'Haute-Savoie'),
(76, '75', 'Paris'),
(77, '76', 'Seine-Maritime'),
(78, '77', 'Seine-et-Marne'),
(79, '78', 'Yvelines'),
(80, '79', 'Deux-Sèvres'),
(81, '80', 'Somme'),
(82, '81', 'Tarn'),
(83, '82', 'Tarn-et-Garonne'),
(84, '83', 'Var'),
(85, '84', 'Vaucluse'),
(86, '85', 'Vendée'),
(87, '86', 'Vienne'),
(88, '87', 'Haute-Vienne'),
(89, '88', 'Vosges'),
(90, '89', 'Yonne'),
(91, '90', 'Territoire de Belfort'),
(92, '91', 'Essonne'),
(93, '92', 'Hauts-de-Seine'),
(94, '93', 'Seine-Saint-Denis'),
(95, '94', 'Val-de-Marne'),
(96, '95', 'Val-d\'oise'),
(97, '976', 'Mayotte'),
(98, '971', 'Guadeloupe'),
(99, '973', 'Guyane'),
(100, '972', 'Martinique'),
(101, '974', 'Réunion');

-- --------------------------------------------------------

--
-- Structure de la table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contrat` varchar(255) NOT NULL,
  `entreprise` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `begin_at` date NOT NULL,
  `end_at` date DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `dep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `experience`
--

INSERT INTO `experience` (`id`, `name`, `contrat`, `entreprise`, `ville`, `begin_at`, `end_at`, image_path, `dep_id`) VALUES
(1, 'Employée polyvalente de restauration', 'CDI', 'Flunch', 'Portet-sur-Garonne', '2013-10-18', '2017-01-01', 'logoFlunch.jpg', 32),
(2, 'Documentaliste stagiaire', 'Stage', 'Musée National du Sport', 'Nice', '2015-02-15', '2015-06-19', 'logoMNS.png', 6),
(3, 'Développeuse web stagiaire', 'Stage', 'Computys', 'Colomiers', '2015-11-16', '2015-12-18', 'logoComputys.png', 32),
(7, 'Développeuse web stagiaire', 'Stage', 'Web-Atrio', 'Blagnac', '2017-03-27', '2017-09-30', 'logo.png', 32);

-- --------------------------------------------------------

--
-- Structure de la table `experience_has_project`
--

CREATE TABLE `experience_has_project` (
  `experience_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `experience_has_project`
--

INSERT INTO `experience_has_project` (`experience_id`, `project_id`) VALUES
(2, 1),
(3, 6),
(7, 16);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `etablissement` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `begin_at` date NOT NULL,
  `end_at` date DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `mention` varchar(2) NOT NULL,
  `dep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `formation`
--

INSERT INTO `formation` (`id`, `name`, `type`, `etablissement`, `ville`, `begin_at`, `end_at`, image_path, `mention`, `dep_id`) VALUES
(1, 'Baccalauréat général, série Littéraire', 'Diplôme', 'Lycée Jean-Pierre Vernant', 'Pins-Justaret', '2009-09-02', '2010-07-02', '', 'P', 32),
(2, 'Licence Langues, Littérature et Civilisation Étrangères et Régionales (LLCER) anglais-occitan', 'Formation', 'Université Toulouse 2 Le Mirail', 'Toulouse', '2010-10-17', '2011-05-27', 'logoMirail.png', 'P', 32),
(7, 'Licence d\'Histoire', 'Diplôme', 'Université Toulouse Le Mirail', 'Toulouse', '2011-09-26', '2014-05-30', 'logoMirail.png', 'P', 32),
(9, 'Licence professionnelle Image et Histoire', 'Diplôme', 'Université Toulouse  Jean Jaurès', 'Toulouse', '2014-09-15', '2015-05-29', 'logoUT2J.png', 'AB', 32),
(10, 'Maîtrise d\'Information-Documentation', 'Diplôme', 'Université Toulouse 2 Jean Jaurès', 'Toulouse', '2015-09-07', '2016-06-10', 'logoUT2J.png', 'AB', 32),
(12, 'Master 2 Ingénierie de l\'Information Numérique, M2 I2N', 'Formation', 'Université Toulouse 2 Jean Jaurès', 'Toulouse', '2016-09-05', '2017-04-28', 'logoUT2J.png', 'P', 32);

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `image_path`, `year`) VALUES
(1, 'Exposition "En Mode Sport"', 'Participation à l\'élaboration du catalogue et de l\'exposition En Mode Sport (été 2015).', 'EnModeSport.jpg', 2015),
(6, 'Les Voyages de Petit Louis', 'Application permettant la gestion des archives de Louis Bernard Emont, alias Petit Louis, télégraphiste pour l\'aéropostale dans les années 30. ', '', 2016),
(7, 'Site du SPNS', 'Refonte et transfert du site du SPNS, anciennement hébergé chez SportsRegions.fr, sur la plateforme Wix.', 'siteSPNS.png', 2017),
(16, 'Portfolio', 'Présentation de mon portfolio de développeuse.', 'Portfolio.png', 2017);

-- --------------------------------------------------------

--
-- Structure de la table `project_has_technology`
--

CREATE TABLE `project_has_technology` (
  `project_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `project_has_technology`
--

INSERT INTO `project_has_technology` (`project_id`, `technology_id`) VALUES
(6, 1),
(7, 1),
(16, 1),
(6, 2),
(7, 2),
(16, 2),
(6, 11),
(16, 12),
(16, 16),
(16, 19);

-- --------------------------------------------------------

--
-- Structure de la table `technology`
--

CREATE TABLE `technology` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `technology`
--

INSERT INTO `technology` (`id`, `name`, `image_path`) VALUES
(1, 'HTML', 'logoHTML.png'),
(2, 'CSS', 'logoCSS.png'),
(11, 'PHP procédural', 'logoPHPOld.png'),
(12, 'JavaScript', 'logoJavascript.png'),
(16, 'Slim', 'logoSlim.png'),
(19, 'PHP objet', 'logoPHPPOO.png'),
(21, 'Bootstrap', 'logoBootstrap.png');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `roles`) VALUES
(1, 'elodie.vianai', 'elodie.vianai@hotmail.fr', 'cheval', 'admin'),
(2, 'elodiev31', 'elodiev31190@gmail.com', 'cheval', 'user');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id_dep`),
  ADD KEY `departement_code` (`code`),
  ADD KEY `id` (`id_dep`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_experience_departement1_idx` (`dep_id`);

--
-- Index pour la table `experience_has_project`
--
ALTER TABLE `experience_has_project`
  ADD PRIMARY KEY (`experience_id`,`project_id`),
  ADD KEY `fk_experience_has_project_project1_idx` (`project_id`),
  ADD KEY `fk_experience_has_project_experience1_idx` (`experience_id`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_formation_departement1_idx` (`dep_id`);

--
-- Index pour la table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `project_has_technology`
--
ALTER TABLE `project_has_technology`
  ADD PRIMARY KEY (`project_id`,`technology_id`),
  ADD KEY `fk_project_has_technology_technology1_idx` (`technology_id`),
  ADD KEY `fk_project_has_technology_project_idx` (`project_id`);

--
-- Index pour la table `technology`
--
ALTER TABLE `technology`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id_dep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `technology`
--
ALTER TABLE `technology`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `experience_has_project`
--
ALTER TABLE `experience_has_project`
  ADD CONSTRAINT `fk_experience_has_project_experience1` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_experience_has_project_project1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `formation`
--
ALTER TABLE `formation`
  ADD CONSTRAINT `fk_formation_departement1` FOREIGN KEY (`dep_id`) REFERENCES `departement` (`id_dep`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `project_has_technology`
--
ALTER TABLE `project_has_technology`
  ADD CONSTRAINT `fk_project_has_technology_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_project_has_technology_technology1` FOREIGN KEY (`technology_id`) REFERENCES `technology` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
