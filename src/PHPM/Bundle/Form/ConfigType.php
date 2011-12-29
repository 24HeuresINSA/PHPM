<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('field')
            ->add('value')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_configtype';
    }
}
