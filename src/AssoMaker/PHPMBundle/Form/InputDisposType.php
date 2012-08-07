<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\PHPMBundle\Form\DisponibiliteInscriptionType;

class InputDisposType extends AbstractType
{
    
      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       

       
       $builder->add('disponibiliteInscriptionItems', 'entity',
       		array(	'class' => 'AssoMakerPHPMBundle:DisponibiliteInscription',
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
