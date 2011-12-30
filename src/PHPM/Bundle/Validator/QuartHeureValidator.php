<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class QuartHeureValidator extends ConstraintValidator
{
 
    public function isValid($value, Constraint $constraint)
    {
    	$timestamp=$value->getTimestamp();
		
		
		
		if (($timestamp % 900)==0 )
		{
			
			return TRUE;
			
		}
		else 
		{
			$this->setMessage($constraint->message);
			return FALSE;	
			
		}
    }
}

