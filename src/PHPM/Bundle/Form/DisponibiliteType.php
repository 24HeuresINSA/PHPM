<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DisponibiliteType extends AbstractType
{

	public $orgaOptions = array();
	
    public function buildForm(FormBuilder $builder, array $options)
    {
    	
        $builder
            ->add('debut')
            ->add('fin')
			->add('orga',null,$this->orgaOptions);
            
        
		
		
    }

    public function getName()
    {
        return 'phpm_bundle_disponibilitetype';
    }
	
	public function disableOrga()
	{
		$this->orgaOptions = array("read_only" => TRUE);
	}
	
}
