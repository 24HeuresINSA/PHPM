<?php

namespace AssoMaker\PHPMBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/

class DebutAvantFin extends Constraint
{
    public $message = "Veuillez indiquer une heure de fin postérieure à l'heure de début.";
    
    
    public function validatedBy()
    {
        return 'validator.debutAvantFin';
    }
   
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}



