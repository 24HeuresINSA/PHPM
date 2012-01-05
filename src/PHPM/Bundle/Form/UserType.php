<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('pass')
            ->add('email')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_usertype';
    }
}
