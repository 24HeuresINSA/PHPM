<?php

namespace AssoMaker\BaseBundle\Form;

use AssoMaker\BaseBundle\Extension\ConfigExtension;
use AssoMaker\BaseBundle\Entity\Orga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuickRegistrationUserType extends AbstractType {

    private $securityContext;
    private $config;

    public function __construct(SecurityContext $securityContext, ConfigExtension $config) {
        $this->securityContext = $securityContext;
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('prenom', null, array('label' => 'Prénom'))
            ->add('nom', null, array('label' => 'Nom'))
            ->add('email', 'email', array('label' => 'Adresse email'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répétez')
            ));
    }

    public function getName() {
        return 'assomaker_base_bundle_quickregistrationtype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\BaseBundle\Entity\Orga',
            'validation_groups' => array('quick_registration')
        ));
    }

}

