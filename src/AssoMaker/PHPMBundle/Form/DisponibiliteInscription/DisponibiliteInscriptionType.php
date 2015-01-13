<?php

namespace AssoMaker\PHPMBundle\Form\DisponibiliteInscription;

use AssoMaker\PHPMBundle\Form\LimiteInscriptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DisponibiliteInscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut','datetime',array(
                    'label'=>'Début',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'debutdp'),
                    'format'=>'yyyy-MM-dd HH:mm:ss'))
            ->add('fin','datetime',array(
                    'label'=>'Fin',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'findp'),
                    'format'=>'yyyy-MM-dd HH:mm:ss'))
            ->add('statut','choice',array('label'=>'Statut', 'choices'=>array(
        				'0'=>'Verrouillé', '1'=>'Cochable Uniquement', '2'=>'Cochable/Décochable'
        	)))
        		->add('mission')
        		->add('pointsCharisme')
            ->add('limitesInscriptions','collection', array('type' => new LimiteInscriptionType($builder,$options),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'  => ' ',
                'options'  => array('label' => ' ')
            ))
            
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_disponibiliteinscriptiontype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PHPMBundle\Entity\DisponibiliteInscription',
        ));
    }
    
}
