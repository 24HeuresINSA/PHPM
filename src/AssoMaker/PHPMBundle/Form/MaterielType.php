<?php

namespace AssoMaker\PHPMBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', null, array('label' => 'Nom'))
                ->add('categorie', null, array('label' => 'Catégorie'))
                ->add('type', 'choice', array('label' => ' ', 'choices' => array('0' => 'Indénombrable', '1' => 'Dénombrable')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PHPMBundle\Entity\Materiel',
        ));
    }

    public function getName()
    {
        return 'phpm_bundle_materieltype';
    }
}
