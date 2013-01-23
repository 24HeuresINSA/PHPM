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
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
            ->add('orgaManif','entity',array(
				'class' => 'AssoMakerBaseBundle:Orga',
				'query_builder' => function(OrgaRepository $or)use($minConfianceResp){return $or->findAllWithConfianceValueMin($minConfianceResp);}))
            
            ->add('equipe')
        ;
        
        if(!$this->create){
            $builder            
            ->add('extNom',null,array('label'=>'Nom'))
            ->add('extType', 'choice',array('choices'=>Animation::$extTypes))
            ->add('extTelephone',null,array('label'=>'Téléphone'))
            ->add('extEmail',null,array('label'=>'Email'))
            ->add('extCommentaire',null,array('label'=>'Commentaire'))
            ->add('extPresent',null,array('label'=>'Présent sur la manif'))
            ->add('extTboisson')
            ->add('extTbouffe')
            ->add('extCatering')
            ->add('lieu')
            ->add('locX')
            ->add('locY')
            ->add('public')
            ->add('description')
            ->add('animPhare')
            ->add('animGosses')
            ->add('lieuPublic')
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
