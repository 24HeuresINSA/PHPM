<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LimiteInscriptionType extends AbstractType {

    protected $config;

    function __construct($config) {

        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('confiance', 'entity', array('class'=>'AssoMaker\BaseBundle\Entity\Confiance','required'=>true))
                ->add('max', 'integer', array('label' => 'Nombre Maximum', 'required' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PHPMBundle\Entity\LimiteInscription',
        ));
    }

    public function getName() {
        return 'phpm_bundle_limiteditype';
    }

}
