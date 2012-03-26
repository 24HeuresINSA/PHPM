-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 25 Mars 2012 à 22:49
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.6

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Base de données: `phpm`
--

-- --------------------------------------------------------

--
-- Structure de la table `BesoinMateriel`
--

DROP TABLE IF EXISTS `BesoinMateriel`;
CREATE TABLE `BesoinMateriel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tache_id` int(11) DEFAULT NULL,
  `materiel_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF9D6A0ED2235D39` (`tache_id`),
  KEY `IDX_CF9D6A0E16880AAF` (`materiel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `BesoinMateriel`
--


-- --------------------------------------------------------

--
-- Structure de la table `BesoinOrga`
--

DROP TABLE IF EXISTS `BesoinOrga`;
CREATE TABLE `BesoinOrga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipe_id` int(11) DEFAULT NULL,
  `nbOrgasNecessaires` smallint(6) NOT NULL,
  `plageHoraire_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BDE21E3A2D9955DA` (`plageHoraire_id`),
  KEY `IDX_BDE21E3A6D861B89` (`equipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `BesoinOrga`
--


-- --------------------------------------------------------

--
-- Structure de la table `Categorie`
--

DROP TABLE IF EXISTS `Categorie`;
CREATE TABLE `Categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `couleur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `Categorie`
--


-- --------------------------------------------------------

--
-- Structure de la table `Commentaire`
--

DROP TABLE IF EXISTS `Commentaire`;
CREATE TABLE `Commentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tache_id` int(11) DEFAULT NULL,
  `orga_id` int(11) DEFAULT NULL,
  `debut` datetime NOT NULL,
  `texte` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E16CE76BD2235D39` (`tache_id`),
  KEY `IDX_E16CE76B97F068A1` (`orga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `Commentaire`
--


-- --------------------------------------------------------

--
-- Structure de la table `Confiance`
--

DROP TABLE IF EXISTS `Confiance`;
CREATE TABLE `Confiance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `valeur` smallint(6) NOT NULL,
  `couleur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `Confiance`
--

INSERT INTO `Confiance` VALUES(1, 'Soft', 100, 'blue');
INSERT INTO `Confiance` VALUES(2, 'Confiance', 500, 'vert');
INSERT INTO `Confiance` VALUES(3, 'Hard', 1000, 'orange');

-- --------------------------------------------------------

--
-- Structure de la table `Config`
--

DROP TABLE IF EXISTS `Config`;
CREATE TABLE `Config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D3262A4A5BF54558` (`field`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `Config`
--

INSERT INTO `Config` VALUES(1, 'manifestation_plages', 'Plages de la manifestation', '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"},"2":{"nom":"Manif","debut":"2012-05-24 00:00","fin":"2012-05-27 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}');
INSERT INTO `Config` VALUES(2, 'manifestation_organisation_nom', 'Nom de l''organisation', '24 Heures de l''INSA');
INSERT INTO `Config` VALUES(3, 'phpm_config_initiale', 'PHPlanningMaker configuré', '1');
INSERT INTO `Config` VALUES(4, 'server_baseurl', 'URL du serveur', 'localhost:8888');
INSERT INTO `Config` VALUES(5, 'manifestation_nom', 'Nom de la manifestation', '24 Heures de l''INSA 38e');
INSERT INTO `Config` VALUES(6, 'manifestation_orga_responsableconfiancemin', 'Confiance minimale pour être responsable d''une tâche', '500');
INSERT INTO `Config` VALUES(7, 'manifestation_permis_libelles', 'Libellés des permis', '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }');
INSERT INTO `Config` VALUES(8, 'animations_db_path', 'Chemin vers la BDD des anims', '');
INSERT INTO `Config` VALUES(9, 'manifestation_edition', 'Numéro de l''édition', '38');

-- --------------------------------------------------------

--
-- Structure de la table `Creneau`
--

DROP TABLE IF EXISTS `Creneau`;
CREATE TABLE `Creneau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disponibilite_id` int(11) DEFAULT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `plageHoraire_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_36DBB2C32B9D6493` (`disponibilite_id`),
  KEY `IDX_36DBB2C32D9955DA` (`plageHoraire_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=365 ;

--
-- Contenu de la table `Creneau`
--

INSERT INTO `Creneau` VALUES(67, NULL, '2012-05-25 21:00:00', '2012-05-25 23:15:00', 7);
INSERT INTO `Creneau` VALUES(68, NULL, '2012-05-25 23:00:00', '2012-05-26 01:15:00', 7);
INSERT INTO `Creneau` VALUES(69, NULL, '2012-05-26 01:00:00', '2012-05-26 03:15:00', 7);
INSERT INTO `Creneau` VALUES(70, NULL, '2012-05-26 03:00:00', '2012-05-26 05:00:00', 7);
INSERT INTO `Creneau` VALUES(71, NULL, '2012-05-25 21:00:00', '2012-05-25 23:15:00', 7);
INSERT INTO `Creneau` VALUES(72, NULL, '2012-05-25 23:00:00', '2012-05-26 01:15:00', 7);
INSERT INTO `Creneau` VALUES(73, NULL, '2012-05-26 01:00:00', '2012-05-26 03:15:00', 7);
INSERT INTO `Creneau` VALUES(74, NULL, '2012-05-26 03:00:00', '2012-05-26 05:00:00', 7);
INSERT INTO `Creneau` VALUES(75, NULL, '2012-05-25 21:00:00', '2012-05-25 23:15:00', 7);
INSERT INTO `Creneau` VALUES(76, NULL, '2012-05-25 23:00:00', '2012-05-26 01:15:00', 7);
INSERT INTO `Creneau` VALUES(77, NULL, '2012-05-26 01:00:00', '2012-05-26 03:15:00', 7);
INSERT INTO `Creneau` VALUES(78, NULL, '2012-05-26 03:00:00', '2012-05-26 05:00:00', 7);
INSERT INTO `Creneau` VALUES(119, NULL, '2012-05-26 16:00:00', '2012-05-26 17:15:00', 10);
INSERT INTO `Creneau` VALUES(120, NULL, '2012-05-26 17:00:00', '2012-05-26 18:15:00', 10);
INSERT INTO `Creneau` VALUES(121, NULL, '2012-05-26 18:00:00', '2012-05-26 19:15:00', 10);
INSERT INTO `Creneau` VALUES(122, NULL, '2012-05-26 19:00:00', '2012-05-26 20:15:00', 10);
INSERT INTO `Creneau` VALUES(123, NULL, '2012-05-26 20:00:00', '2012-05-26 21:15:00', 10);
INSERT INTO `Creneau` VALUES(124, NULL, '2012-05-26 21:00:00', '2012-05-26 22:15:00', 10);
INSERT INTO `Creneau` VALUES(125, NULL, '2012-05-26 22:00:00', '2012-05-26 23:00:00', 10);
INSERT INTO `Creneau` VALUES(126, NULL, '2012-05-27 16:00:00', '2012-05-27 17:15:00', 11);
INSERT INTO `Creneau` VALUES(127, NULL, '2012-05-27 17:00:00', '2012-05-27 18:15:00', 11);
INSERT INTO `Creneau` VALUES(128, NULL, '2012-05-27 18:00:00', '2012-05-27 19:15:00', 11);
INSERT INTO `Creneau` VALUES(129, NULL, '2012-05-27 19:00:00', '2012-05-27 20:15:00', 11);
INSERT INTO `Creneau` VALUES(130, NULL, '2012-05-27 20:00:00', '2012-05-27 21:15:00', 11);
INSERT INTO `Creneau` VALUES(131, NULL, '2012-05-27 21:00:00', '2012-05-27 22:15:00', 11);
INSERT INTO `Creneau` VALUES(132, NULL, '2012-05-27 22:00:00', '2012-05-27 23:00:00', 11);
INSERT INTO `Creneau` VALUES(133, NULL, '2012-05-26 20:00:00', '2012-05-26 21:15:00', 12);
INSERT INTO `Creneau` VALUES(134, NULL, '2012-05-26 21:00:00', '2012-05-26 22:15:00', 12);
INSERT INTO `Creneau` VALUES(135, NULL, '2012-05-26 22:00:00', '2012-05-26 23:15:00', 12);
INSERT INTO `Creneau` VALUES(136, NULL, '2012-05-26 23:00:00', '2012-05-27 00:15:00', 12);
INSERT INTO `Creneau` VALUES(137, NULL, '2012-05-27 00:00:00', '2012-05-27 01:15:00', 12);
INSERT INTO `Creneau` VALUES(138, NULL, '2012-05-27 01:00:00', '2012-05-27 02:15:00', 12);
INSERT INTO `Creneau` VALUES(139, NULL, '2012-05-27 02:00:00', '2012-05-27 03:15:00', 12);
INSERT INTO `Creneau` VALUES(140, NULL, '2012-05-27 03:00:00', '2012-05-27 04:15:00', 12);
INSERT INTO `Creneau` VALUES(141, NULL, '2012-05-27 04:00:00', '2012-05-27 05:00:00', 12);
INSERT INTO `Creneau` VALUES(142, NULL, '2012-05-26 20:00:00', '2012-05-26 21:15:00', 12);
INSERT INTO `Creneau` VALUES(143, NULL, '2012-05-26 21:00:00', '2012-05-26 22:15:00', 12);
INSERT INTO `Creneau` VALUES(144, NULL, '2012-05-26 22:00:00', '2012-05-26 23:15:00', 12);
INSERT INTO `Creneau` VALUES(145, NULL, '2012-05-26 23:00:00', '2012-05-27 00:15:00', 12);
INSERT INTO `Creneau` VALUES(146, NULL, '2012-05-27 00:00:00', '2012-05-27 01:15:00', 12);
INSERT INTO `Creneau` VALUES(147, NULL, '2012-05-27 01:00:00', '2012-05-27 02:15:00', 12);
INSERT INTO `Creneau` VALUES(148, NULL, '2012-05-27 02:00:00', '2012-05-27 03:15:00', 12);
INSERT INTO `Creneau` VALUES(149, NULL, '2012-05-27 03:00:00', '2012-05-27 04:15:00', 12);
INSERT INTO `Creneau` VALUES(150, NULL, '2012-05-27 04:00:00', '2012-05-27 05:00:00', 12);
INSERT INTO `Creneau` VALUES(151, NULL, '2012-05-26 20:00:00', '2012-05-26 21:15:00', 12);
INSERT INTO `Creneau` VALUES(152, NULL, '2012-05-26 21:00:00', '2012-05-26 22:15:00', 12);
INSERT INTO `Creneau` VALUES(153, NULL, '2012-05-26 22:00:00', '2012-05-26 23:15:00', 12);
INSERT INTO `Creneau` VALUES(154, NULL, '2012-05-26 23:00:00', '2012-05-27 00:15:00', 12);
INSERT INTO `Creneau` VALUES(155, NULL, '2012-05-27 00:00:00', '2012-05-27 01:15:00', 12);
INSERT INTO `Creneau` VALUES(156, NULL, '2012-05-27 01:00:00', '2012-05-27 02:15:00', 12);
INSERT INTO `Creneau` VALUES(157, NULL, '2012-05-27 02:00:00', '2012-05-27 03:15:00', 12);
INSERT INTO `Creneau` VALUES(158, NULL, '2012-05-27 03:00:00', '2012-05-27 04:15:00', 12);
INSERT INTO `Creneau` VALUES(159, NULL, '2012-05-27 04:00:00', '2012-05-27 05:00:00', 12);
INSERT INTO `Creneau` VALUES(247, NULL, '2012-03-21 09:00:00', '2012-03-21 11:00:00', 14);
INSERT INTO `Creneau` VALUES(248, NULL, '2012-03-21 11:00:00', '2012-03-21 13:00:00', 14);
INSERT INTO `Creneau` VALUES(249, NULL, '2012-03-21 13:00:00', '2012-03-21 15:00:00', 14);
INSERT INTO `Creneau` VALUES(250, NULL, '2012-03-21 15:00:00', '2012-03-21 17:00:00', 14);
INSERT INTO `Creneau` VALUES(251, NULL, '2012-03-21 17:00:00', '2012-03-21 19:00:00', 14);
INSERT INTO `Creneau` VALUES(252, NULL, '2012-05-28 06:00:00', '2012-05-28 08:00:00', 15);
INSERT INTO `Creneau` VALUES(253, NULL, '2012-05-28 08:00:00', '2012-05-28 10:00:00', 15);
INSERT INTO `Creneau` VALUES(254, NULL, '2012-05-28 10:00:00', '2012-05-28 12:00:00', 15);
INSERT INTO `Creneau` VALUES(255, NULL, '2012-05-28 12:00:00', '2012-05-28 14:00:00', 15);
INSERT INTO `Creneau` VALUES(256, NULL, '2012-05-28 14:00:00', '2012-05-28 16:00:00', 15);
INSERT INTO `Creneau` VALUES(257, NULL, '2012-05-28 16:00:00', '2012-05-28 18:00:00', 15);
INSERT INTO `Creneau` VALUES(258, NULL, '2012-05-28 18:00:00', '2012-05-28 20:00:00', 15);
INSERT INTO `Creneau` VALUES(259, NULL, '2012-05-28 20:00:00', '2012-05-28 22:00:00', 15);
INSERT INTO `Creneau` VALUES(260, NULL, '2012-05-28 22:00:00', '2012-05-29 00:00:00', 15);
INSERT INTO `Creneau` VALUES(261, NULL, '2012-05-29 00:00:00', '2012-05-29 02:00:00', 15);
INSERT INTO `Creneau` VALUES(262, NULL, '2012-05-29 02:00:00', '2012-05-29 04:00:00', 15);
INSERT INTO `Creneau` VALUES(263, NULL, '2012-05-29 04:00:00', '2012-05-29 06:00:00', 15);
INSERT INTO `Creneau` VALUES(264, NULL, '2012-05-29 06:00:00', '2012-05-29 08:00:00', 15);
INSERT INTO `Creneau` VALUES(265, NULL, '2012-05-29 08:00:00', '2012-05-29 10:00:00', 15);
INSERT INTO `Creneau` VALUES(266, NULL, '2012-05-29 10:00:00', '2012-05-29 12:00:00', 15);
INSERT INTO `Creneau` VALUES(267, NULL, '2012-05-29 12:00:00', '2012-05-29 14:00:00', 15);
INSERT INTO `Creneau` VALUES(268, NULL, '2012-05-29 14:00:00', '2012-05-29 16:00:00', 15);
INSERT INTO `Creneau` VALUES(269, NULL, '2012-05-29 16:00:00', '2012-05-29 18:00:00', 15);
INSERT INTO `Creneau` VALUES(270, NULL, '2012-05-29 18:00:00', '2012-05-29 20:00:00', 15);
INSERT INTO `Creneau` VALUES(271, NULL, '2012-05-29 20:00:00', '2012-05-29 22:00:00', 15);
INSERT INTO `Creneau` VALUES(272, NULL, '2012-05-29 22:00:00', '2012-05-30 00:00:00', 15);
INSERT INTO `Creneau` VALUES(273, NULL, '2012-05-30 00:00:00', '2012-05-30 02:00:00', 15);
INSERT INTO `Creneau` VALUES(274, NULL, '2012-05-30 02:00:00', '2012-05-30 04:00:00', 15);
INSERT INTO `Creneau` VALUES(275, NULL, '2012-05-30 04:00:00', '2012-05-30 06:00:00', 15);
INSERT INTO `Creneau` VALUES(276, NULL, '2012-05-30 06:00:00', '2012-05-30 08:00:00', 15);
INSERT INTO `Creneau` VALUES(277, NULL, '2012-03-27 10:15:00', '2012-03-27 11:30:00', 16);
INSERT INTO `Creneau` VALUES(278, NULL, '2012-03-27 11:15:00', '2012-03-27 12:30:00', 16);
INSERT INTO `Creneau` VALUES(279, NULL, '2012-03-27 12:15:00', '2012-03-27 13:30:00', 16);
INSERT INTO `Creneau` VALUES(280, NULL, '2012-03-27 13:15:00', '2012-03-27 14:30:00', 16);
INSERT INTO `Creneau` VALUES(281, NULL, '2012-03-27 14:15:00', '2012-03-27 15:30:00', 16);
INSERT INTO `Creneau` VALUES(282, NULL, '2012-03-27 15:15:00', '2012-03-27 16:30:00', 16);
INSERT INTO `Creneau` VALUES(283, NULL, '2012-03-27 16:15:00', '2012-03-27 17:30:00', 16);
INSERT INTO `Creneau` VALUES(284, NULL, '2012-03-27 17:15:00', '2012-03-27 18:30:00', 16);
INSERT INTO `Creneau` VALUES(285, NULL, '2012-03-27 18:15:00', '2012-03-27 19:30:00', 16);
INSERT INTO `Creneau` VALUES(286, NULL, '2012-03-27 19:15:00', '2012-03-27 20:30:00', 16);
INSERT INTO `Creneau` VALUES(287, NULL, '2012-03-27 20:15:00', '2012-03-27 21:30:00', 16);
INSERT INTO `Creneau` VALUES(288, NULL, '2012-03-27 21:15:00', '2012-03-27 22:30:00', 16);
INSERT INTO `Creneau` VALUES(289, NULL, '2012-03-27 22:15:00', '2012-03-27 23:30:00', 16);
INSERT INTO `Creneau` VALUES(290, NULL, '2012-03-27 23:15:00', '2012-03-28 00:30:00', 16);
INSERT INTO `Creneau` VALUES(291, NULL, '2012-03-28 00:15:00', '2012-03-28 01:30:00', 16);
INSERT INTO `Creneau` VALUES(292, NULL, '2012-03-28 01:15:00', '2012-03-28 02:30:00', 16);
INSERT INTO `Creneau` VALUES(293, NULL, '2012-03-28 02:15:00', '2012-03-28 03:30:00', 16);
INSERT INTO `Creneau` VALUES(294, NULL, '2012-03-28 03:15:00', '2012-03-28 04:30:00', 16);
INSERT INTO `Creneau` VALUES(295, NULL, '2012-03-28 04:15:00', '2012-03-28 05:30:00', 16);
INSERT INTO `Creneau` VALUES(296, NULL, '2012-03-28 05:15:00', '2012-03-28 06:30:00', 16);
INSERT INTO `Creneau` VALUES(297, NULL, '2012-03-28 06:15:00', '2012-03-28 07:30:00', 16);
INSERT INTO `Creneau` VALUES(298, NULL, '2012-03-28 07:15:00', '2012-03-28 08:30:00', 16);
INSERT INTO `Creneau` VALUES(299, NULL, '2012-03-28 08:15:00', '2012-03-28 09:30:00', 16);
INSERT INTO `Creneau` VALUES(300, NULL, '2012-03-28 09:15:00', '2012-03-28 10:30:00', 16);
INSERT INTO `Creneau` VALUES(301, NULL, '2012-03-28 10:15:00', '2012-03-28 11:30:00', 16);
INSERT INTO `Creneau` VALUES(302, NULL, '2012-03-28 11:15:00', '2012-03-28 12:15:00', 16);
INSERT INTO `Creneau` VALUES(303, NULL, '2012-03-27 10:15:00', '2012-03-27 11:30:00', 16);
INSERT INTO `Creneau` VALUES(304, NULL, '2012-03-27 11:15:00', '2012-03-27 12:30:00', 16);
INSERT INTO `Creneau` VALUES(305, NULL, '2012-03-27 12:15:00', '2012-03-27 13:30:00', 16);
INSERT INTO `Creneau` VALUES(306, NULL, '2012-03-27 13:15:00', '2012-03-27 14:30:00', 16);
INSERT INTO `Creneau` VALUES(307, NULL, '2012-03-27 14:15:00', '2012-03-27 15:30:00', 16);
INSERT INTO `Creneau` VALUES(308, NULL, '2012-03-27 15:15:00', '2012-03-27 16:30:00', 16);
INSERT INTO `Creneau` VALUES(309, NULL, '2012-03-27 16:15:00', '2012-03-27 17:30:00', 16);
INSERT INTO `Creneau` VALUES(310, NULL, '2012-03-27 17:15:00', '2012-03-27 18:30:00', 16);
INSERT INTO `Creneau` VALUES(311, NULL, '2012-03-27 18:15:00', '2012-03-27 19:30:00', 16);
INSERT INTO `Creneau` VALUES(312, NULL, '2012-03-27 19:15:00', '2012-03-27 20:30:00', 16);
INSERT INTO `Creneau` VALUES(313, NULL, '2012-03-27 20:15:00', '2012-03-27 21:30:00', 16);
INSERT INTO `Creneau` VALUES(314, NULL, '2012-03-27 21:15:00', '2012-03-27 22:30:00', 16);
INSERT INTO `Creneau` VALUES(315, NULL, '2012-03-27 22:15:00', '2012-03-27 23:30:00', 16);
INSERT INTO `Creneau` VALUES(316, NULL, '2012-03-27 23:15:00', '2012-03-28 00:30:00', 16);
INSERT INTO `Creneau` VALUES(317, NULL, '2012-03-28 00:15:00', '2012-03-28 01:30:00', 16);
INSERT INTO `Creneau` VALUES(318, NULL, '2012-03-28 01:15:00', '2012-03-28 02:30:00', 16);
INSERT INTO `Creneau` VALUES(319, NULL, '2012-03-28 02:15:00', '2012-03-28 03:30:00', 16);
INSERT INTO `Creneau` VALUES(320, NULL, '2012-03-28 03:15:00', '2012-03-28 04:30:00', 16);
INSERT INTO `Creneau` VALUES(321, NULL, '2012-03-28 04:15:00', '2012-03-28 05:30:00', 16);
INSERT INTO `Creneau` VALUES(322, NULL, '2012-03-28 05:15:00', '2012-03-28 06:30:00', 16);
INSERT INTO `Creneau` VALUES(323, NULL, '2012-03-28 06:15:00', '2012-03-28 07:30:00', 16);
INSERT INTO `Creneau` VALUES(324, NULL, '2012-03-28 07:15:00', '2012-03-28 08:30:00', 16);
INSERT INTO `Creneau` VALUES(325, NULL, '2012-03-28 08:15:00', '2012-03-28 09:30:00', 16);
INSERT INTO `Creneau` VALUES(326, NULL, '2012-03-28 09:15:00', '2012-03-28 10:30:00', 16);
INSERT INTO `Creneau` VALUES(327, NULL, '2012-03-28 10:15:00', '2012-03-28 11:30:00', 16);
INSERT INTO `Creneau` VALUES(328, NULL, '2012-03-28 11:15:00', '2012-03-28 12:15:00', 16);
INSERT INTO `Creneau` VALUES(329, NULL, '2012-03-27 10:15:00', '2012-03-27 11:30:00', 16);
INSERT INTO `Creneau` VALUES(330, NULL, '2012-03-27 11:15:00', '2012-03-27 12:30:00', 16);
INSERT INTO `Creneau` VALUES(331, NULL, '2012-03-27 12:15:00', '2012-03-27 13:30:00', 16);
INSERT INTO `Creneau` VALUES(332, NULL, '2012-03-27 13:15:00', '2012-03-27 14:30:00', 16);
INSERT INTO `Creneau` VALUES(333, NULL, '2012-03-27 14:15:00', '2012-03-27 15:30:00', 16);
INSERT INTO `Creneau` VALUES(334, NULL, '2012-03-27 15:15:00', '2012-03-27 16:30:00', 16);
INSERT INTO `Creneau` VALUES(335, NULL, '2012-03-27 16:15:00', '2012-03-27 17:30:00', 16);
INSERT INTO `Creneau` VALUES(336, NULL, '2012-03-27 17:15:00', '2012-03-27 18:30:00', 16);
INSERT INTO `Creneau` VALUES(337, NULL, '2012-03-27 18:15:00', '2012-03-27 19:30:00', 16);
INSERT INTO `Creneau` VALUES(338, NULL, '2012-03-27 19:15:00', '2012-03-27 20:30:00', 16);
INSERT INTO `Creneau` VALUES(339, NULL, '2012-03-27 20:15:00', '2012-03-27 21:30:00', 16);
INSERT INTO `Creneau` VALUES(340, NULL, '2012-03-27 21:15:00', '2012-03-27 22:30:00', 16);
INSERT INTO `Creneau` VALUES(341, NULL, '2012-03-27 22:15:00', '2012-03-27 23:30:00', 16);
INSERT INTO `Creneau` VALUES(342, NULL, '2012-03-27 23:15:00', '2012-03-28 00:30:00', 16);
INSERT INTO `Creneau` VALUES(343, NULL, '2012-03-28 00:15:00', '2012-03-28 01:30:00', 16);
INSERT INTO `Creneau` VALUES(344, NULL, '2012-03-28 01:15:00', '2012-03-28 02:30:00', 16);
INSERT INTO `Creneau` VALUES(345, NULL, '2012-03-28 02:15:00', '2012-03-28 03:30:00', 16);
INSERT INTO `Creneau` VALUES(346, NULL, '2012-03-28 03:15:00', '2012-03-28 04:30:00', 16);
INSERT INTO `Creneau` VALUES(347, NULL, '2012-03-28 04:15:00', '2012-03-28 05:30:00', 16);
INSERT INTO `Creneau` VALUES(348, NULL, '2012-03-28 05:15:00', '2012-03-28 06:30:00', 16);
INSERT INTO `Creneau` VALUES(349, NULL, '2012-03-28 06:15:00', '2012-03-28 07:30:00', 16);
INSERT INTO `Creneau` VALUES(350, NULL, '2012-03-28 07:15:00', '2012-03-28 08:30:00', 16);
INSERT INTO `Creneau` VALUES(351, NULL, '2012-03-28 08:15:00', '2012-03-28 09:30:00', 16);
INSERT INTO `Creneau` VALUES(352, NULL, '2012-03-28 09:15:00', '2012-03-28 10:30:00', 16);
INSERT INTO `Creneau` VALUES(353, NULL, '2012-03-28 10:15:00', '2012-03-28 11:30:00', 16);
INSERT INTO `Creneau` VALUES(354, NULL, '2012-03-28 11:15:00', '2012-03-28 12:15:00', 16);
INSERT INTO `Creneau` VALUES(355, NULL, '2012-03-23 11:00:00', '2012-03-23 12:00:00', 17);
INSERT INTO `Creneau` VALUES(356, NULL, '2012-03-23 12:00:00', '2012-03-23 13:00:00', 17);
INSERT INTO `Creneau` VALUES(357, NULL, '2012-03-23 13:00:00', '2012-03-23 14:00:00', 17);
INSERT INTO `Creneau` VALUES(358, NULL, '2012-03-23 14:00:00', '2012-03-23 15:00:00', 17);
INSERT INTO `Creneau` VALUES(359, NULL, '2012-03-23 15:00:00', '2012-03-23 16:00:00', 17);
INSERT INTO `Creneau` VALUES(360, NULL, '2012-03-23 16:00:00', '2012-03-23 17:00:00', 17);
INSERT INTO `Creneau` VALUES(361, NULL, '2012-03-23 17:00:00', '2012-03-23 18:00:00', 17);
INSERT INTO `Creneau` VALUES(362, NULL, '2012-03-23 18:00:00', '2012-03-23 19:00:00', 17);
INSERT INTO `Creneau` VALUES(363, NULL, '2012-05-26 11:00:00', '2012-05-26 12:00:00', 18);
INSERT INTO `Creneau` VALUES(364, NULL, '2012-05-26 12:00:00', '2012-05-26 13:00:00', 18);

-- --------------------------------------------------------

--
-- Structure de la table `Disponibilite`
--

DROP TABLE IF EXISTS `Disponibilite`;
CREATE TABLE `Disponibilite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orga_id` int(11) DEFAULT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FC485DA97F068A1` (`orga_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Contenu de la table `Disponibilite`
--

INSERT INTO `Disponibilite` VALUES(105, 63, '2012-05-23 10:00:00', '2012-05-23 20:00:00');
INSERT INTO `Disponibilite` VALUES(106, 63, '2012-03-24 11:00:00', '2012-03-24 16:00:00');
INSERT INTO `Disponibilite` VALUES(107, 63, '2012-05-25 10:00:00', '2012-05-25 16:00:00');
INSERT INTO `Disponibilite` VALUES(108, 63, '2012-05-17 10:00:00', '2012-05-18 12:00:00');
INSERT INTO `Disponibilite` VALUES(109, 63, '2012-05-28 10:00:00', '2012-05-28 12:00:00');
INSERT INTO `Disponibilite` VALUES(110, 64, '2012-05-23 10:00:00', '2012-05-26 20:00:00');
INSERT INTO `Disponibilite` VALUES(111, 64, '2012-05-27 00:00:00', '2012-05-29 02:00:00');
INSERT INTO `Disponibilite` VALUES(112, 65, '2012-05-19 00:00:00', '2012-05-25 02:00:00');
INSERT INTO `Disponibilite` VALUES(113, 66, '2012-05-25 00:00:00', '2012-06-10 01:00:00');
INSERT INTO `Disponibilite` VALUES(114, 67, '2012-05-21 00:00:00', '2012-05-26 02:00:00');
INSERT INTO `Disponibilite` VALUES(115, 67, '2012-05-26 05:00:00', '2012-06-01 07:00:00');
INSERT INTO `Disponibilite` VALUES(116, 68, '2012-05-23 00:00:00', '2012-06-04 02:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `DisponibiliteInscription`
--

DROP TABLE IF EXISTS `DisponibiliteInscription`;
CREATE TABLE `DisponibiliteInscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `DisponibiliteInscription`
--

INSERT INTO `DisponibiliteInscription` VALUES(1, '2012-05-20 21:55:05', '2012-06-03 21:55:36');

-- --------------------------------------------------------

--
-- Structure de la table `Equipe`
--

DROP TABLE IF EXISTS `Equipe`;
CREATE TABLE `Equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsable_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23E5BF2353C59D72` (`responsable_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `Equipe`
--

INSERT INTO `Equipe` VALUES(1, 69, 'BDE');

-- --------------------------------------------------------

--
-- Structure de la table `GroupeTache`
--

DROP TABLE IF EXISTS `GroupeTache`;
CREATE TABLE `GroupeTache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsable_id` int(11) DEFAULT NULL,
  `equipe_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `animLiee` int(11) DEFAULT NULL,
  `statut` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_21178F0A53C59D72` (`responsable_id`),
  KEY `IDX_21178F0A6D861B89` (`equipe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `GroupeTache`
--

INSERT INTO `GroupeTache` VALUES(1, 69, 1, 'Logistique', 'Local 24', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Lieu`
--

DROP TABLE IF EXISTS `Lieu`;
CREATE TABLE `Lieu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` longtext,
  `latitude` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Lieu`
--

INSERT INTO `Lieu` VALUES(1, 'Patio MDE', 'Patio de la MDE', 5, 6);
INSERT INTO `Lieu` VALUES(2, 'Grande Scène', 'Scène concert qui tue', 6, 9);

-- --------------------------------------------------------

--
-- Structure de la table `Materiel`
--

DROP TABLE IF EXISTS `Materiel`;
CREATE TABLE `Materiel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `type` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Materiel`
--

INSERT INTO `Materiel` VALUES(1, 'Pelle', 'log\\''', 1);
INSERT INTO `Materiel` VALUES(2, 'Du rêve', 'Anim', 0);

-- --------------------------------------------------------

--
-- Structure de la table `Orga`
--

DROP TABLE IF EXISTS `Orga`;
CREATE TABLE `Orga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `confiance_id` int(11) DEFAULT NULL,
  `importid` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `surnom` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dateDeNaissance` date DEFAULT NULL,
  `departement` varchar(255) DEFAULT NULL,
  `commentaire` longtext,
  `statut` smallint(6) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `equipe_id` int(11) DEFAULT NULL,
  `datePermis` date DEFAULT NULL,
  `lastActivity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A54F87E7450FF010` (`telephone`),
  UNIQUE KEY `UNIQ_A54F87E7E7927C74` (`email`),
  KEY `IDX_A54F87E79C9352E1` (`confiance_id`),
  KEY `IDX_A54F87E76D861B89` (`equipe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Contenu de la table `Orga`
--

INSERT INTO `Orga` VALUES(63, 2, NULL, 'Arnaud', 'Camille', 'Cam', '0678431234', 'Camille.Arnaud@insa-lyon.fr', '1992-03-11', 'PC', NULL, 1, 0, 1, '2012-03-13', NULL);
INSERT INTO `Orga` VALUES(64, 2, NULL, 'Fournell', 'Georges', 'Georges', '0678432134', 'Georges.Fournell@insa-lyon.fr', '1991-03-14', 'PC', NULL, 1, 0, 1, '2011-03-21', NULL);
INSERT INTO `Orga` VALUES(65, 3, NULL, 'Cros', 'Lucas', 'Lucas', '0654231456', 'Lucas.Cros@gmail.com', '1989-03-15', 'GMD', NULL, 1, 0, 1, '2012-02-13', NULL);
INSERT INTO `Orga` VALUES(66, 2, NULL, 'Dubourg', 'Jean Baptiste', 'JB', '0654231234', 'jb@yahoo.fr', '2012-03-09', 'PC', NULL, 1, 0, 1, '2012-03-25', NULL);
INSERT INTO `Orga` VALUES(67, 2, NULL, 'Duvoluy', 'Charlotte', 'Chacha', '0745212345', 'Chacha@hotmail.fr', '1991-03-14', 'IF', NULL, 1, 0, 1, '2002-03-26', NULL);
INSERT INTO `Orga` VALUES(68, 3, NULL, 'Dupuy', 'Mathilde', 'Mathilde', '0645231456', 'Mathilde@gmail.com', '2012-03-09', 'SGM', NULL, 1, 0, 1, '2005-03-22', NULL);
INSERT INTO `Orga` VALUES(69, 3, NULL, 'bourgin', 'sylvain', 'Cil', '0685178329', 'sylvain.bourgin@gmail.com', '1990-07-06', 'IF', 'plouf', 1, 1, 1, '2008-03-14', '2012-03-25 22:48:49');

-- --------------------------------------------------------

--
-- Structure de la table `orga_disponibiliteinscription`
--

DROP TABLE IF EXISTS `orga_disponibiliteinscription`;
CREATE TABLE `orga_disponibiliteinscription` (
  `orga_id` int(11) NOT NULL,
  `disponibiliteinscription_id` int(11) NOT NULL,
  PRIMARY KEY (`orga_id`,`disponibiliteinscription_id`),
  KEY `IDX_2877B9F497F068A1` (`orga_id`),
  KEY `IDX_2877B9F433028124` (`disponibiliteinscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `orga_disponibiliteinscription`
--

INSERT INTO `orga_disponibiliteinscription` VALUES(68, 1);
INSERT INTO `orga_disponibiliteinscription` VALUES(69, 1);

-- --------------------------------------------------------

--
-- Structure de la table `PlageHoraire`
--

DROP TABLE IF EXISTS `PlageHoraire`;
CREATE TABLE `PlageHoraire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tache_id` int(11) DEFAULT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `dureeCreneau` smallint(6) NOT NULL,
  `recoupementCreneau` smallint(6) NOT NULL,
  `respNecessaire` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A556E34D2235D39` (`tache_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `PlageHoraire`
--

INSERT INTO `PlageHoraire` VALUES(7, 9, '2012-05-25 21:00:00', '2012-05-26 05:00:00', 7200, 900, 69);
INSERT INTO `PlageHoraire` VALUES(9, 11, '2012-05-25 16:00:00', '2012-05-25 23:00:00', 3600, 900, 69);
INSERT INTO `PlageHoraire` VALUES(10, 11, '2012-05-26 16:00:00', '2012-05-26 23:00:00', 3600, 900, 69);
INSERT INTO `PlageHoraire` VALUES(11, 11, '2012-05-27 16:00:00', '2012-05-27 23:00:00', 3600, 900, 69);
INSERT INTO `PlageHoraire` VALUES(12, 9, '2012-05-26 20:00:00', '2012-05-27 05:00:00', 3600, 900, 69);
INSERT INTO `PlageHoraire` VALUES(14, 8, '2012-03-21 09:00:00', '2012-03-21 19:00:00', 7200, 0, 69);
INSERT INTO `PlageHoraire` VALUES(15, 10, '2012-05-28 06:00:00', '2012-05-30 08:00:00', 7200, 0, 69);
INSERT INTO `PlageHoraire` VALUES(16, 12, '2012-03-27 10:15:00', '2012-03-28 12:15:00', 3600, 900, 69);
INSERT INTO `PlageHoraire` VALUES(17, 7, '2012-03-23 11:00:00', '2012-03-23 19:00:00', 3600, 0, 69);
INSERT INTO `PlageHoraire` VALUES(18, 7, '2012-05-26 11:00:00', '2012-05-26 13:00:00', 3600, 0, 69);

-- --------------------------------------------------------

--
-- Structure de la table `Tache`
--

DROP TABLE IF EXISTS `Tache`;
CREATE TABLE `Tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsable_id` int(11) DEFAULT NULL,
  `confiance_id` int(11) DEFAULT NULL,
  `groupetache_id` int(11) DEFAULT NULL,
  `importid` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `consignes` longtext,
  `materielSupplementaire` longtext,
  `permisNecessaire` smallint(6) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `statut` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_52460F7153C59D72` (`responsable_id`),
  KEY `IDX_52460F719C9352E1` (`confiance_id`),
  KEY `IDX_52460F71600C5B0A` (`groupetache_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `Tache`
--

INSERT INTO `Tache` VALUES(7, 69, 3, NULL, NULL, 'Deployer matos bar', '-Pas de consignes', 'Des mains', 2, 'Lyon', 0);
INSERT INTO `Tache` VALUES(8, 69, 2, NULL, NULL, 'Apporter matos scene', '-arrivée prévue à 18h', 'Un camion', 2, 'Lyon', 0);
INSERT INTO `Tache` VALUES(9, 69, 2, NULL, NULL, 'Servir des bières', 'Bien pleines svp !', NULL, 0, 'Pelouse des humas', 0);
INSERT INTO `Tache` VALUES(10, 69, 2, NULL, NULL, 'Rangement des barrières', 'Rdv 10h le 28 mai', NULL, 2, 'Pelouse des humas', 0);
INSERT INTO `Tache` VALUES(11, 69, 2, NULL, NULL, 'Sandwichs', 'Servir des sandwich', NULL, 0, 'Pelouse des humas', 0);
INSERT INTO `Tache` VALUES(12, 69, 2, NULL, NULL, 'Nettoyer pelouse humas', 'Nettoyer pelouse humas', NULL, 0, 'Pelouse humas', 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `BesoinMateriel`
--
ALTER TABLE `BesoinMateriel`
  ADD CONSTRAINT `FK_CF9D6A0E16880AAF` FOREIGN KEY (`materiel_id`) REFERENCES `Materiel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CF9D6A0ED2235D39` FOREIGN KEY (`tache_id`) REFERENCES `Tache` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `BesoinOrga`
--
ALTER TABLE `BesoinOrga`
  ADD CONSTRAINT `FK_BDE21E3A6D861B89` FOREIGN KEY (`equipe_id`) REFERENCES `Equipe` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_BDE21E3A2D9955DA` FOREIGN KEY (`plageHoraire_id`) REFERENCES `PlageHoraire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Commentaire`
--
ALTER TABLE `Commentaire`
  ADD CONSTRAINT `FK_E16CE76B97F068A1` FOREIGN KEY (`orga_id`) REFERENCES `Orga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_E16CE76BD2235D39` FOREIGN KEY (`tache_id`) REFERENCES `Tache` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Creneau`
--
ALTER TABLE `Creneau`
  ADD CONSTRAINT `FK_36DBB2C32D9955DA` FOREIGN KEY (`plageHoraire_id`) REFERENCES `PlageHoraire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_36DBB2C32B9D6493` FOREIGN KEY (`disponibilite_id`) REFERENCES `Disponibilite` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `Disponibilite`
--
ALTER TABLE `Disponibilite`
  ADD CONSTRAINT `FK_9FC485DA97F068A1` FOREIGN KEY (`orga_id`) REFERENCES `Orga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Equipe`
--
ALTER TABLE `Equipe`
  ADD CONSTRAINT `FK_23E5BF2353C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `Orga` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `GroupeTache`
--
ALTER TABLE `GroupeTache`
  ADD CONSTRAINT `FK_21178F0A6D861B89` FOREIGN KEY (`equipe_id`) REFERENCES `Equipe` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_21178F0A53C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `Orga` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `Orga`
--
ALTER TABLE `Orga`
  ADD CONSTRAINT `FK_A54F87E76D861B89` FOREIGN KEY (`equipe_id`) REFERENCES `Equipe` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_A54F87E79C9352E1` FOREIGN KEY (`confiance_id`) REFERENCES `Confiance` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `orga_disponibiliteinscription`
--
ALTER TABLE `orga_disponibiliteinscription`
  ADD CONSTRAINT `FK_2877B9F433028124` FOREIGN KEY (`disponibiliteinscription_id`) REFERENCES `DisponibiliteInscription` (`id`),
  ADD CONSTRAINT `FK_2877B9F497F068A1` FOREIGN KEY (`orga_id`) REFERENCES `Orga` (`id`);

--
-- Contraintes pour la table `PlageHoraire`
--
ALTER TABLE `PlageHoraire`
  ADD CONSTRAINT `FK_6A556E34D2235D39` FOREIGN KEY (`tache_id`) REFERENCES `Tache` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Tache`
--
ALTER TABLE `Tache`
  ADD CONSTRAINT `FK_52460F71600C5B0A` FOREIGN KEY (`groupetache_id`) REFERENCES `GroupeTache` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_52460F7153C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `Orga` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_52460F719C9352E1` FOREIGN KEY (`confiance_id`) REFERENCES `Confiance` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
