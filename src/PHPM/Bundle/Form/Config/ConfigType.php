<?php

namespace PHPM\Bundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\EventListener\ConfigFormSubscriber;

class ConfigType extends AbstractType
{
    
	
	public function buildForm(FormBuilder $builder, array $options)
    {
    	
    	
    	$builder->add('value','textarea', array('label'=>' ', 'required'=>false));
    	$builder->add('field','hidden');
    	
        
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\Config',
        );
    }
    

    public function getName()
    {
        return 'phpm_bundle_configtype';
    }
    
    
}
