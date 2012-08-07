<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('couleur')
            ->add('responsable',null,array('required'=>false,'label'=>false))
            ->add('confiance')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'AssoMaker\BaseBundle\Entity\Equipe',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_equipetype';
    }
}
