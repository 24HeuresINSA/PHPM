<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
/**
*
*/

class PlageHoraireRecoupeValidator extends ConstraintValidator
{
 
        private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
     
 
    public function isValid($value, Constraint $constraint)  // prend le début de la plage horaire our la tester
    {
       $debut = $this->context->getRoot()->get("debut")->getData();
        $fin = $this->context->getRoot()->get("fin")->getData();
        $id = $this->context->getRoot();
       
       var_dump($id);
        $dql = 'SELECT (count(e)==0) FROM PlageHoraire p WHERE p.tache == :id AND (p.debut <= :fin ) AND (p.fin >= :debut) ';
        
        var_dump( $entityManager
        ->createQuery($dql)
        ->setParameter('id', $id)
        ->setParameter('debut', $debut)
        ->setParameter('fin', $debut)
        ->getResult());
        
        
        
        
        
        
        
         $arrayPlageHoraire = $this->entityManager->getRepository($constraint->entity)->findAll();
          var_dump($arrayPlageHoraire);
          exit();      
         $debutPlageATester = $value->getTimestamp();           
         $finPlageATester = $this->context->getRoot()->get("fin")->getData()->getTimestamp();
         if ($debutPlageATester < $finPlageATester)
            {
			     $this->setMessage($constraint->message);
                 return FALSE;     
            }
			
		 return TRUE;
    }
}

