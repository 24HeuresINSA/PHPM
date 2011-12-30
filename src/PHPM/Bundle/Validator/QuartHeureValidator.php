<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
*
*/
class quartHeuerValidator extends ConstraintValidator
{
    /**
* Configures the form field and options
*/
    public function isValid($creneauDebut, $creneauFin)
    {
		if (($creneauDebut % 900)==0 AND ($creneauDebut % 900) == 0)
		{
			return TRUE;
		}
		else 
		{
			return FALSE;	
		}
    }
}

