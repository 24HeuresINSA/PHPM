<?php

namespace AssoMaker\PHPMBundle\Form\DisponibiliteInscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\PHPMBundle\Form\EventListener\ConfigFormSubscriber;
use AssoMaker\PHPMBundle\Form\DisponibiliteInscription\DisponibiliteInscriptionType;


class DisponibiliteInscriptionListType extends AbstractType
{
    protected $admin;
    protected $config;
    function __construct($admin, $config)
    {

        $this->config = $config;
        $this->admin = $admin;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
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
                        array(	'class' => 'AssoMakerPHPMBundle:DisponibiliteInscription',
                        		'expanded'=>true,
        						'multiple'=>true,
                                'by_reference' => false,
                                'label' => 'Di'
                				))
        		->add('decalage', 'choice', array('label'=>'Avec un décalage de', 'choices' => $choixDecalage , 'empty_value' => 'Dupliquer et décaler', 'required'=> false ))
        		->add('statut','choice',array('label'=>'Statut', 'empty_value' => 'Changer le statut', 'required'=> false, 'choices'=>array(
        				'0'=>'Verrouillé', '1'=>'Cochable Uniquement', '2'=>'Cochable/Décochable'
        				)))
        		->add('mission', 'entity',
        						array(	'class' => 'AssoMakerPHPMBundle:Mission',
        								'label' => 'Groupe',
        								'empty_value' => 'Changer la mission',
        								'required'=> false
        						))
        		->add('pointsCharisme',null,
        						array( 'label' => 'Points de Charisme',
        								'required'=> false,
        								'attr' => array('placeHolder'=>'Changer les points de charisme')
        						))
		        ->add('confiance', 'entity',
		        array(	'class' => 'AssoMakerBaseBundle:Confiance',
		                								'label' => 'Affecter les orgas',
		                								'empty_value' => 'Affecter les orgas',
		                								'required'=> false
		        ))
		        ->add('confiance2', 'entity',
		        array(	'class' => 'AssoMakerBaseBundle:Confiance',
        		                						'label' => 'Désaffecter les orgas',
        		                						'empty_value' => 'Désaffecter les orgas',
        		                						'required'=> false
		        ))
        ;
     

        $form = $builder->getForm();

    }

    public function getName()
    {
        return 'phpm_bundle_disponibiliteinscriptionlisttype';
    }

}
