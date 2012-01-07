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
    //    var_dump(get_class($value));
      //  if(get_class($value)=="DateTime")
            
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
}

