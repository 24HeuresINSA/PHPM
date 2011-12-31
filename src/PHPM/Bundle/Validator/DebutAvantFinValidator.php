<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class DebutAvantFinValidator extends ConstraintValidator  // vérifie si le début est bien avant la fin
{ 
    public function isValid($value, Constraint $constraint)
    {
    	$debut = $this->context->getRoot()->get("debut")->getData();
    	$fin = $this->context->getRoot()->get("fin")->getData();
    	$debut = $debut->getTimestamp();
		$fin=$fin->getTimestamp();
    	
    	
    	if ($debut < $fin)
		{
			return TRUE;
		}
    	
		else {
			
		$this->setMessage($constraint->message);
			return FALSE;
		}
    		
				
	}			
}