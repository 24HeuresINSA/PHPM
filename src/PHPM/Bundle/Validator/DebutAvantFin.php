<?php
namespace PHPM\Bundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for the Unique Entity validator
 *
 * @Annotation
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class UniqueEntity extends Constraint
{
    public $message = 'This value is already used';
    public $service = 'doctrine.orm.validator.unique';
    public $em = null;
    public $fields = array();

    public function getRequiredOptions()
    {
        return array('fields');
    }

    /**
     * The validator must be defined as a service with this name.
     *
     * @return string
     */
    public function validatedBy()
    {
        return $this->service;
    }

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getDefaultOption()
    {
        return 'fields';
    }
}


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



