<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class PrintPlanningType extends AbstractType
{

    

    function __construct(){
        


    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
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
    							'class' => 'AssoMakerBaseBundle:Orga'    			))
    	->add('equipe', 'entity', array('label'=>'Équipe',  'required'=> false,
    									'class' => 'AssoMakerBaseBundle:Equipe'    			))				
    	;
    	    
    }

    public function getName()
    {
        return 'phpm_bundle_printplanningtype';
    }
    
    
}
