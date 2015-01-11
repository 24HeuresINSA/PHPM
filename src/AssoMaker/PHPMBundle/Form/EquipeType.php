<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EquipeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')
                ->add('couleur')
                ->add('responsable', null, array('required' => false, 'label' => false))
                ->add('confiance', 'entity', array('class' => 'AssoMaker\BaseBundle\Entity\Confiance'))
                ->add('showOnTrombi', null, array('required' => false, 'label' => false))
                ->add('comptesPersoEnabled', null, array('required' => false, 'label' => false))
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
