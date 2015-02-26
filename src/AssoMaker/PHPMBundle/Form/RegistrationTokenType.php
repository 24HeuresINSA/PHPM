<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationTokenType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('token')
                ->add('equipe', 'entity', array('class' => 'AssoMaker\BaseBundle\Entity\Equipe'))
                //->add('email', null, array('required' => false, 'label' => "EMail"))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\BaseBundle\Entity\Equipe',
        ));
    }

    public function getName() {
        return 'phpm_bundle_equipetype';
    }

}
