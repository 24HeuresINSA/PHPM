<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\PHPMBundle\Entity\AnimLinker;

class GroupeTacheType extends AbstractType
{
    
    protected $config;
    protected $admin;
    function __construct($admin,$config){
    
        $this->config =$config;
        $this->admin =$admin;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $al = new AnimLinker($this->config->getValue('animations_db_path'),$this->config->getValue('manifestation_edition'));
        
        
        $builder
            ->add('nom')
            ->add('responsable')
            ->add('equipe')
            ->add('lieu')
            ->add('animLiee','choice',array('label'=>'Animation Liée', 'choices'   => $al->getAnimsArray(),'required'  => false));
        
    }

    public function getName()
    {
        return 'phpm_bundle_groupetachetype';
    }
}
