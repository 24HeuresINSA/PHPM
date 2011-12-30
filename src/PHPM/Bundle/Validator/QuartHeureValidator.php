<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
*
*/

class QuartHeure extends Constraint
{
    public $message = 'L\heure ne peut qu\'être une unitié indivisible de quart d\'heure';
    public $entity;
    public $timestamp;
   
    public function validatedBy()
    {
        return 'validator.quartHeureValidator';
    }
   
    public function requiredOptions()
    {
        return array('entity', 'timestamp');
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}

class QuartHeureValidator extends ConstraintValidator
{
    /**
* Configures the form field and options
*/
    public function isValid($timestamp, Constraint $constraint)
    {
		if (($timestamp % 900)==0 )
		{
			return TRUE;
			
		}
		else 
		{
			return FALSE;	
			$this->setMessage($constraint->message);
		}
    }
}

