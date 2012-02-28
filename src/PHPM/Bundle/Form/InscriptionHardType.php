<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\DisponibiliteInscriptionType;

class InscriptionHardType extends AbstractType
{
    
    protected $admin;
    protected $em;
    protected $orga;

    function __construct($admin,$em,$orga){
        
            $this->em = $em;
            $this->admin =$admin;
            $this->orga = $orga;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
       $entities = $this->em->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d ORDER BY d.debut")->getResult();
       $prevday= -1;
       $child = 0;
       foreach ($entities as $key =>$e)
        {
            if($e->getDebut()->format('z')!=$prevday ){
                $label =\IntlDateFormatter::create("fr_FR", null, null,null,null,'EEEE d MMMM')->format($e->getDebut());
                $c=$builder->create($e->getDebut()->format('z'),'form',array('label'=>$label, 'required'=>false));
                $builder->add($c);
                           }
            
            
            $checked = $this->orga->getDisponibilitesInscription()->contains($e);
            $read_only = $checked  && !$this->admin;
            $options = array(
                    'data'=>$checked,
                   
                    'read_only'=>$read_only,
                    'label'     => $e->__toString(),
                    'required'  => false,
                    'attr'=> array('class'=>'q')
                    );
            
            
                
            $prevday = $e->getDebut()->format('z');
            
            $c->add((string)$key, 'checkbox',$options);
            
            
        }
        $builder->add('end',new FormType(),array('label'=>" ", 'required'=>false, 'attr'=> array('class'=>'clear')));
	    
    }

    public function getName()
    {
        return 'phpm_bundle_inscriptionhardtype';
    }
}
