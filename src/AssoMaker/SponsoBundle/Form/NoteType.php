<?php

namespace AssoMaker\SponsoBundle\Form;

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
    	    ->add('type', 'choice', array('choices'   => array('Infos','Rencontre','Appel téléphonique','Mail','Autre')))
    	    ->add('statut', 'choice', array('choices'   => array(-1 => 'Supprimé',0 => 'À faire',1 => 'En cours',2 => 'Terminé/Rien à faire')))
    	    ->add('texte','textarea',array('label'=>'Texte'));
           
    	
    }

    public function getName()
    {
        return 'assomaker_sponso_bundle_note_type';
    }
    
    
}
