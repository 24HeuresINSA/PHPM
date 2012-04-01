<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('couleur')
            ->add('responsable',null,array('required'=>false,'label'=>false))
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\Equipe',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_equipetype';
    }
}
