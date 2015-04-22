<?php

namespace AssoMaker\AnimBundle\Form;

use AssoMaker\AnimBundle\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Image;

class ArtisteType extends AbstractType {

    protected $create;
    protected $config;

    function __construct($config, $create) {
        $this->config = $config;
        $this->create = $create;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $a = $builder->create('entity', 'form', array('label' => " ", 'data_class' => 'AssoMaker\AnimBundle\Entity\Artiste'));
        $builder->add($a);

        $a->add('nom', null, array('label' => 'Nom'))
            ->add('stage', 'choice', array('label' => 'Scène', 'choices' => Artiste::$stages))
        ;

        if (!$this->create) {
            $a
                    ->add('description', null, array('label' => 'Description de l\'artiste', 'attr' => array('placeholder' => 'Description de quelques lignes.')))
                    ->add('position', null, array('label' => 'Position (ordre d\'importance pour l\'appli mobile)', 'required' => false))
                    ->add('facebookUrl', null, array('label' => 'URL de la page Facebook (avec http://)', 'required' => false))
                    ->add('twitterUrl', null, array('label' => 'URL de la page Twitter (avec http://)', 'required' => false))
                    ->add('websiteUrl', null, array('label' => 'URL de la page web (avec http://)', 'required' => false))
                    ->add('horaires', 'hidden')
            ;
            $builder->add('photo', 'file', array(
                'label' => 'Ajouter une photo : ',
                'constraints' => array(
                    new Image(array(
                        'mimeTypes' => array('image/jpeg', 'image/png'),
                        'maxSize' => '1M')
                    )
                ),
                'required' => false
            ));
            $builder->add('commentaire', 'textarea', array('label' => 'Ajouter un commentaire', 'required' => false));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

    }

    public function getName() {
        return 'assomaker_animbundle_artistetype';
    }

}
