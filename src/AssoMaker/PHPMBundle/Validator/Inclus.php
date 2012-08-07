<?php

namespace AssoMaker\PHPMBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/

class Inclus extends Constraint
{
    public $messagePlage = 'Veuillez renseigner une valeur incluse dans la plage Horaire.';
    public $messageDisponibilite = 'Veuillez renseigner une valeur incluse dans la Disponibilite.';
    public $messageOrdre = 'Veuillez renseigner une fin postérieure au début.';

	public $entity;
	public $creneau;

    
    public function validatedBy()
    {
        return 'validator.inclus';
    }
   
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}



