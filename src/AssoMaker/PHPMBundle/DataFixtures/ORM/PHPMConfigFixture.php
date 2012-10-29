<?php
namespace AssoMaker\ComptesPersoBundle\DataFixtures\ORM;
use AssoMaker\PHPMBundle\Entity\Config;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PHPMConfigFixture implements FixtureInterface {

	public function load(ObjectManager $manager) {
		echo "Starting to load PHPLanningmaker PHPM FIXTURES\n";
		
		$manager
		->persist(
		        new Config('phpm_disponibilites_actif',
		                'Les orgas peuvent renseigner leurs disponibilités',
		                '0',
		                NULL));
		
		$manager
		->persist(
		        new Config('manifestation_plages',
		                'Plages de la manifestation',
		                '{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"}}',
		                'Tableau JSON associatif de plages. Exemple : "{"1":{"nom":"Prémanif","debut":"2012-05-16 00:00","fin":"2012-05-24 00:00"}}". Les plages doivent commencer et finir à 0h00.'));
		$manager
		->persist(
		        new Config('manifestation_organisation_nom',
		                'Nom de l\'organisation',
		                '24 Heures de l\'INSA', NULL));
		
		$manager
		->persist(
		        new Config(
		                'manifestation_orga_responsableconfiancemin',
		                'Confiance minimale pour être responsable d\'une tâche',
		                '500', NULL));
		$manager
		->persist(
		        new Config('manifestation_permis_libelles',
		                'Libellés des permis',
		                '{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }',
		                'Objet JSON. Exemple : "{"0": "Pas de permis","1": "Permis - de 2 ans","2": "Permis de + de 2 ans" }"'));
		$manager
		->persist(
		        new Config('animations_db_path',
		                'Chemin vers la BDD des anims',
		                null,
		                'Chemin absolu vers la base SQLite des animations, au format Animaker. Laisser nul sinon.'));
		$manager
		->persist(
		        new Config('phpm_orgasoft_inscription_returnURL',
		                'URL de retour inscription après inscription orga',
		                'http://www.24heures.org/orga', NULL));

		$manager
		->persist(
		        new Config('phpm_contenu_mail_confirmation_soft',
		                'Contenu du mail de confirmation soft',
		                '<p>Ton inscription en tant qu\'Orga Soft  pour la 38<sup>e</sup> édition des 24 Heures de l\'INSA a bien été prise en compte.</p>\r\n<p>Pour toute question, envoies un email à <a href="mailto:orga@24heures.org">orga@24heures.org</a> ou répond à cet email.</p>\r\n<p>Merci beaucoup de ton aide et à très bientôt sur la manif\' !</p>\r\n<p><em>Maud et Adam.</em></p>',
		                NULL));
		$manager
		->persist(
		        new Config('manifestation_charisme_minimum',
		                'Charisme minimum pour s\'inscrire', '30',
		                NULL));
		$manager
		->persist(
		        new Config(
		                'phpm_inscription_disponibilites_consignes',
		                'Consignes concernant les disponibilités',
		                '<p>\r\n<b>Tu dois sélectionner au moins 6h d\'Orga, soit 3 créneaux de 2h.</b>\r\nC\'est un minimum, plus tu mets de disponibilités, plus tu gagnes de charisme !</p>',
		                NULL));
		$manager
		->persist(
		        new Config(
		                'manifestation_orga_plagehoraireconfiancemin',
		                'Confiance minimale pour être désigné dans une plage horaire',
		                '500', NULL));
		$manager
		->persist(
		        new Config('phpm_planning_message',
		                'Message affiché sur les plannings',
		                '<em>Attention : planning non définitif.\r\n</em>',
		                NULL));
		$manager
		->persist(
		        new Config('phpm_planning_fin',
		                'Heure de début des plannings',
		                '13-05-05 00:00:00', NULL));
		

		
		$manager->flush();

	}
}
