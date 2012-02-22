<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InscriptionHardType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('public', 'checkbox', array(
    'label'     => 'Show this entry publicly?',
    'required'  => false,
));
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_inscriptionhardtype';
    }
}
