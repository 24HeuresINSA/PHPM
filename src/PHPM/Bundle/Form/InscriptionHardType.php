<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\DisponibiliteInscriptionType;

class InscriptionHardType extends AbstractType
{
    
    protected $admin;
    protected $em;

    function __construct($admin,$em){
        
            $this->em = $em;
            $this->admin =$admin;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        
	    $builder->add('Disponibilités', 'entity', array(
	            'class' => 'PHPMBundle:DisponibiliteInscription',
	            'multiple' => true,
	            'expanded' =>true           
	             
	                    
	            )
	    );

	    
    }

    public function getName()
    {
        return 'phpm_bundle_inscriptionhardtype';
    }
}
