<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\DisponibiliteInscriptionType;

class InputDisposType extends AbstractType
{
    
    protected $admin;

    function __construct($admin){
        

            $this->admin =$admin;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
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
