<?php

namespace AssoMaker\PHPMBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
*/

class QuartHeure extends Constraint
{
    public $message = "Veuillez indiquer un multiple de quart d'heure";

    public function validatedBy()
    {
        return 'validator.quartHeure';
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}



