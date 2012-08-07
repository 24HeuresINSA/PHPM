<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PHPM\Bundle\Form\DisponibiliteInscriptionType;

class InputDisposType extends AbstractType
{
    
      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       

       
       $builder->add('disponibiliteInscriptionItems', 'entity',
       		array(	'class' => 'PHPMBundle:DisponibiliteInscription',
       				'expanded'=>true,
       				'multiple'=>true,
       				'by_reference' => false,
       				'label' => 'Di'
       		));
       
       
       
        
	    
    }

    public function getName()
    {
        return 'phpm_bundle_inputdispostype';
    }
}
