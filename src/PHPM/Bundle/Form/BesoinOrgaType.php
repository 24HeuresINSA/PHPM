<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use PHPM\Bundle\Entity\OrgaRepository;

class BesoinOrgaType extends AbstractType
{
	

	protected $config;

	function __construct($config)
	{
		
		$this->config = $config;
		
	}
	
    public function buildForm(FormBuilder $builder, array $options)
    {
    	
    	$minConfianceOrgaHint = $this->config->getValue('manifestation_orga_plagehoraireconfiancemin');
    	
        $builder
        	->add('orgaHint', null, array('label'=>'Orga précis nécessaire', 'empty_value' => 'N\'importe qui', 'required'=> false,
        			'class' => 'PHPMBundle:Orga',
        			'query_builder' => function(OrgaRepository $or)use($minConfianceOrgaHint){
        			return $or->findAllWithConfianceValueMin($minConfianceOrgaHint);
        			}
        			))
        	->add('equipe', null, array('label'=>'Équipe', 'empty_value' => '(N'importe quelle équipe)', 'required'=> false))        	
            ->add('nbOrgasNecessaires', null, array('label' => 'Orgas nécessaires', 'attr' => array('min' => 1)));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        	'data_class' => 'PHPM\Bundle\Entity\BesoinOrga'
        );
    }
    
    public function getName()
    {
        return 'phpm_bundle_besoinorgatype';
    }
}
