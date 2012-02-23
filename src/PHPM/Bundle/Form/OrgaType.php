<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrgaType extends AbstractType
{
    protected $admin;
    function __construct($admin=false){
        
        
            $this->admin =$admin;
    }
    
    
    public function buildForm(FormBuilder $builder, array $options)
    {
             
    	$currentYear = date('Y');
    	$years = array(); 	
    	
    	for ($i=($currentYear-27);$i<=($currentYear-16);$i++){
    		array_push($years, $i);
    	
    	}
    	$builder
            ->add('nom')
            ->add('prenom')
            ->add('surnom')
            ->add('telephone')
            ->add('email')
            ->add('dateDeNaissance', 'birthday', array('label'=>'Date de naissance','years'=>$years))
            ->add('departement')
            ->add('commentaire')
            ->add('permis')
			->add('confiance', null,array('read_only'=>!$this->admin))
			->add('statut', null,array('read_only'=>!$this->admin))
			->add('isAdmin',null,array('label'=>'Administrateur','read_only'=>!$this->admin));
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_orgatype';
    }
    
    
}
