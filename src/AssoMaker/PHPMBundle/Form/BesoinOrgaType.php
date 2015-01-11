<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BesoinOrgaType extends AbstractType {

    protected $config;

    function __construct($config) {

        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $minConfianceOrgaHint = $this->config->getValue('manifestation_orga_plagehoraireconfiancemin');

        $builder
                ->add('orgaHint', 'entity', array('label' => 'Orga précis nécessaire', 'empty_value' => 'N\'importe qui', 'required' => false,
                    'class' => 'AssoMakerBaseBundle:Orga',
                    'query_builder' => function(OrgaRepository $or)use($minConfianceOrgaHint) {
                        return $or->findAllWithConfianceValueMin($minConfianceOrgaHint);
                    }
                ))
                ->add('equipe', 'entity', array('class' => 'AssoMaker\BaseBundle\Entity\Equipe', 'label' => 'Équipe', 'required' => true))
                ->add('nbOrgasNecessaires', null, array('label' => 'Orgas nécessaires', 'attr' => array('min' => 1)));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PHPMBundle\Entity\BesoinOrga',
        ));
    }

    public function getName() {
        return 'phpm_bundle_besoinorgatype';
    }

}
