<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrgaType extends AbstractType
{
    protected $admin;
    function __construct($admin){
        
        
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
    	    ->add('prenom',null,array('label'=>'Prénom'))
            ->add('nom',null,array('label'=>'Nom'))
            ->add('surnom',null,array('label'=>'Surnom'))
            ->add('telephone',null,array('label'=>'Téléphone portable'))
            ->add('email',null,array('label'=>'Adresse email'))
            ->add('dateDeNaissance', 'birthday', array('label'=>'Date de naissance','years'=>$years))
            ->add('departement','choice',array('label'=>'Département INSA', 'choices'=>array(
                    'PC','GMC','GMD', 'GMPP', 'IF', 'SGM', 'GI', 'GE', 'TC', 'GCU', 'BIM', 'BIOCH', 'GEN', 'Autre' 
                    )))
            ->add('commentaire')
            ->add('permis','choice',array('label'=>'Titulaire du permis B', 'choices'=>array(
                    0=>'Non', 1=>'Permis de + de deux ans'
                    )));
    	if($this->admin){
        $builder
			->add('confiance', null,array('read_only'=>!$this->admin))
			->add('statut', 'choice',array('choices'=>array('0'=>'Inscrit','1'=>'Validé'), 'read_only'=>!$this->admin))
			->add('isAdmin',null,array('label'=>'Administrateur','read_only'=>!$this->admin));
        
    	}
    }

    public function getName()
    {
        return 'phpm_bundle_orgatype';
    }
    
    
}
