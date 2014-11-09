<?php

namespace AssoMaker\PHPMBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class InclusValidator extends ConstraintValidator  // vérifie si un créneau est bien inclus dans une plage horaire ou dans une dispo
{
 
    public function isValid($entity, Constraint $constraint)
    {
    	$disponibilite= $entity->getDisponibilite();
    	$plageHoraire= $entity->getPlageHoraire();
    	
    	$debut=$entity->getDebut()->getTimestamp();
    	$fin=$entity->getFin()->getTimestamp();
    	
    	
    		if($disponibilite!=null){
    			
    		    			
    			if ($debut < $disponibilite->getDebut()->getTimestamp() OR
    			$fin > $disponibilite->getFin()->getTimestamp())
    			{
    				
    				$this->setMessage($constraint->messageDisponibilite);
    				return FALSE;
    			}

    		}	
    		
    		
    			if ($debut < $plageHoraire->getDebut()->getTimestamp() OR
    			$fin > $plageHoraire->getFin()->getTimestamp())
    			{
    				$this->setMessage($constraint->messagePlage);
    				return FALSE;
    			}
    			
    		
		return TRUE;	
	}

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if(!$this->isValid($value,$constraint))
            $this->context->addViolation("");
    }
}