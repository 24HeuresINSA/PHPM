<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('ordre',null,array('label'=>'Ordre de Tri'))
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_missiontype';
    }
}
