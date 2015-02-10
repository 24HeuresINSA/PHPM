<?php

namespace AssoMaker\AnimBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtistType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('stage', 'choice', array('label' => 'Stage', 'choices' => ["SMALL","BIG"]))
            //            ->add('horaires')
            ->add('mainPictureUrl')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\AnimBundle\Entity\Artist'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assomaker_animbundle_artist';
    }
}
