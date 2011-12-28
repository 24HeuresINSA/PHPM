<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrgaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('begintime')
            ->add('endtime')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_orgatype';
    }
}
