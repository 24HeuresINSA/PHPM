<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('lieu')
            ->add('categorie')
            ->add('confiance')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_tachetype';
    }
}
