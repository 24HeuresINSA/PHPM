<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Entity\EquipeRepository;

class PrintPlanningType extends AbstractType
{

    

    function __construct(){
        


    }
    
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        
    	$builder
    	->add('debut','date',array(
    			'format' => 'yyyy-MM-dd',
    			'widget' => 'single_text',
    			'label'=>'Début',
    			'attr'=>array('class'=>'datep')))
    	->add('fin','date',array(
    					'format' => 'yyyy-MM-dd',
    					'widget' => 'single_text',
    					'label'=>'Fin',
    					'attr'=>array('class'=>'datep')))
    	->add('orga', 'entity', array('label'=>'Orga',  'required'=> false,
    							'class' => 'PHPMBundle:Orga'    			))
    	->add('equipe', 'entity', array('label'=>'Équipe',  'required'=> false,
    									'class' => 'PHPMBundle:Equipe'    			))				
    	;
    	    
    }

    public function getName()
    {
        return 'phpm_bundle_printplanningtype';
    }
    
    
}
