<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BesoinOrgaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('orgaHint', null, array('label'=>'Orga précis nécessaire', 'empty_value' => 'N\'importe qui', 'required'=> false))
        	->add('equipe', null, array('label'=>'Équipe', 'empty_value' => '(orga précis)', 'required'=> false))        	
            ->add('nbOrgasNecessaires', null, array('label' => 'Orgas nécessaires', 'attr' => array('min' => 1)));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        	'data_class' => 'PHPM\Bundle\Entity\BesoinOrga'
        );
    }
    
    public function getName()
    {
        return 'phpm_bundle_besoinorgatype';
    }
}