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
            ->add('tache')
			->add('nbOrgasNecessaires')
			->add('dureeCreneau')
			->add('recoupementCreneau')
			
			;

    }

    public function getName()
    {
        return 'phpm_bundle_plagehorairetype';
    }
}
