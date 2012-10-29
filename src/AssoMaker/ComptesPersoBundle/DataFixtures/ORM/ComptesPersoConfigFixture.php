<?php
namespace AssoMaker\ComptesPersoBundle\DataFixtures\ORM;
use AssoMaker\PHPMBundle\Entity\Config;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ComptesPersoConfigFixture implements FixtureInterface {

	public function load(ObjectManager $manager) {
		echo "Starting to load PHPLanningmaker ComptePerso FIXTURES\n";

		$manager
		->persist(
				new Config('comptes_perso_prix_conso_standard',
						'Prix d\'une consommation standard',
						'0.4',
						NULL));
		
		$manager->flush();

	}
}
