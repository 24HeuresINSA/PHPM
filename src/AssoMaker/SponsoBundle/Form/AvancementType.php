<?php

namespace AssoMaker\SponsoBundle\Form;

use AssoMaker\SponsoBundle\Entity\Avancement;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class AvancementType extends AbstractType {

    protected $admin;

    function __construct($admin) {
        $this->admin = $admin;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nom', null, array('label' => 'Nom', 'required' => false))
                ->add('entreprise', null, array('label' => 'Entreprise'))
                ->add('email', null, array('label' => 'Email', 'required' => false))
                ->add('telephone', null, array('label' => 'Téléphone', 'required' => false))
                ->add('adresse', 'textarea', array('label' => 'Adresse', 'required' => false))
                ->add('poste', null, array('label' => 'Poste', 'required' => false))
                ->add('responsable', 'entity', array('label' => 'Responsable', 'class' => 'AssoMakerBaseBundle:Orga'))
                ->add('projet', 'entity', array('label' => 'Projet', 'class' => 'AssoMakerSponsoBundle:Projet'))

        ;

        if ($this->admin) {
            $builder
                    ->add('statut', 'choice', array('choices' => Avancement::$messagesStatut))
            ;
        }
    }

    public function getName() {
        return 'assomaker_sponso_bundle_avancement_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\SponsoBundle\Entity\Avancement',
            'cascade_validation' => true,
        ));
    }

}
