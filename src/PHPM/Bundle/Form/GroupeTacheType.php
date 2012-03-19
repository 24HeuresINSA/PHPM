<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Entity\AnimLinker;

class GroupeTacheType extends AbstractType
{
    
    protected $config;
    protected $admin;
    function __construct($admin,$config){
    
        $this->config =$config;
        $this->admin =$admin;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $al = new AnimLinker($this->config->getValue('animations.db.path'));
        
        
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
