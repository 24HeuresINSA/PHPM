<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('ordre',null,array('label'=>'Ordre de Tri'))
            ->add('description')
            ->add('confianceMin')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_missiontype';
    }
}
