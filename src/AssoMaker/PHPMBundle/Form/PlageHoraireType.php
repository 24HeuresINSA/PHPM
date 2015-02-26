<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlageHoraireType extends AbstractType
{
	
	protected $config;
	
	function __construct($config)
	{
	
		$this->config = $config;
	
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $choixDurees= array();
        
        for ($i=1;$i<=12;$i++){
            $s= 900*$i;
            $choixDurees[$s]=gmdate("H\hi", $s);
        }
        
        $choixRC= array();
        
        for ($i=0;$i<=2;$i++){
            $s= 900*$i;
            $choixRC[$s]=gmdate("H\hi", $s);
        }
        
        $builder
            ->add('debut','datetime',array(
                    'label'=>'Début',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'debutdp'),
                    'format'=>'yyyy-MM-dd HH:mm:ss'))
            ->add('fin','datetime',array(
                    'label'=>'Fin',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'findp'),
                    'format'=>'yyyy-MM-dd HH:mm:ss'))
            ->add('creneauUnique', null, array('label'=>'Créneau Unique (pas de découpage)'))
            ->add('dureeCreneau', 'choice', array('label'=>'Durée d\'un créneau', 'choices'=>$choixDurees))
            ->add('recoupementCreneau', 'choice', array('label'=>'Recoupement entre deux créneaux consécutifs (en sec.)', 'choices' => $choixRC ))
			->add('besoinsOrga','collection', array('type' => new BesoinOrgaType($this->config),
													'allow_add' => true,
													'allow_delete' => true,
									                'by_reference' => false,
												    'label'  => ' ',
									                'options'  => array('label' => ' ')
        										));
    }
    
    public function getDefaultOptions(array $options) {
    	return array(
    			'data_class' => 'AssoMaker\PHPMBundle\Entity\PlageHoraire',
    	);
    }

    public function getName() {
        return 'assomaker_phpm_bundle_plagehorairetype';
    }
	
}
