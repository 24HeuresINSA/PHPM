<?php

namespace AssoMaker\SponsoBundle\Form;

use AssoMaker\SponsoBundle\Entity\Note;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class NoteType extends AbstractType
{

    function __construct(){
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        	
    	$builder
    	    ->add('type', 'choice', array('choices'   => Note::$textesTypes))
    	    ->add('texte','textarea',array('label'=>'Texte'));
           
    	
    }

    public function getName()
    {
        return 'assomaker_sponso_bundle_note_type';
    }
    
    
}
