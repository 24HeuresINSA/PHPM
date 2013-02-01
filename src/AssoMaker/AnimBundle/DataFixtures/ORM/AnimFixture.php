<?php
namespace AssoMaker\ComptesPersoBundle\DataFixtures\ORM;
use AssoMaker\PHPMBundle\Entity\Config;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ComptesPersoConfigFixture implements FixtureInterface {

	public function load(ObjectManager $manager) {
		echo "Starting to load PHPLanningmaker Anim FIXTURES\n";



	}
}
