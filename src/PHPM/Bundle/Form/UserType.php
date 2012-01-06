<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label'=>'Nom d\' utilisateur'))
            ->add('pass', 'text',array('label'=>'Mot de passe'))
            ->add('email','email',array('label'=>'Adresse email'))
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_usertype';
    }
}
