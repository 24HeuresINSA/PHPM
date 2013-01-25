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
    
    function __construct($admin,$config,$create){
        $this->admin =$admin;
        $this->config=$config;
        $this->create=$create;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $minConfianceResp = $this->config->getValue('manifestation_orga_responsableconfiancemin');
        
        
        $builder
            ->add('nom',null,array('label'=>'Nom'))
            ->add('responsable','entity',array(
                'label'=>'Responsable',
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
            ->add('orgaManif','entity',array(
                'label'=>'Orga sur la manif',
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
                
            ->add('equipe')
        ;
        
        if(!$this->create){
            $builder            
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
            ->add('horaires','hidden')
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\AnimBundle\Entity\Animation'
        ));
    }

    public function getName()
    {
        return 'assomaker_animbundle_animationtype';
    }
}
