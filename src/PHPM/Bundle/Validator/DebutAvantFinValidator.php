<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class DebutAvantFinValidator extends ConstraintValidator  // vérifie si le début est bien avant la fin
{ 
    public function isValid($entity, Constraint $constraint)
    {
    	
    	
    	if ($entity->getDebut() < $entity->getFin())
		{
			return TRUE;
		}
    	
		else {
			
		$this->setMessage($constraint->message);
			return FALSE;
		}
    		
				
	}			
}