<?php

namespace AssoMaker\PHPMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\OrgaRepository;

class BesoinOrgaType extends AbstractType
{
	

	protected $config;

	function __construct($config)
	{
		
		$this->config = $config;
		
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
    	$minConfianceOrgaHint = $this->config->getValue('manifestation_orga_plagehoraireconfiancemin');
    	
        $builder
        	->add('orgaHint', null, array('label'=>'Orga précis nécessaire', 'empty_value' => 'N\'importe qui', 'required'=> false,
        			'class' => 'AssoMakerBaseBundle:Orga',
        			'query_builder' => function(OrgaRepository $or)use($minConfianceOrgaHint){
        			return $or->findAllWithConfianceValueMin($minConfianceOrgaHint);
        			}
        			))
        	->add('equipe', null, array('label'=>'Équipe', 'empty_value' => '(N\'importe quelle équipe)', 'required'=> false))        	
            ->add('nbOrgasNecessaires', null, array('label' => 'Orgas nécessaires', 'attr' => array('min' => 1)));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        	'data_class' => 'AssoMaker\PHPMBundle\Entity\BesoinOrga'
        );
    }
    
    public function getName()
    {
        return 'phpm_bundle_besoinorgatype';
    }
}
