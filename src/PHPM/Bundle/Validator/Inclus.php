<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
*
*/

class Inclus extends Constraint
{
    public $message = 'Veuillez renseigner une valeur incluse dans la plage';

	public $entity;
	public $creneau;


    /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array('entity');
    }

    public function validatedBy()
    {
        return 'validator.inclus';
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}



