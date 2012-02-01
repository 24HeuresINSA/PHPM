<?php

namespace PHPM\Bundle\Validator;

use PHPM\Bundle\Entity\Creneau;

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
    	$id = $entity->getId();
    	if($entity instanceof Disponibilite){
    		$pid=$entity->getOrga()->getId();
    		$dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d WHERE d.orga = :pid AND (d.debut <= :fin ) AND (d.fin >= :debut) AND d.id!=:id ';
    		$message= $constraint->messageDisponibilite;
    	
    	}elseif ($entity instanceof PlageHoraire){
    		
    		
    		$pid=$entity->getTache()->getId();
    		
    		$dql = 'SELECT (count(p)) FROM PHPMBundle:PlageHoraire p WHERE p.tache = :pid AND (p.debut <= :fin ) AND (p.fin >= :debut) AND p.id!=:id';
    		$message= $constraint->messagePlageHoraire;
    	}elseif ($entity instanceof Creneau){
    	    $pid=$entity->getDisponibilite()->getOrga()->getId();
    	    $dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d, PHPMBundle:Creneau c WHERE c.disponibilite = d AND d.orga = :pid AND (c.debut < :fin ) AND (c.fin > :debut) AND c.id!=:id';
    	    $message= $constraint->messageCreneau;
    	}else{
    		
    		return FALSE;
    	}
    	
    	
    	$result = $this->em
    			->createQuery($dql)
    			->setParameter('id', $id)
    			->setParameter('pid', $pid)
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

