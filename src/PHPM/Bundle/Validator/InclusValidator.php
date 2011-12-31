<?php

namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
*
*/

class InclusValidator extends ConstraintValidator  // vérifie si un créneau est bien inclus dans une plage horaire ou dans une dispo
{
 
    public function isValid($value, Constraint $constraint)
    {
    	$dispoObject = $this->context->getRoot()->get("disponibilite")->getData();
    	$plageObject = $this->context->getRoot()->get("plageHoraire")->getData();
    	
    	
    		if($dispoObject->getId()!=0){
    			
    		    			
    			if ($value->getTimestamp() < $dispoObject->getDebut()->getTimestamp() OR
    			$value->getTimestamp() > $dispoObject->getFin()->getTimestamp())
    			{
    				
    				$this->setMessage($constraint->messageDisponibilite);
    				return FALSE;
    			}

    		}	
    		
    		
    		
    		if ($value->getTimestamp() < $plageObject->getDebut()->getTimestamp() OR
    		$value->getTimestamp() > $plageObject->getFin()->getTimestamp())
    		{
    			$this->setMessage($constraint->messagePlage);
    			return FALSE;
    		}
    		
		return TRUE;	
	}			
}