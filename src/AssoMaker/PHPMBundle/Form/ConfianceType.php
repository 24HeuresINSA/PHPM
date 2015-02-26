<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfianceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('valeur')
            ->add('couleur')
            ->add('privileges')
            ->add('code')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\BaseBundle\Entity\Confiance',
        ));
    }

    public function getName()
    {
        return 'phpm_bundle_confiancetype';
    }
}
