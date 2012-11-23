<?php

namespace AssoMaker\SponsoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class ContactType extends AbstractType
{

    function __construct(){
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        	
    	$builder
    	    ->add('nom',null,array('label'=>'Nom'))
    	    ->add('entreprise','entity',array('class'=>'AssoMakerSponsoBundle:Entreprise','label'=>'Entreprise'))
    	    ->add('email',null,array('label'=>'Email'))
    	    ->add('telephone',null,array('label'=>'Téléphone'))
    	    ->add('adresse','textarea',array('label'=>'Adresse'))
    	    ->add('poste',null,array('label'=>'Poste'));
            
    	
    }

    public function getName()
    {
        return 'assomaker_sponso_bundle_contact_type';
    }
    
    
}
