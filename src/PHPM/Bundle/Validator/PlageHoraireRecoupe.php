<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
*
*/

class PlageHoraireRecoupe extends Constraint
{
    public $message = "La plage horaire se recoupe avec une déjà existante.";

    public function validatedBy()
    {
        return 'validator.plageHoraireRecoupe';
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}



