<?php

namespace AssoMaker\AnimBundle\Form;

use AssoMaker\AnimBundle\Entity\Animation;

use AssoMaker\BaseBundle\Entity\OrgaRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnimationType extends AbstractType
{
    
    protected $admin;
    protected $create;
    protected $config;
    protected $disabled;
    
    function __construct($admin,$config,$create,$disabled = false){
        $this->admin =$admin;
        $this->config=$config;
        $this->create=$create;
        $this->disabled=$disabled;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $minConfianceResp = $this->config->getValue('manifestation_orga_responsableconfiancemin');
        
        $a = $builder->create('entity', 'form', array('disabled'=>$this->disabled,'label' => " ", 'data_class' => 'AssoMaker\AnimBundle\Entity\Animation'));
        $builder->add($a);
        
        $a  ->add('nom',null,array('label'=>'Nom'))
            ->add('responsable','entity',array(
                'label'=>'Responsable',
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
            ->add('orgaManif','entity',array(
                'label'=>'Orga sur la manif',
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
                
            ->add('equipe','entity',array('label'=>'Équipe','class' => 'AssoMakerBaseBundle:Equipe'))
            ->add('type', 'choice',array('label'=>'Type','choices'=>Animation::$animTypes))
            
        ;
        
        if(!$this->create){
            $a            
            ->add('extNom',null,array('label'=>'Nom'))
            ->add('extType', 'choice',array('label'=>'Type','choices'=>Animation::$extTypes))
            ->add('extTelephone',null,array('label'=>'Téléphone'))
            ->add('extEmail',null,array('label'=>'Email'))
            ->add('extCommentaire',null,array('label'=>'Commentaire','attr'=>array('placeholder'=>'Commentaire')))
            ->add('extPresent',null,array('label'=>'Présent sur la manif'))
            ->add('extBoisson','integer',array('label'=>'Tickets Boisson','attr'=>array('class'=>'input-mini')))
            ->add('extBouffe','integer',array('label'=>'Tickets Bouffe','attr'=>array('class'=>'input-mini')))
            ->add('extCatering',null,array('label'=>'Accès bouffe artiste'))
            ->add('lieu',null,array('label'=>'Lieu exact'))
            ->add('locX','hidden')
            ->add('locY','hidden')
            ->add('public',null,array('label'=>'Publier sur le site / plaquette'))
            ->add('description',null,array('label'=>'Description de l\'animation'))
            ->add('animPhare',null,array('label'=>'Anim Phare'))
            ->add('animGosses',null,array('label'=>'Anim pour les gosses'))
            ->add('besoinSecu',null,array('label'=>'Dispositif de sécurité particulier'))
            ->add('besoinPass',null,array('label'=>'Besoin de pass'))
            ->add('detailSecu',null,array('label'=>'Détails','attr'=>array('placeholder'=>'Description du dispositif de sécurité à mettre en place (AS, barrièrage, ERP spécial, autorisation spéciale, types de risques, etc.)')))
            ->add('besoinSigna',null,array('label'=>'Besoin de signalétique'))
            ->add('detailSigna',null,array('label'=>'Desctiption du dispositif','attr'=>array('placeholder'=>'Texte à mettre sur les panneaux, nombre, etc')))
            ->add('horaires','hidden')
            ->add('materiel','hidden')
            ->add('lieuDepotLog', 'choice',array('label'=>'Lieu de dépôt du matériel','choices'=>Animation::$lieuxDepotLog, 'required'=>false,'disabled'=>!$this->admin))
            ;
            $builder->add('commentaire','textarea',array('label'=>'Ajouter un commentaire','required'=>false));
        }
    }
    

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'assomaker_animbundle_animationtype';
    }
}
