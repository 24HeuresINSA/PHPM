-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 26 Février 2012 à 20:52
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `phpm`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `Categorie`
--

INSERT INTO `Categorie` VALUES(1, 'Bar', 'blue');
INSERT INTO `Categorie` VALUES(2, 'Sécurité', 'red');
INSERT INTO `Categorie` VALUES(3, 'Bouffe', 'yellow');

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

INSERT INTO `Confiance` VALUES(1, 'Soft', 100, 'green');
INSERT INTO `Confiance` VALUES(2, 'Confiance', 500, 'blue');
INSERT INTO `Confiance` VALUES(3, 'Hard', 1000, 'orange');

-- --------------------------------------------------------

--
-- Structure de la table `Config`
--

DROP TABLE IF EXISTS `Config`;
CREATE TABLE `Config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D3262A4A5BF54558` (`field`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `Config`
--

INSERT INTO `Config` VALUES(1, 'manifestation_plages', '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"},"2":{"nom":"Manif","debut":"2012-05-24 00:00","fin":"2012-05-27 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}', 'Plages de la manifestation');
INSERT INTO `Config` VALUES(2, 'manifestation_organisation_nom', '24 Heures de l''INSA', 'Nom de l''organisation');
INSERT INTO `Config` VALUES(3, 'phpm_config_initiale', '1', 'PHPlanningMaker configuré');
INSERT INTO `Config` VALUES(4, 'server_baseurl', 'localhost', 'URL du serveur');
INSERT INTO `Config` VALUES(5, 'manifestation_nom', '24 Heures de l''INSA 38e', 'Nom de la manifestation');
INSERT INTO `Config` VALUES(6, 'manifestation_orga_responsableconfiancemin', '500', 'Confiance minimale pour être responsable d''une tâche');
INSERT INTO `Config` VALUES(7, 'manifestation_permis_libelles', '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }', 'Libellés des permis');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `Creneau`
--

INSERT INTO `Creneau` VALUES(1, NULL, '2012-05-25 20:00:00', '2012-05-25 20:30:00', 1);
INSERT INTO `Creneau` VALUES(2, 5, '2012-05-25 22:00:00', '2012-05-26 00:00:00', 4);
INSERT INTO `Creneau` VALUES(3, NULL, '2012-05-26 00:00:00', '2012-05-26 02:00:00', 4);
INSERT INTO `Creneau` VALUES(4, 5, '2012-05-26 20:00:00', '2012-05-26 22:00:00', 4);
INSERT INTO `Creneau` VALUES(5, NULL, '2012-05-25 22:00:00', '2012-05-26 01:00:00', 4);
INSERT INTO `Creneau` VALUES(6, 7, '2012-05-26 02:00:00', '2012-05-26 04:00:00', 4);
INSERT INTO `Creneau` VALUES(7, NULL, '2012-05-26 12:00:00', '2012-05-26 13:00:00', 5);
INSERT INTO `Creneau` VALUES(8, NULL, '2012-05-26 14:00:00', '2012-05-26 16:00:00', 5);
INSERT INTO `Creneau` VALUES(9, 0, '2012-05-26 12:00:00', '2012-05-26 13:00:00', 5);
INSERT INTO `Creneau` VALUES(10, 0, '2012-05-26 13:00:00', '2012-05-26 14:00:00', 5);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `Disponibilite`
--

INSERT INTO `Disponibilite` VALUES(0, 0, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `Disponibilite` VALUES(5, 3, '2012-05-25 22:00:00', '2012-05-26 02:00:00');
INSERT INTO `Disponibilite` VALUES(6, 3, '2012-05-26 20:00:00', '2012-05-27 04:00:00');
INSERT INTO `Disponibilite` VALUES(7, 4, '2012-05-25 22:00:00', '2012-05-26 00:00:00');
INSERT INTO `Disponibilite` VALUES(26, 4, '2012-05-26 22:00:00', '2012-05-27 00:00:00');
INSERT INTO `Disponibilite` VALUES(27, 3, '2012-05-26 12:00:00', '2012-05-26 16:00:00');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `DisponibiliteInscription`
--


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
  `permis` smallint(6) NOT NULL,
  `statut` smallint(6) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A54F87E7450FF010` (`telephone`),
  UNIQUE KEY `UNIQ_A54F87E7E7927C74` (`email`),
  KEY `IDX_A54F87E79C9352E1` (`confiance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Orga`
--

INSERT INTO `Orga` VALUES(0, 3, NULL, 'NOBODY', 'NOBODY', 'NOBODY', '0600000000', 'nobody@nobody.com', '1900-01-01', '0', '0', 0, 0, NULL);
INSERT INTO `Orga` VALUES(1, NULL, NULL, 'Drigon', 'Romaric', 'Shériff', '0606626574', 'blackbirdster@gmail.com', '1990-12-04', 'IF', NULL, 0, 0, 1);
INSERT INTO `Orga` VALUES(3, 1, 21, 'Péon', 'Lambda', NULL, '0723456789', 'peon.lambda@gmail.com', '1970-09-19', '2PC', 'Il est basique.', 2, 1, NULL);
INSERT INTO `Orga` VALUES(4, 2, 21, 'Rôti', 'Michel', '', '0643215945', 'michel@k-fet.fr', '1989-09-19', '9Labo', 'Apéro ! ', 0, 1, NULL);

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
  `nbOrgasNecessaires` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A556E34D2235D39` (`tache_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `PlageHoraire`
--

INSERT INTO `PlageHoraire` VALUES(1, 2, '2012-05-25 20:00:00', '2012-05-25 20:30:00', 0, 0, 1);
INSERT INTO `PlageHoraire` VALUES(4, 1, '2012-05-25 20:00:00', '2012-05-26 04:00:00', 0, 0, 5);
INSERT INTO `PlageHoraire` VALUES(5, 3, '2012-05-26 12:00:00', '2012-05-26 14:00:00', 3600, 0, 5);

-- --------------------------------------------------------

--
-- Structure de la table `Tache`
--

DROP TABLE IF EXISTS `Tache`;
CREATE TABLE `Tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsable_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `confiance_id` int(11) DEFAULT NULL,
  `importid` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `consignes` longtext NOT NULL,
  `materielNecessaire` longtext,
  `permisNecessaire` smallint(6) NOT NULL,
  `ageNecessaire` smallint(6) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_52460F7153C59D72` (`responsable_id`),
  KEY `IDX_52460F71BCF5E72D` (`categorie_id`),
  KEY `IDX_52460F719C9352E1` (`confiance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `Tache`
--

INSERT INTO `Tache` VALUES(1, NULL, 1, 1, 0, 'Tenir le bar', 'TB', 'rien', 0, 0, 'Bar AIP');
INSERT INTO `Tache` VALUES(2, NULL, 2, 3, 0, 'Surveiller le PS1', 'S''assurer que les orgas sont bien en position.', 'Kit Sécu', 0, 18, 'PS1 - Laurent Bonnevay');
INSERT INTO `Tache` VALUES(3, NULL, 3, 1, NULL, 'Vendre des frites', 'Avoir du charisme.', NULL, 0, 0, 'Bar Bouffe');

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `User`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Creneau`
--
ALTER TABLE `Creneau`
  ADD CONSTRAINT `FK_36DBB2C32B9D6493` FOREIGN KEY (`disponibilite_id`) REFERENCES `Disponibilite` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_36DBB2C32D9955DA` FOREIGN KEY (`plageHoraire_id`) REFERENCES `PlageHoraire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Disponibilite`
--
ALTER TABLE `Disponibilite`
  ADD CONSTRAINT `FK_9FC485DA97F068A1` FOREIGN KEY (`orga_id`) REFERENCES `Orga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Orga`
--
ALTER TABLE `Orga`
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
  ADD CONSTRAINT `FK_52460F7153C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `Orga` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_52460F719C9352E1` FOREIGN KEY (`confiance_id`) REFERENCES `Confiance` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_52460F71BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `Categorie` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;
