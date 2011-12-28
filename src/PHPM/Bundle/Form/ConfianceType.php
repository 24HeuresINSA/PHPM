<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ConfianceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('valeur')
            ->add('couleur')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_confiancetype';
    }
}
