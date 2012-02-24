-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Ven 24 Février 2012 à 10:34
-- Version du serveur: 5.5.20
-- Version de PHP: 5.3.10-1ubuntu1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `phpm`
--

--
-- Contenu de la table `Categorie`
--

INSERT IGNORE INTO `Categorie` (`id`, `nom`, `couleur`) VALUES
(1, 'Bar', 'Bleu'),
(2, 'Bouffe', 'Bleu'),
(3, 'Sécurité', 'Bleu');

--
-- Contenu de la table `Confiance`
--

INSERT IGNORE INTO `Confiance` (`id`, `nom`, `valeur`, `couleur`) VALUES
(2, 'Soft', 1, 'Bleu'),
(3, 'Hard', 3, 'Bleu');

--
-- Contenu de la table `Config`
--

INSERT IGNORE INTO `Config` (`id`, `field`, `label`, `value`) VALUES
(1, 'manifestation.plages', 'Plages de la manifestation', '{"1":{"nom":"Pru00e9manif","debut":"2012-05-16 00:00","fin":"2012-05-23 00:00"},"2":{"nom":"Manif","debut":"2012-05-23 00:00","fin":"2012-05-27 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}'),
(2, 'manifestation.organisation.nom', 'Nom de l''organisation', '24 Heures de l''INSA'),
(3, 'phpm.config.initiale', 'PHPM configuré', '1'),
(4, 'manifestation.nom', '', '24 Heures de l''INSA');

--
-- Contenu de la table `Creneau`
--

