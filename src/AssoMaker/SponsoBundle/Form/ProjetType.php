<?php

namespace AssoMaker\SponsoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class ProjetType extends AbstractType
{

    function __construct(){
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        	
      
        $builder->add('contact',new ContactType());
        
    	$builder
    	    ->add('nom',null,array('label'=>'Nom'))
    	    
    	//    ->add('contact','entity',array('label'=>'Contact','class' => 'AssoMakerSponsoBundle:Contact'))
    	    ->add('description',null,array('label'=>'Description'))
    	    ->add('equipe','entity',array('label'=>'Equipe','class' => 'AssoMakerBaseBundle:Equipe'))
    	    ->add('responsable','entity',array('label'=>'Responsable','class' => 'AssoMakerBaseBundle:Orga'))

    	    ;
            
    	
    }

    public function getName()
    {
        return 'assomaker_sponso_bundle_projet_type';
    }
    
    
}
