<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'AssoMaker\BaseBundle\Entity\Confiance',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_confiancetype';
    }
}
