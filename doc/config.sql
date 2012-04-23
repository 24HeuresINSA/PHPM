INSERT INTO `Config` (`id`, `field`, `label`, `value`, `hint`) VALUES
(1, 'manifestation_plages', 'Plages de la manifestation', '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"},"2":{"nom":"Manif","debut":"2012-05-24 00:00","fin":"2012-05-28 00:00"},"3":{"nom":"Postmanif","debut":"2012-05-28 00:00","fin":"2012-06-01 00:00"}}', 'Tableau JSON associatif de plages. Exemple : "{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"}}". Les plages doivent commencer et finir à 0h00.'),
(2, 'manifestation_organisation_nom', 'Nom de l''organisation', '24 Heures de l''INSA', NULL),
(3, 'phpm_config_initiale', 'PHPlanningMaker configuré', '1', '1 si la configuration initiale à déjà été effectuée, 0 sinon.'),
(4, 'server_baseurl', 'URL du serveur', 'localhost', NULL),
(5, 'manifestation_nom', 'Nom de la manifestation', '24 Heures de l''INSA 38e', NULL),
(6, 'manifestation_orga_responsableconfiancemin', 'Confiance minimale pour être responsable d''une tâche', '500', NULL),
(7, 'manifestation_permis_libelles', 'Libellés des permis', '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }', 'Objet JSON. Exemple : "{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }"'),
(8, 'animations_db_path', 'Chemin vers la BDD des anims', '/var/www/animaker/Backend/Data/DB/DB.sqlite', 'Chemin absolu vers la base SQLite des animations, au format Animaker. Laisser nul sinon.'),
(9, 'manifestation_edition', 'Numéro de l''édition', '38', NULL),
(10, 'phpm_contenu_page_principale', 'Contenu de la page principale', '<h1>PHPlanningMaker<h2><p>Gestion des ressources humaines.</p>', '')
(11, 'phpm_orgasoft_inscription_returnURL', 'URL de retour  inscription orga', 'http://www.24heures.org/orga', NULL),
(12, 'manifestation_admin_email', 'Email de l''administrateur', 'orga@24heures.org', NULL)
;

INSERT INTO `Confiance` (`id`, `nom`, `valeur`, `couleur`) VALUES
(1, 'Soft', 100, 'blue'),
(2, 'Confiance', 500, 'vert'),
(3, 'Hard', 1000, 'orange');
