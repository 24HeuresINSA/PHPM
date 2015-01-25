<?php

namespace AssoMaker\PHPMBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class QuartHeureValidator extends ConstraintValidator
{
 
    public function isValid($value, Constraint $constraint)
    {            
          if($value instanceof \DateTime)         
            {
             $timestamp=$value->getTimestamp();  
            }
        else
            {
             $timestamp=$value; // dans le cas où on passe directement un timestamp   
            }

		 
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

