<?php

namespace PHPM\Bundle\Form\DisponibiliteInscription;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DisponibiliteInscriptionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
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
            ->add('categorie')
            
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
