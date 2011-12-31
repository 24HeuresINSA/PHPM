<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
*
*/

class DebutAvantFin extends Constraint
{
    public $message = "Le début est après la fin... Try again";
    
    public function validatedBy()
    {
        return 'validator.debutAvantFin';
    }
   
    public function targets()
    {
        return self::CLASS_CONSTRAINT;
    }
}



