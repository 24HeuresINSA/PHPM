<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text',array('label'=>'Nom'))
            ->add('description')
            ->add('latitude')
            ->add('longitude')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'AssoMaker\PHPMBundle\Entity\Lieu',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_lieutype';
    }
}
