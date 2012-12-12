<?php

namespace AssoMaker\SponsoBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class AvancementType extends AbstractType
{

    function __construct(){
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        	
    	$builder
    	    ->add('nom',null,array('label'=>'Nom'))
    	    ->add('entreprise',null,array('label'=>'Entreprise'))
    	    ->add('email',null,array('label'=>'Email'))
    	    ->add('telephone',null,array('label'=>'Téléphone'))
    	    ->add('adresse','textarea',array('label'=>'Adresse'))
    	    ->add('poste',null,array('label'=>'Poste'))
    	    ->add('responsable','entity',array('label'=>'Responsable','class' => 'AssoMakerBaseBundle:Orga'))
    	    ->add('projet','entity',array('label'=>'Projet','class' => 'AssoMakerSponsoBundle:Projet'))
    	;
            
    	
    }

    public function getName()
    {
        return 'assomaker_sponso_bundle_avancement_type';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\SponsoBundle\Entity\Avancement',
            'cascade_validation' => true,
    ));
    }
    
}
