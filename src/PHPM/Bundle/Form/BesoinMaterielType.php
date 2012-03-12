<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BesoinMaterielType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('quantite')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_besoinmaterieltype';
    }
}