INSERT IGNORE INTO `Creneau` (`id`, `disponibilite_id`, `debut`, `fin`, `plageHoraire_id`) VALUES
(2, NULL, '2011-01-01 18:00:00', '2011-01-01 19:15:00', 1),
(3, NULL, '2011-01-01 19:00:00', '2011-01-01 20:15:00', 1),
(4, NULL, '2011-01-01 20:00:00', '2011-01-01 21:15:00', 1),
(5, NULL, '2011-01-01 21:00:00', '2011-01-01 22:00:00', 1),
(6, NULL, '2011-01-01 18:00:00', '2011-01-01 19:15:00', 1),
(7, NULL, '2011-01-01 19:00:00', '2011-01-01 20:15:00', 1),
(8, NULL, '2011-01-01 20:00:00', '2011-01-01 21:15:00', 1),
(9, NULL, '2011-01-01 21:00:00', '2011-01-01 22:00:00', 1),
(10, NULL, '2011-01-01 18:00:00', '2011-01-01 19:15:00', 1),
(11, NULL, '2011-01-01 19:00:00', '2011-01-01 20:15:00', 1),
(12, NULL, '2011-01-01 20:00:00', '2011-01-01 21:15:00', 1),
(13, NULL, '2011-01-01 21:00:00', '2011-01-01 22:00:00', 1),
(14, NULL, '2011-01-01 18:00:00', '2011-01-01 19:15:00', 1),
(15, NULL, '2011-01-01 19:00:00', '2011-01-01 20:15:00', 1),
(16, NULL, '2011-01-01 20:00:00', '2011-01-01 21:15:00', 1),
(17, NULL, '2011-01-01 21:00:00', '2011-01-01 22:00:00', 1),
(18, NULL, '2011-01-01 18:00:00', '2011-01-01 19:15:00', 1),
(19, NULL, '2011-01-01 19:00:00', '2011-01-01 20:15:00', 1),
(20, NULL, '2011-01-01 20:00:00', '2011-01-01 21:15:00', 1),
(21, NULL, '2011-01-01 21:00:00', '2011-01-01 22:00:00', 1),
(22, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(23, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(24, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(25, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(26, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(27, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(28, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(29, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(30, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(31, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(32, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(33, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(34, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(35, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(36, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(37, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(38, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(39, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(40, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(41, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(42, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(43, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(44, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(45, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(46, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(47, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(48, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(49, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(50, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(51, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(52, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(53, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(54, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(55, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(56, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(57, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(58, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(59, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(60, NULL, '2011-01-02 18:00:00', '2011-01-02 20:15:00', 4),
(61, NULL, '2011-01-02 20:00:00', '2011-01-02 22:00:00', 4),
(62, NULL, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 3),
(63, NULL, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 3),
(64, NULL, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 3),
(65, NULL, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 3),
(66, NULL, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 3);

--
-- Contenu de la table `Disponibilite`
--

INSERT IGNORE INTO `Disponibilite` (`id`, `orga_id`, `debut`, `fin`) VALUES
(1, 1, '2011-01-01 00:00:00', '2011-01-02 00:00:00');

--
-- Contenu de la table `Orga`
--

INSERT IGNORE INTO `Orga` (`id`, `confiance_id`, `importid`, `nom`, `prenom`, `surnom`, `telephone`, `email`, `dateDeNaissance`, `departement`, `commentaire`, `permis`, `statut`, `is_admin`) VALUES
(1, 3, NULL, 'Tournier', 'Mathieu', 'Samt', '0643548131', 'mathieutournier@gmail.com', '1991-03-30', '4', NULL, 1, 0, 1),
(2, 3, NULL, 'Cashman', 'Nathan', 'Nath''', '0678543131', 'Nathan.Cashman@insa-lyon.fr', '1992-07-17', '0', NULL, 1, 1, 0),
(3, 3, NULL, 'Dupond', 'Manon', 'Manon', '0656784312', 'Manon.Dupond@insa-lyon.fr', '1993-06-18', '0', NULL, 1, 1, 0),
(4, 2, NULL, 'Durand', 'Dupond', 'Dudu', '0671347654', 'Dupond.Durand@insa-lyon.fr', '1990-01-01', '2', NULL, 1, 1, 0),
(5, 2, NULL, 'Malacom', 'Lola', 'Lol', '0645341287', 'Lola.Malacom@insa-lyon.fr', '1992-01-01', '12', NULL, 1, 0, 0),
(6, 2, NULL, 'Laval', 'Natacha', 'Nat', '0634567854', 'Natacha.Laval@gmail.com', '1992-01-01', '12', NULL, 1, 1, 0),
(7, 2, NULL, 'Lelouche', 'Marc', 'Marc', '0675431234', 'Marc.L@google.com', '1994-01-01', '13', NULL, 1, 1, 0),
(8, 2, NULL, 'V', 'Jean Marc', 'Jj', '0678542341', 'jj@gmail.com', '1991-01-01', '11', NULL, 1, 1, 0),
(9, 2, NULL, 'Marché', 'Camille', 'Camille', '0656789054', 'Camille.Marche@yahoo.fr', '1985-01-01', '3', NULL, 1, 1, 0),
(10, 2, NULL, 'Dujean', 'Janot', 'Janot', '0643521345', 'Janot.Dujean@gmail.com', '1995-01-01', '13', NULL, 1, 1, 0);

--
-- Contenu de la table `PlageHoraire`
--

INSERT IGNORE INTO `PlageHoraire` (`id`, `tache_id`, `debut`, `fin`, `dureeCreneau`, `recoupementCreneau`, `nbOrgasNecessaires`) VALUES
(1, 3, '2011-01-01 18:00:00', '2011-01-01 22:00:00', 3600, 900, 5),
(2, 2, '2011-01-01 12:00:00', '2011-01-01 14:00:00', 3600, 0, 5),
(3, 5, '2011-01-02 07:00:00', '2011-01-02 09:00:00', 7200, 0, 5),
(4, 4, '2011-01-02 18:00:00', '2011-01-02 22:00:00', 7200, 900, 20),
(5, 4, '2011-01-01 18:00:00', '2011-01-01 23:00:00', 7200, 900, 20),
(6, 6, '2011-01-02 22:00:00', '2011-01-03 00:00:00', 3600, 900, 5);

--
-- Contenu de la table `Tache`
--

INSERT IGNORE INTO `Tache` (`id`, `responsable_id`, `categorie_id`, `confiance_id`, `importid`, `nom`, `consignes`, `materielNecessaire`, `permisNecessaire`, `ageNecessaire`, `lieu`) VALUES
(1, 1, 1, 3, NULL, 'Charchage barrières', 'RDV au QG orga, apporter le papier de prêt', 'Des mains', 0, 18, 'Grand Lyon'),
(2, NULL, 3, 2, NULL, 'Extincteurs', 'Rapporter les extincteurs à la DirPa...', NULL, 0, 15, 'BDE'),
(3, NULL, 2, 2, NULL, 'Bouffe artiste', 'Bouffe artiste en semaine', '...', 0, 18, 'INSA'),
(4, 1, 1, 2, NULL, 'Servir des bières', '...', NULL, 0, 18, 'Humas'),
(5, NULL, 2, 3, NULL, 'Installation bar bouffe', 'Voir QG', 'Tables, matos', 1, 18, 'INSA'),
(6, 1, 1, 3, NULL, 'Matos concert Daft punk', 'Gerer le matos', NULL, 1, 20, 'Pelouse des humas');

--
-- Contenu de la table `User`
--

INSERT IGNORE INTO `User` (`id`, `username`, `pass`, `email`) VALUES
(1, 'orga', 'orga', 'orga@24heures.org');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
