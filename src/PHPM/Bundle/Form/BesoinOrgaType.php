<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BesoinOrgaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('equipe', null, array('label'=>' '))
            ->add('nbOrgasNecessaires', null, array('label' => 'Orgas nécessaires', 'attr' => array('min' => 1)));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\BesoinOrga',
        );
    }
    
    public function getName()
    {
        return 'phpm_bundle_besoinorgatype';
    }
}