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
    	if(!isset($id))
    	$id=0;
    	if($entity instanceof Disponibilite){
    	    
    		$pid=$entity->getOrga()->getId();
    		$dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d WHERE (d.orga = :pid) AND (d.debut < :fin ) AND (d.fin > :debut) AND (d.id!=:id )';
    		$message= $constraint->messageDisponibilite;
            $result = $this->em
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('pid', $pid)
               
                ->setParameter('debut', $debut)
                ->setParameter('fin', $debut)
                ->getSingleScalarResult();
            
            
            
    	
    	}elseif ($entity instanceof PlageHoraire){
    		
    		
    		$pid=$entity->getTache()->getId();
    		
    		$dql = 'SELECT (count(p)) FROM PHPMBundle:PlageHoraire p WHERE p.tache = :pid AND (p.debut < :fin ) AND (p.fin >mm :debut) AND p.id!=:id';
    		$message= $constraint->messagePlageHoraire;
            
            $result = $this->em
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('pid', $pid)
                
                ->setParameter('debut', $debut)
                ->setParameter('fin', $debut)
                ->getSingleScalarResult();
            
            
            
            
    	}elseif ($entity instanceof Creneau){
    	    $pid=$entity->getDisponibilite()->getOrga()->getId();
            $tid=$entity->getPlageHoraire()->getTache()->getId();
    	    $dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d, PHPMBundle:Creneau c, PHPMBundle:PlageHoraire p, PHPMBundle:Tache t
    	     WHERE c.disponibilite = d AND c.plageHoraire = p AND p.tache = t AND d.orga = :pid AND (c.debut < :fin ) AND (c.fin > :debut) AND c.id!=:id AND t.id!=:tid';
    	    $message= $constraint->messageCreneau;
            
            $result = $this->em
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('pid', $pid)
                ->setParameter('tid', $tid)
                ->setParameter('debut', $debut)
                ->setParameter('fin', $debut)
                ->getSingleScalarResult();
            
    	}else{
    		
    		return FALSE;
    	}
    	
    	
        
    	
    	
    	if($result > 0){
    		$this->setMessage($message);
    		return FALSE;
    	}
    	
    	
    	
			
		 return TRUE;
    }
}

