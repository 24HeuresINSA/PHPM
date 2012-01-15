<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
*/

class QuartHeure extends Constraint
{
    public $message = "L'heure ne peut qu'être une unitié indivisible de quart d'heure";

    public function validatedBy()
    {
        return 'validator.quartHeure';
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}



