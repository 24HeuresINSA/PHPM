<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrgaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('surnom')
            ->add('telephone')
            ->add('email')
            ->add('dateDeNaissance')
            ->add('departement')
            ->add('commentaire')
            ->add('permisB')
            ->add('permisB2ans')
            ->add('confiance')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_orgatype';
    }
}
