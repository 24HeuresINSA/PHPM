<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('consignes')
            ->add('lieu')
            ->add('categorie')
            ->add('confiance')
			->add('consignes')
			->add('materielNecessaire')
			->add('permisNecessaire')
			->add('ageNecessaire')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_tachetype';
    }
}
