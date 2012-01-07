<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class PlageHoraireRecoupeValidator extends ConstraintValidator
{
 
    public function isValid($value, Constraint $constraint)  // prend le début de la plage horaire our la tester
    {
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

