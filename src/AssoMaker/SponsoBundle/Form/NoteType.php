<?php

namespace AssoMaker\SponsoBundle\Form;

use AssoMaker\SponsoBundle\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class NoteType extends AbstractType {

    function __construct() {

    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('type', 'choice', array('choices' => Note::$textesTypes))
                ->add('texte', 'textarea', array('label' => 'Texte', 'required' => false))
                ->add('dossierSponso', 'file', array('label' => 'Dossier sponso (.docx)', 'required' => false));
    }

    public function getName() {
        return 'assomaker_sponso_bundle_note_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\SponsoBundle\Entity\Note',
        ));
    }

}
