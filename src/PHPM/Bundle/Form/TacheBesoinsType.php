<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class TacheBesoinsType extends AbstractType
{
    

    protected $admin;
    protected $em;
    protected $config;
    protected $tache;
    function __construct($admin,$em,$config,$tache){

            $this->em = $em;
            $this->config =$config;
            $this->admin =$admin;
            $this->tache= $tache;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $libellesPermis =  json_decode($this->config->getValue('manifestation_permis_libelles'),true);
        
             
        
        
        $entities = $this->em->createQuery("SELECT m FROM PHPMBundle:Materiel m ORDER BY m.categorie")->getResult();
        
        $prevcat= -1;
        
        foreach ($entities as $key =>$e)
        {
            if($e->getCategorie()!=$prevcat ){
                $label =$e->getCategorie();
                $c=$builder->create($e->getCategorie(),'collection',array('label'=>$label, 'required'=>false, 'allow_add' => true));
                $builder->add($c);
//                 $c->add('end',new FormType(),array('label'=>" ", 'required'=>false, 'attr'=> array('class'=>'clear')));
            }
        
        
            
        
        
        
            $prevcat = $e->getCategorie();
            $read_only = false;
        switch ($e->getType()) {
    case 0:
        $widget = 'checkbox';
        $checked = false;

        $options = array(
                'data'=>$checked,
                'read_only'=>$read_only,
                'label'     => $e->__toString(),
                'required'  => false,
                'attr'=> array('class'=>''),
        
        );
        break;
    case 1:
        $widget = 'integer';
        $options = array(
                'data'=>0,
                'read_only'=>$read_only,
                'label'     => $e->__toString(),
                'required'  => false,
                'attr'=> array('class'=>''),
        
        );
        break;
    
}
        
            $c->add((string)$e->getId(), $widget,$options);
        
        
        }
        
        
       
        
    }

    public function getName()
    {
        return 'phpm_bundle_tachetype';
    }
}
