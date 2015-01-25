<?php

namespace AssoMaker\PHPMBundle\Validator;

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

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if(!$this->isValid($value,$constraint))
            $this->context->addViolation("");
    }
}