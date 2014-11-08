<?php

namespace AssoMaker\AnimBundle\Form;

use AssoMaker\AnimBundle\Entity\Animation;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnimationType extends AbstractType {

    protected $log;
    protected $create;
    protected $config;
    protected $disabled;

    function __construct($log, $config, $create, $readOnly = false) {
        $this->log = $log;
        $this->config = $config;
        $this->create = $create;
        $this->disabled = $readOnly;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $minConfianceResp = $this->config->getValue('manifestation_orga_responsableconfiancemin');

        $a = $builder->create('entity', 'form', array('label' => " ", 'data_class' => 'AssoMaker\AnimBundle\Entity\Animation'));
        $builder->add($a);

        $a->add('nom', null, array('label' => 'Nom', 'disabled' => $this->disabled['h']))
                ->add('responsable', 'entity', array(
                    'label' => 'Responsable',
                    'class' => 'AssoMakerBaseBundle:Orga',
                    'disabled' => $this->disabled['h'],
                    'query_builder' => function(OrgaRepository $or)use($minConfianceResp) {
                        return $or->findAllWithConfianceValueMin($minConfianceResp);
                    }))
                ->add('orgaManif', 'entity', array(
                    'label' => 'Orga sur la manif',
                    'class' => 'AssoMakerBaseBundle:Orga',
                    'query_builder' => function(OrgaRepository $or)use($minConfianceResp) {
                        return $or->findAllWithConfianceValueMin($minConfianceResp);
                    }, 'disabled' => $this->disabled['h']))
                ->add('equipe', 'entity', array('label' => 'Équipe', 'class' => 'AssoMakerBaseBundle:Equipe', 'disabled' => $this->disabled['h']))
                ->add('type', 'choice', array('label' => 'Type', 'choices' => Animation::$animTypes, 'disabled' => $this->disabled['h']))

        ;

        if (!$this->create) {
            $a
                    ->add('extNom', null, array('label' => 'Nom', 'disabled' => $this->disabled['h']))
                    ->add('extType', 'choice', array('label' => 'Type', 'choices' => Animation::$extTypes, 'disabled' => $this->disabled['h']))
                    ->add('extTelephone', null, array('label' => 'Téléphone', 'disabled' => $this->disabled['h']))
                    ->add('extEmail', null, array('label' => 'Email', 'disabled' => $this->disabled['h']))
                    ->add('extCommentaire', null, array('label' => 'Commentaire', 'attr' => array('placeholder' => 'Commentaire'), 'disabled' => $this->disabled['h']))
                    ->add('extPresent', null, array('label' => 'Présent sur la manif', 'disabled' => $this->disabled['h']))
                    ->add('extBoisson', 'integer', array('label' => 'Tickets Boisson', 'attr' => array('class' => 'input-mini'), 'disabled' => $this->disabled['h']))
                    ->add('extBouffe', 'integer', array('label' => 'Tickets Bouffe', 'attr' => array('class' => 'input-mini'), 'disabled' => $this->disabled['h']))
                    ->add('extCatering', null, array('label' => 'Accès bouffe artiste', 'disabled' => $this->disabled['h']))
                    ->add('lieu', null, array('label' => 'Lieu exact', 'disabled' => $this->disabled['h']))
                    ->add('locX', 'hidden', array('disabled' => $this->disabled['h']))
                    ->add('locY', 'hidden', array('disabled' => $this->disabled['h']))
                    ->add('public', null, array('label' => 'Publier sur le site / plaquette', 'disabled' => $this->disabled['h']))
                    ->add('description', null, array('label' => 'Description de l\'animation', 'disabled' => $this->disabled['h']))
                    ->add('pubPicture', null, array('label' => 'Image à mettre sur le site', 'disabled' => $this->disabled['h']))
                    ->add('animPhare', null, array('label' => 'Anim Phare', 'disabled' => $this->disabled['h']))
                    ->add('animGosses', null, array('label' => 'Anim pour les gosses', 'disabled' => $this->disabled['h']))
                    ->add('elec', null, array('label' => 'Besoin d\'éléctricité', 'disabled' => $this->disabled['l']))
                    ->add('elecAmperes', null, array('label' => 'Ampères', 'disabled' => $this->disabled['l']))
                    ->add('elecTri', null, array('label' => 'Triphasé', 'disabled' => $this->disabled['l']))
                    ->add('besoinSecu', null, array('label' => 'Dispositif de sécurité particulier', 'disabled' => $this->disabled['s']))
                    ->add('besoinPass', null, array('label' => 'Besoin de pass', 'disabled' => $this->disabled['s']))
                    ->add('detailSecu', null, array('label' => 'Détails', 'attr' => array('placeholder' => 'Description du dispositif de sécurité à mettre en place (AS, barrièrage, ERP spécial, autorisation spéciale, types de risques, etc.)'), 'disabled' => $this->disabled['s']))
                    ->add('besoinSigna', null, array('label' => 'Besoin de signalétique', 'disabled' => $this->disabled['h']))
                    ->add('detailSigna', null, array('label' => 'Desctiption du dispositif', 'attr' => array('placeholder' => 'Texte à mettre sur les panneaux, nombre, etc'), 'disabled' => $this->disabled['h']))
                    ->add('horaires', 'hidden', array('disabled' => $this->disabled['h']))
                    ->add('materiel', 'hidden', array('disabled' => $this->disabled['l']))
                    ->add('lieuDepotLog', 'choice', array('label' => 'Lieu de dépôt du matériel', 'choices' => Animation::$lieuxDepotLog, 'required' => false, 'disabled' => !$this->log))
            ;
            $builder->add('commentaire', 'textarea', array('label' => 'Ajouter un commentaire', 'required' => false));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

    }

    public function getName() {
        return 'assomaker_animbundle_animationtype';
    }

}
