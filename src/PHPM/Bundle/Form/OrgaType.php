<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrgaType extends AbstractType
{
    protected $admin;
    protected $config;
    function __construct($admin,$config){
        
            $this->config =$config;
            $this->admin =$admin;
    }
    
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $libellesPermis =  json_decode($this->config->getValue('manifestation_permis_libelles'),true);
        
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
            ->add('dateDeNaissance', 'birthday', array(
                    'label'=>'Date de naissance',
                    'years'=>$years,
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'birthdaydp'),
                    'format' => 'yyyy-MM-dd'))
            ->add('datePermis', null, array(
                            'label'=>'Date de permis',
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd'))
            ->add('departement','choice',array('label'=>'Département INSA', 'choices'=>array(
                    'PC'=>'PC','GMC'=>'GMC','GMD'=>'GMD', 'GMPP'=>'GMPP', 'IF'=>'IF', 'SGM'=>'SGM',
                    'GI'=>'GI', 'GE'=>'GE', 'TC'=>'TC', 'GCU'=>'GCU', 'BIM'=>'BIM', 'BIOCH'=>'BIOCH', 'GEN'=>'GEN', 'Autre'=>'Autre' 
                    )))
            ->add('equipe',null,array('label'=>'Équipe'))
            ->add('commentaire')
            
    	    ;
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
