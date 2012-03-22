<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
			->add('couleur')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'PHPM\Bundle\Entity\Categorie',
        );
    }

    public function getName()
    {
        return 'phpm_bundle_categorietype';
    }
}
