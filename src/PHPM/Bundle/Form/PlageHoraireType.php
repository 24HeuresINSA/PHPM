<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlageHoraireType extends AbstractType
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
			->add('nbOrgasNecessaires',null,array('label'=>'Orgas nécessaires'))
			->add('dureeCreneau',null,array('label'=>'Durée d\'un créneau'))
			->add('recoupementCreneau',null,array('label'=>'Marge entre créneaux'))
			
			;

    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\PlageHoraire',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_plagehorairetype';
    }
}
