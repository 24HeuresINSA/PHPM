<?php

namespace AssoMaker\BaseBundle\DataFixtures\ORM;

use AssoMaker\BaseBundle\Entity\Orga;
use AssoMaker\BaseBundle\Entity\Equipe;
use AssoMaker\BaseBundle\Entity\Confiance;
use AssoMaker\PHPMBundle\Entity\Config;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BaseEntitiesFixture implements FixtureInterface {

    public function load(ObjectManager $manager) {
        echo "Starting to load PHPLanningmaker Entities FIXTURES\n";

        //Confiances

        $softConfiance = new Confiance();
        $softConfiance->setNom('Soft');
        $softConfiance->setValeur(100);
        $softConfiance->setCouleur('blue');
        $softConfiance->setCode(mt_rand());
        $softConfiance->setPrivileges(0);
        $manager->persist($softConfiance);

        $confianceConfiance = new Confiance();
        $confianceConfiance->setNom('Confiance');
        $confianceConfiance->setValeur(500);
        $confianceConfiance->setCouleur('green');
        $confianceConfiance->setCode(mt_rand());
        $confianceConfiance->setPrivileges(0);
        $manager->persist($confianceConfiance);

        $hardConfiance = new Confiance();
        $hardConfiance->setNom('Hard');
        $hardConfiance->setValeur(1000);
        $hardConfiance->setCouleur('orange');
        $hardConfiance->setCode(mt_rand());
        $hardConfiance->setPrivileges(1);
        $manager->persist($hardConfiance);

        $hardEquipe = new Equipe();
        $hardEquipe->setConfiance($hardConfiance);
        $hardEquipe->setCouleur($hardConfiance->getCouleur());
        $hardEquipe->setNom($hardConfiance->getNom());

        $admin = new Orga();
        $admin->setNom("Admin");
        $admin->setPrenom("Admin");
        $admin->setAnneeEtudes(0);
        $admin->setDateDeNaissance(new \DateTime());
        $admin->setDepartement("Admin");
        $admin->setTelephone("0699999999");
        $admin->setEmail('admin@admin.com');
        $admin->setEquipe($hardEquipe);
        $admin->setStatut($hardEquipe);
        $admin->setPrivileges(2);


        $hardEquipe->setResponsable($admin);

        $manager->persist($hardEquipe);
        $manager->persist($admin);





        $manager->flush();
    }

}
