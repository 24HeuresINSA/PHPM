<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TimespanType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('begintime')
            ->add('endtime')
            ->add('orgasneeded')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_timespantype';
    }
}
