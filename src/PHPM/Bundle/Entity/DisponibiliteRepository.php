<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DisponibiliteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DisponibiliteRepository extends EntityRepository
{
	
	public function getContainingDisponibilite($orga, $creneau) 
	{
	
		return $this->getEntityManager()
		->createQuery("SELECT d FROM PHPMBundle:Disponibilite d JOIN d.orga o, PHPMBundle:Creneau c
		WHERE d.orga = :orga_id AND c.id = :creneau_id AND c.debut >= d.debut AND c.fin <= d.fin
		")
		->setMaxResults(1)
		->setParameter('orga_id', $orga->getId())
		->setParameter('creneau_id', $creneau->getId())
	
		->getResult();
		
	
	}
}