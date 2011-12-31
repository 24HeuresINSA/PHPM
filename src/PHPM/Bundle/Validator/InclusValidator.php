<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class InclusValidator extends ConstraintValidator  // vérifie si un créneau est bien inclus dans une plage horaire ou dans une dispo
{
 
    public function isValid($value, Constraint $constraint)
    {
    		$id=$this->context->getRoot()->get($constraint->field)->getData();
			exit(var_dump($id));
    	$plageObject = $this->entityManager->getRepository($constraint->entity)
                ->findOneById($plage->id);
				
		
		if ($value->getTimestamp() >= $plageObject->getDebut()->getTimestamp() AND
			$value->getTimestamp() <= $plageObject->getFin()->getTImestamp())
			{
				return TRUE;
			}		
			
		else {
			$this->setMessage($constraint->message);
			return FALSE;
		}	
		
		
				
	}			
}