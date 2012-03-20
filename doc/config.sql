INSERT INTO `Config` (`id`, `field`, `label`, `value`) VALUES
(1, 'manifestation_plages', 'Plages de la manifestation', '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"},"2":{"nom":"Manif","debut":"2012-05-24 00:00","fin":"2012-05-27 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}'),
(2, 'manifestation_organisation_nom', 'Nom de l''organisation', '24 Heures de l''INSA'),
(3, 'phpm_config_initiale', 'PHPlanningMaker configuré', '1'),
(4, 'server_baseurl', 'URL du serveur', 'localhost'),
(5, 'manifestation_nom', 'Nom de la manifestation', '24 Heures de l''INSA 38e'),
(6, 'manifestation_orga_responsableconfiancemin', 'Confiance minimale pour être responsable d''une tâche', '500'),
(7, 'manifestation_permis_libelles', 'Libellés des permis', '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }'),
(8, 'animations.db.path', 'Chemin vers la BDD des anims', '/var/www/animaker/Backend/Data/DB/DB.sqlite');

INSERT INTO `Confiance` (`id`, `nom`, `valeur`, `couleur`) VALUES
(1, 'Soft', 100, 'blue'),
(2, 'Confiance', 500, 'vert'),
(3, 'Hard', 1000, 'orange');
