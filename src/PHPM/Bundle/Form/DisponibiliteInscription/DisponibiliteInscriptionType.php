<?php

namespace PHPM\Bundle\Form\DisponibiliteInscription;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DisponibiliteInscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut',null,array(
                    'label'=>'Début',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'debutdp')))
            ->add('fin',null,array(
                    'label'=>'Fin',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'findp')))
            ->add('statut','choice',array('label'=>'Statut', 'choices'=>array(
        				'0'=>'Verrouillé', '1'=>'Cochable Uniquement', '2'=>'Cochable/Décochable'
        	)))
        		->add('mission')
        		->add('pointsCharisme')
            
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_disponibiliteinscriptiontype';
    }
    
    public function getDefaultOptions(array $options){
        return array('data_class' => 'PHPM\Bundle\Entity\DisponibiliteInscription');
    }
    
}
