<?php

namespace PHPM\Bundle\Validator;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use PHPM\Bundle\Entity\Disponibilite;
use PHPM\Bundle\Entity\PlageHoraire;


/**
*
*/

class RecoupeValidator extends ConstraintValidator
{

	public $em;
	

	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}
	
	
	
  
public function isValid($entity, Constraint $constraint)
    {
    	$debut=$entity->getDebut()->format('Y-m-d H:i:s');
    	$fin=$entity->getFin()->format('Y-m-d H:i:s');
    	
    	if($entity instanceof Disponibilite){
    		$id=$entity->getOrga()->getId();
    		$dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d WHERE d.orga = :id AND (d.debut <= :fin ) AND (d.fin >= :debut) ';
    		$message= $constraint->messageDisponibilite;
    	
    	}elseif ($entity instanceof PlageHoraire){
    		
    		
    		$id=$entity->getTache()->getId();
    		$dql = 'SELECT (count(p)) FROM PHPMBundle:PlageHoraire p WHERE p.tache = :id AND (p.debut <= :fin ) AND (p.fin >= :debut) ';
    		$message= $constraint->messagePlageHoraire;
    	}else{
    		
    		return FALSE;
    	}
    	
    	
    	$result = $this->em
    			->createQuery($dql)
    			->setParameter('id', $id)
    			->setParameter('debut', $debut)
    			->setParameter('fin', $debut)
    			->getSingleScalarResult();
    	
    	if($result > 0){
    		$this->setMessage($message);
    		return FALSE;
    	}
    	
    	
    	
			
		 return TRUE;
    }
}

