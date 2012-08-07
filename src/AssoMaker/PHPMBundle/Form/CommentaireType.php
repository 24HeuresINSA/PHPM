<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
