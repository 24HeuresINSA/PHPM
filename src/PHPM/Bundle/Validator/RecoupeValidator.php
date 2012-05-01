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
//     	if(!isset($id))
//     	$id=0;
    	if($entity instanceof Disponibilite){
    	    
    		$pid=$entity->getOrga()->getId();
    		$dql = 'SELECT (count(d)) FROM PHPMBundle:Disponibilite d WHERE (d.orga = :pid) AND (d.debut < :fin ) AND (d.fin > :debut) AND (d.id!=:id )';
    		$message= $constraint->messageDisponibilite;
            $result = $this->em
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('pid', $pid)
               
                ->setParameter('debut', $debut)
                ->setParameter('fin', $fin)
                ->getSingleScalarResult();
            
            
            
    	
    	}elseif ($entity instanceof PlageHoraire){
    		
    	    
    		$pid=$entity->getTache()->getId();
    		
    		$dql = 'SELECT (count(p)) FROM PHPMBundle:PlageHoraire p WHERE p.tache = :pid AND (p.debut < :fin ) AND (p.fin > :debut) AND p.id!=:id';
    		$message= $constraint->messagePlageHoraire;
            
            $result = $this->em
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('pid', $pid)
                
                ->setParameter('debut', $debut)
                ->setParameter('fin', $fin)
                ->getSingleScalarResult();

            
            
    	}elseif ($entity instanceof Creneau){
    		$message= $constraint->messageCreneau;
    		
    		$oid=$entity->getDisponibilite()->getOrga()->getId();
    	    $dql = 'SELECT (count(c)) FROM PHPMBundle:Orga o JOIN o.disponibilites d JOIN d.creneaux c WHERE (c.debut < :fin ) AND (c.fin > :debut) AND o.id =:oid';
            
            $result = $this->em
            ->createQuery($dql)
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->setParameter('oid', $oid)
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

