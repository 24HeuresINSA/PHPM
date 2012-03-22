<?php

namespace PHPM\Bundle\Form;

use PHPM\Bundle\Entity\BesoinMateriel;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use PHPM\Bundle\Form\BesoinMaterielType;
use PHPM\Bundle\Entity\Tache;

class TacheType extends AbstractType
{
    

    protected $admin;
    protected $em;
    protected $config;
    protected $tache;
    protected $rOnly;
    function __construct($admin,$em,$config,$rOnly){

            $this->em = $em;
            $this->config =$config;
            $this->admin =$admin;
            $this->rOnly = $rOnly;
            
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        
        
        
        $libellesPermis =  json_decode($this->config->getValue('manifestation_permis_libelles'),true);
        
        
        $i=$builder->create('entity','form',array('label'=>" ", 'required'=>false, 'data_class' => 'PHPM\Bundle\Entity\Tache',
                'error_bubbling'=>true, "read_only"=>$this->rOnly));
        $i->add('groupeTache', 'entity', array(
    'class' => 'PHPMBundle:GroupeTache'
    
            
    
))
        ->add('nom')
        ->add('consignes')
        ->add('lieu')
        ->add('confiance')
        ->add('consignes')
        ->add('permisNecessaire','choice',array('label'=>'Permis Nécessaire', 'choices'=>$libellesPermis))
        ->add('responsable')
        ->add('materielSupplementaire');
       
        
        if($this->admin){
        $i->add('statut', 'choice',array('choices'=>array('0'=>'En rédaction','1'=>'Soumise à validation','2'=>'Validée')));
        }
        
        $i->add('plagesHoraire','collection',array('type' => new PlageHoraireType(),'allow_add' => true,'allow_delete' => true,
                'by_reference' => false,
                'options'  => array( 'label'  => " "),
                'property_path'=>'plagesHoraire',
                 "read_only"=>$this->rOnly
        ));
        
        $m=$builder->create('Materiel','form',array('label'=>"Matériel requis", 'required'=>false, "read_only"=>$this->rOnly));
        $builder->add($i)
        ->add($m)
        ->add('commentaire','textarea', array('required'=>false,
                 "read_only"=>$this->rOnly, 'attr'=>array('placeholder'=>'Ajouter un commentaire')));

        
        
        
        $entities = $this->em->createQuery("SELECT m FROM PHPMBundle:Materiel m ORDER BY m.categorie")->getResult();
        
        
        
        $prevcat= -1;
        
        foreach ($entities as $key =>$e)
        {
            if($e->getCategorie()!=$prevcat ){
                $label =$e->getCategorie();
                $c=$builder->create($e->getCategorie(),'form',array('label'=>$label, 'required'=>false));
                $m->add($c);
                //                 $c->add('end',new FormType(),array('label'=>" ", 'required'=>false, 'attr'=> array('class'=>'clear')));
            }
        
        
        
        
        
        
            $prevcat = $e->getCategorie();
            $read_only = false;
            switch ($e->getType()) {
                case 0:
                    $widget = 'checkbox';
                    $checked = false;
        
                    $options = array(
                            'read_only'=>$read_only,
                            'label'     => $e->__toString(),
                            'required'  => false,
                            'attr'=> array('class'=>'')
        
                    );
                    break;
                case 1:
                    $widget = 'integer';
                    $options = array(
                            'read_only'=>$read_only,
                            'label'     => $e->__toString(),
                            'required'  => false,
                            'attr'=> array('class'=>'')
        
                    );
                    break;
        
            }
        
            $c->add((string)$e->getId(),$widget,$options);
        
        }
        
        
        
    }

    public function getName()
    {
        return 'phpm_bundle_tachetype';
    }
}
