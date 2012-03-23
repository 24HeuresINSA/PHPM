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
                    'attr'=>array('class'=>'dtp')))
            ->add('fin',null,array(
                    'label'=>'Fin',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'dtp')))
            ->add('dureeCreneau',null,array('label'=>'Durée d\'un créneau (en sec.)'))
            ->add('recoupementCreneau',null,array('label'=>'Recoupement entre deux créneaux consécutifs (en sec.)' ))
            ->add('nbOrgasComNecessaires',null,array('label'=>'Nombre d\'orgas de l\'équipe nécessaires'))
			->add('nbOrgasNecessaires',null,array('label'=>'Nombre d\'autres orgas nécessaires'))
			->add('respNecessaire',null,array('label'=>'Le responsable de la tâche doit être présent'))


			
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
