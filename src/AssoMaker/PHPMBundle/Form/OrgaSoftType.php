<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;

class OrgaSoftType extends AbstractType
{

    protected $config;

    function __construct($config){
        
            $this->config =$config;

    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $libellesPermis =  json_decode($this->config->getValue('manifestation_permis_libelles'),true);
        $choixCompetences =  json_decode($this->config->getValue('phpm_competences_orga'),true);
        
    	$currentYear = date('Y');
    	$years = array(); 	
    	
    	for ($i=($currentYear-27);$i<=($currentYear-16);$i++){
    		array_push($years, $i);
    	
    	}
    	$builder
    	    ->add('prenom',null,array('label'=>'Prénom','attr' => array('placeHolder'=>'Prénom')))
            ->add('nom',null,array('label'=>'Nom','attr' => array('placeHolder'=>'Nom')))
            ->add('surnom',null,array('label'=>'Surnom','attr' => array('placeHolder'=>'Surnom')))
            ->add('telephone',null,array('label'=>'Téléphone portable','attr' => array('placeHolder'=>'Numéro de portable')))
            ->add('email',null,array('label'=>'Adresse email','attr' => array('placeHolder'=>'Adresse email')))
            ->add('dateDeNaissance', 'birthday', array(
                    'label'=>'Date de naissance',
            		'required'=>true,
                    'years'=>$years,
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'birthdaydp','placeHolder'=>'Date de Naissance'),
                    'format' => 'yyyy-MM-dd'))
            ->add('datePermis', 'date', array(
                            'label'=>'Date de permis',
            				'required'=>false,
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr'=>array('class'=>'datep','placeHolder'=>'Date d\'obtention du permis B')))
           ->add('anneeEtudes','choice',array(	'label'=>'Année d\'études',
                            			'required'=>false,
                            			'empty_value' => 'Année d\'études',
                            			'choices'=>array(1=>1,2,3,4,5,6,7,8,0=>'Autre')))
            ->add('departement','choice',array(	'label'=>'Département INSA',
            									'required'=>false,
            									'empty_value' => 'Département INSA',
            									'choices'=>array(
                    'PC'=>'PC','GMC'=>'GMC','GMD'=>'GMD', 'GMPP'=>'GMPP', 'IF'=>'IF', 'SGM'=>'SGM',
                    'GI'=>'GI', 'GE'=>'GE', 'TC'=>'TC', 'GCU'=>'GCU', 'BIM'=>'BIM', 'BIOCH'=>'BIOCH', 'GEN'=>'GEN', 'Autre'=>'Autre' 
                    )))
            ->add('groupePC',null,array('label'=>'Nom',
            							'attr'=>array('placeHolder'=>'Groupe (Premier Cycle)')))            
            ->add('commentaire',null,array('label'=>'Commentaires'))
            ->add('amis',null,array('label'=>'Nom des orgas avec qui tu veux bosser'))
            ->add('celibataire','choice',array(	'label'=>'Célib\'?',
            									'required'=>false,
            									'expanded'=>true,
            									'choices'=>array('0'=>'Non','1'=>'Oui'),
            									'attr'=>array('class'=>'inline')))
		    ->add('competences', 'choice', array(
                'choices'=>$choixCompetences,
                'multiple'=>true,
                'expanded'=>true,
		        'label'=>'Compétences'
            ));
    	;
    	    
    }

    public function getName()
    {
        return 'phpm_bundle_orgatype';
    }
    
    
}
