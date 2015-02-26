<?php

namespace AssoMaker\PHPMBundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\PHPMBundle\Form\EventListener\ConfigFormSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
    	
    	$builder->add('value','textarea', array('label'=>' ', 'required'=>false));
    	$builder->add('field','hidden');
    	
        
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PHPMBundle\Entity\Config',
        ));
    }

    public function getName()
    {
        return 'phpm_bundle_configtype';
    }
    
    
}
