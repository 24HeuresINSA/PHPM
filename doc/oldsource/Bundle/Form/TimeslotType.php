<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TimeslotType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('begintime')
            ->add('endtime')
            ->add('orga')
            ->add('task')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_timeslottype';
    }
}
