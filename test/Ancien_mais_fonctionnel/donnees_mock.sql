-- phpMyAdmin SQL Dump
-- version 3.4.9deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 04, 2012 at 02:12 AM
-- Server version: 5.1.58
-- PHP Version: 5.3.8-1+b1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpm`
--

-- On detruit les données précdentes
DELETE from Orga where is_admin<>1;
DELETE from Disponibilite;
DELETE from Categorie;
DELETE from Confiance;
DELETE from Config;
DELETE from Creneau;
DELETE from PlageHoraire;
DELETE from Tache;
DELETE from User;

--
-- Dumping data for table `Categorie`
--

INSERT INTO `Categorie` (`id`, `nom`, `couleur`) VALUES
(1, 'Bar', 'blue'),
(2, 'Sécurité', 'red'),
(3, 'Bouffe', 'yellow');

--
-- Dumping data for table `Confiance`
--

INSERT INTO `Confiance` (`id`, `nom`, `valeur`, `couleur`) VALUES
(1, 'Soft', 100, 'green'),
(2, 'Confiance', 500, 'blue'),
(3, 'Hard', 1000, 'orange');

--
-- Dumping data for table `Config`
--

INSERT INTO `Config` (`id`, `field`, `label`, `value`) VALUES
(1, 'manifestation.plages', 'Plages de la manifestation', '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"},"2":{"nom":"Manif","debut":"2012-05-24 00:00","fin":"2012-05-27 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}'),
(2, 'manifestation.organisation.nom', 'Nom de l''organisation', '24 Heures de l''INSA'),
(3, 'phpm.config.initiale', 'PHPlanningMaker configuré', '1'),
(4, 'server.baseurl', 'URL du serveur', 'localhost'),
(5, 'manifestation.nom', 'Nom de la manifestation', '24 Heures de l''INSA 38e'),
(6, 'manifestation.orga.responsableconfiancemin', 'Confiance minimale pour être responsable d''une tâche', '500'),
(7, 'manifestation.permis.libelles', 'Libellés des permis', '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }');



--
-- Dumping data for table `Orga`
--

INSERT INTO `Orga` (`id`, `confiance_id`, `importid`, `nom`, `prenom`, `surnom`, `telephone`, `email`, `dateDeNaissance`, `departement`, `commentaire`, `permis`, `statut`) VALUES
(0, 3, NULL, 'NOBODY', 'NOBODY', 'NOBODY', '0600000000', 'nobody@nobody.com', '1900-01-01', '0', '0', 0, 0),
(1, 3, NULL, 'Billon', 'Laurent', 'Tony Bluff', '0632575824', 'laurent.billon@24heures.org', '1991-07-23', '3IF', 'Il est bon en bluff.', 0, 1),
(3, 1, 21, 'Péon', 'Lambda', NULL, '0723456789', 'peon.lambda@gmail.com', '1970-09-19', '2PC', 'Il est basique.', 2, 1),
(4, 2, 21, 'Rôti', 'Michel', '', '0643215945', 'michel@k-fet.fr', '1989-09-19', '9Labo', 'Apéro ! ', 0, 1);



--
-- Dumping data for table `Disponibilite`
--

INSERT INTO `Disponibilite` (`id`, `orga_id`, `debut`, `fin`) VALUES
(0, 0, '9999-12-31 23:59:59', '9999-12-31 23:59:59'),
(1, 1, '2012-05-25 00:00:00', '2012-06-28 00:00:00'),
(5, 3, '2012-05-25 22:00:00', '2012-05-26 02:00:00'),
(6, 3, '2012-05-26 20:00:00', '2012-05-27 04:00:00'),
(7, 4, '2012-05-25 22:00:00', '2012-05-26 00:00:00'),
(26, 4, '2012-05-26 22:00:00', '2012-05-27 00:00:00'),
(27, 3, '2012-05-26 12:00:00', '2012-05-26 16:00:00');

--
-- Dumping data for table `Tache`
--

INSERT INTO `Tache` (`id`, `responsable_id`, `categorie_id`, `confiance_id`, `importid`, `nom`, `consignes`, `materielNecessaire`, `permisNecessaire`, `ageNecessaire`, `lieu`) VALUES
(1, 1, 1, 1, 0, 'Tenir le bar', 'TB', 'rien', 0, 0, 'Bar AIP'),
(2, 1, 2, 3, 0, 'Surveiller le PS1', 'S''assurer que les orgas sont bien en position.', 'Kit Sécu', 0, 18, 'PS1 - Laurent Bonnevay'),
(3, 1, 3, 1, NULL, 'Vendre des frites', 'Avoir du charisme.', NULL, 0, 0, 'Bar Bouffe');

--
-- Dumping data for table `PlageHoraire`
--

INSERT INTO `PlageHoraire` (`id`, `tache_id`, `debut`, `fin`, `dureeCreneau`, `recoupementCreneau`, `nbOrgasNecessaires`) VALUES
(1, 2, '2012-05-25 20:00:00', '2012-05-25 20:30:00', 0, 0, 1),
(4, 1, '2012-05-25 20:00:00', '2012-05-26 04:00:00', 0, 0, 5),
(5, 3, '2012-05-26 12:00:00', '2012-05-26 14:00:00', 3600, 0, 5);



--
-- Dumping data for table `Creneau`
--

INSERT INTO `Creneau` (`id`, `disponibilite_id`, `debut`, `fin`, `plageHoraire_id`) VALUES
(1, 1, '2012-05-25 20:00:00', '2012-05-25 20:30:00', 1),
(2, 5, '2012-05-25 22:00:00', '2012-05-26 00:00:00', 4),
(3, 5, '2012-05-26 00:00:00', '2012-05-26 02:00:00', 4),
(4, 5, '2012-05-26 20:00:00', '2012-05-26 22:00:00', 4),
(5, 7, '2012-05-25 22:00:00', '2012-05-26 00:00:00', 4),
(6, 7, '2012-05-26 02:00:00', '2012-05-26 04:00:00', 4),
(7, 27, '2012-05-26 12:00:00', '2012-05-26 13:00:00', 5),
(8, 0, '2012-05-26 13:00:00', '2012-05-26 14:00:00', 5),
(9, 0, '2012-05-26 12:00:00', '2012-05-26 13:00:00', 5),
(10, 0, '2012-05-26 13:00:00', '2012-05-26 14:00:00', 5);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
