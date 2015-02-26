<?php

namespace AssoMaker\PHPMBundle\Form;

use AssoMaker\PHPMBundle\Entity\LimiteInscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DisponibiliteType extends AbstractType
{

	public $orgaOptions = array();
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
            ->add('debut',null,array(
                    'label'=>'Début',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'debutdp')))
            ->add('fin',null,array(
                    'label'=>'Fin',
                    'widget' => 'single_text',
                    'attr'=>array('class'=>'findp')))
			->add('orga',null,$this->orgaOptions);
            
        
		
		
    }

    public function getName()
    {
        return 'phpm_bundle_disponibilitetype';
    }
	
	public function disableOrga()
	{
		$this->orgaOptions = array("read_only" => TRUE);
	}
	
}
