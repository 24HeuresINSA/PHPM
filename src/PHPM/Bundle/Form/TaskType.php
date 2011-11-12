<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('begintime')
            ->add('endtime')
            ->add('duration')
            ->add('overlap')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_tasktype';
    }
}
