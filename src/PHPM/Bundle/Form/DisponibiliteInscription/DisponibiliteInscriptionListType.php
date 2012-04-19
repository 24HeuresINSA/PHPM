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
        		->add('decalage', 'choice', array('label'=>'Avec un décalage de', 'choices' => $choixDecalage ));

//         $builder
//                 ->add('lieuItems', 'collection',
//                         array('type' => new LieuType(), 'allow_add' => true,
//                                 'allow_delete' => true,
//                                 'by_reference' => false,
//                                 'label' => 'Lieux'));

      

        $form = $builder->getForm();

    }

    public function getName()
    {
        return 'phpm_bundle_disponibiliteinscriptionlisttype';
    }

}
