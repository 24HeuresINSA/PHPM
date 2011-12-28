<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CreneauType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('debut')
            ->add('fin')
            ->add('disponibilite')
            ->add('plageHoraire')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_creneautype';
    }
}
