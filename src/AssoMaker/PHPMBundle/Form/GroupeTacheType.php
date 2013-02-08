<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupeTacheType extends AbstractType {

    protected $config;
    protected $admin;

    function __construct($admin, $config) {

        $this->config = $config;
        $this->admin = $admin;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nom')
                ->add('responsable')
                ->add('equipe')
                ->add('animLiee', 'entity', array('class' => 'AssoMakerAnimBundle:Animation', 'required' => false, 'label' => 'Animation Liée'))
                ->add('lieu')
        ;
    }

    public function getName() {
        return 'phpm_bundle_groupetachetype';
    }

}
