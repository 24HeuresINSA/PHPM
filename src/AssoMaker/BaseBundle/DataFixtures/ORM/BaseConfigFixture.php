<?php

namespace AssoMaker\BaseBundle\DataFixtures\ORM;

use AssoMaker\PHPMBundle\Entity\Config;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BaseConfigFixture implements FixtureInterface {

    public function load(ObjectManager $manager) {
        echo "Starting to load PHPLanningmaker Config FIXTURES\n";

        $manager
                ->persist(
                        new Config('phpm_actif', 'Module planningmaker activé', '1', NULL));
        $manager
                ->persist(
                        new Config('comptes_perso_actif', 'Module Comptes Perso activé', '1', NULL));
        $manager
                ->persist(
                        new Config('sponso_actif', 'Module Sponso activé', '1', NULL));

        $manager
                ->persist(
                        new Config('anim_actif', 'Module Anim activé', '1', NULL));

        $manager
                ->persist(
                        new Config('pass_actif', 'Module Pass activé', '1', NULL));
        $manager
                ->persist(
                        new Config('base_signature_actif', 'Module signature actif', '1', 'Autoriser les orgas à générer leur signature'));

        $manager
                ->persist(
                        new Config('base_admin_login', 'Login admin automatique', '1', NULL));
        $manager
                ->persist(
                        new Config('manifestation_nom', 'Nom de la manifestation', '24 Heures de l\'INSA 41e', NULL));
        $manager
                ->persist(
                        new Config('manifestation_edition', 'Numéro de l\'édition', '41', NULL));
        $manager
                ->persist(
                        new Config('phpm_contenu_page_principale', 'Contenu de la page principale', '<h1>PHPlanningMaker<h2><p>Gestion des ressources humaines.</p>', NULL));

        $manager
                ->persist(
                        new Config('phpm_admin_email', 'Email de l\'administrateur', 'orga@24heures.org', NULL));

        $manager
                ->persist(
                        new Config('phpm_secret_salt', 'Clé secrète pour générer les URL de login', 'xxxx', NULL));
        $manager
                ->persist(
                        new Config('phpm_tache_heure_limite_validation', 'Heure limite pour envoyer les fiches tâches en validation', '2012-05-06 20:00:00', NULL));
        $manager
                ->persist(
                        new Config('phpm_planning_visiteurs', 'Autoriser les visiteurs à accéder à leur planning', 0, NULL));
        $manager
                ->persist(
                        new Config('phpm_contenu_mail_planning', 'Contenu du mail de notification de planning', '<p>Ton planning est disponible.</p>\r\n<p><em>Maud et Adam.</em></p>', NULL));
        $manager
                ->persist(
                        new Config('phpm_ws_access_code', 'Code des WebServices', mt_rand(), NULL));
        $manager
                ->persist(
                        new Config('phpm_anneetrombi', 'Annee du trombi PC', 2013, NULL));

        // Mobile
        $manager
                ->persist(
                        new Config('mobile_baseurl', 'URL mobile', '', NULL));
        $manager
                ->persist(
                        new Config('mobile_version', 'Version courante de l\'appli mobile', '', NULL));

        $manager
                ->persist(
                        new Config('mobile_publish_concert_schedule', 'Publier les horaires des concerts', '', NULL));


        $manager->flush();

        //         $manager->persist($userAdmin);
        //         $manager->flush();
    }

}
