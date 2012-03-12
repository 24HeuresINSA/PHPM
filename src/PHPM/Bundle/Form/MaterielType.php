<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('categorie')
            ->add('type')
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_materieltype';
    }
}
