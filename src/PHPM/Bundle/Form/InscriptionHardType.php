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
    protected $user;

    function __construct($admin,$em,$user){
        
            $this->em = $em;
            $this->admin =$admin;
            $this->user = $user;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
       $entities = $this->em->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d ")->getResult();
       $prevday= -1;
       $child = 0;
       foreach ($entities as $key =>$e)
        {
            if($e->getDebut()->format('z')!=$prevday ){
                $label =\IntlDateFormatter::create("fr_FR", null, null,null,null,'EEEE d MMMM')->format($e->getDebut());
                $c=$builder->create($e->getDebut()->format('z'),'form',array('label'=>$label, 'required'=>false));
                $builder->add($c);
                           }
            
            
            $checked = $this->user->getDisponibilitesInscription()->contains($e);
            $options = array(
                    'data'=>$checked,
                   
                    'read_only'=>$checked,
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
