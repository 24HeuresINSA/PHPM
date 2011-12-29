<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlageHoraireType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('debut')
            ->add('fin')
            ->add('tache')
			->add('nbOrgasNecessaires')
			
			;

    }

    public function getName()
    {
        return 'phpm_bundle_plagehorairetype';
    }
}
