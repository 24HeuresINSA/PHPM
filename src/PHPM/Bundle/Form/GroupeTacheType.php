<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GroupeTacheType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('responsable')
            ->add('equipe')
            ->add('lieu')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_groupetachetype';
    }
}
