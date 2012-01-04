<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\EventListener\ConfigFormSubscriber;

class ConfigType extends AbstractType
{
    
	
	public function buildForm(FormBuilder $builder, array $options)
    {
    	
    	
    	$builder->add('value','textarea', array('label'=>' '));
    	$builder->add('label','hidden');
    	$builder->add('field','hidden');
    	
        
    }

    public function getName()
    {
        return 'phpm_bundle_configtype';
    }
    
    
}
