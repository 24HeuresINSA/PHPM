<?php

namespace PHPM\Bundle\Form\DisponibiliteInscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Form\EventListener\ConfigFormSubscriber;
use PHPM\Bundle\Form\DisponibiliteInscription\DisponibiliteInscriptionType;


class DisponibiliteInscriptionListType extends AbstractType
{
    protected $admin;
    protected $config;
    function __construct($admin, $config)
    {

        $this->config = $config;
        $this->admin = $admin;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        $choixDecalage= array();
        
        for ($i=1;$i<=12;$i++){
            $s= 3600*$i;
            $choixDecalage[$s]="$i h";
        }
                
        for ($i=1;$i<=7;$i++){
        	$s= 24*3600*$i;
        	$choixDecalage[$s]="$i j";
        }
    	
        $builder
                ->add('disponibiliteInscriptionItems', 'entity',
                        array(	'class' => 'PHPMBundle:DisponibiliteInscription',
                        		'expanded'=>true,
        						'multiple'=>true,
                                'by_reference' => false,
                                'label' => 'Di'
                				))
        		->add('decalage', 'choice', array('label'=>'Avec un décalage de', 'choices' => $choixDecalage , 'empty_value' => 'Dupliquer et décaler', 'required'=> false ))
        		->add('categorie','text',array( 'required'=> false, 'attr'=>array('placeholder'=>'Changer la catégorie')))
        		->add('statut','choice',array('label'=>'Statut', 'empty_value' => 'Changer le statut', 'required'=> false, 'choices'=>array(
        				'0'=>'Verrouillé', '1'=>'Cochable Uniquement', '2'=>'Cochable/Décochable'
        				)))
        ;
     

        $form = $builder->getForm();

    }

    public function getName()
    {
        return 'phpm_bundle_disponibiliteinscriptionlisttype';
    }

}
