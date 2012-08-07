<?php

namespace AssoMaker\PHPMBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
* @Annotation
*/

class Recoupe extends Constraint
{
    public $messagePlageHoraire = "Cette plage horaire se recoupe avec une déjà existante.";
    public $messageCreneau = "Ce créneau se recoupe avec un créneau déja affecté";
    public $messageDisponibilite = "Cette disponibilite se recoupe avec une déjà existante.";


    public function validatedBy()
    {
        return 'validator.recoupe';
    }
   
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}



