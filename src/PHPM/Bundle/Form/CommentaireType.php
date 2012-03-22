<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('heure')
            ->add('tache')
            ->add('auteur')
            ->add('texte')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_commentairetype';
    }
}
