<?php

namespace AssoMaker\SponsoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class ProjetType extends AbstractType {

    function __construct() {

    }

    public function buildForm(FormBuilderInterface $builder, array $options) {




        $builder
                ->add('nom', null, array('label' => 'Projet à faire sponsoriser'))
                ->add('equipe', 'entity', array('label' => 'Equipe', 'class' => 'AssoMakerBaseBundle:Equipe'))

        ;
    }

    public function getName() {
        return 'assomaker_sponso_bundle_projet_type';
    }

}
