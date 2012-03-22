<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('categorie')
            ->add('type','choice', array(
                    'label'=>' ',
                    'choices'   => array(
                            '0' => 'Dénombrable',
                             '1' => 'Indénombrable')))
            ->add('description')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\Materiel',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_materieltype';
    }
}
