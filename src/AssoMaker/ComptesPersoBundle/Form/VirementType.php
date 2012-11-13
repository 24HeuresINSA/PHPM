<?php

namespace AssoMaker\ComptesPersoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Collection;

class VirementType extends AbstractType
{
    protected $config;
    
    function __construct($config,$userId){
            $this->config =$config;
            $this->userId = $userId;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    	$userId= $this->userId;
        $builder 
				->add('montant', 'money', array())
				->add('destinataire', 'entity',
						array('label' => 'Bénéficiaire',
								'class' => 'AssoMakerBaseBundle:Orga',
								'query_builder' => function (
										OrgaRepository $or) use ($userId) {
									return $or->findAllComptesPersoUsersExcept($userId);
								}))
				->add('raison', 'text', array('label'=>'Raison'))
								;
		
    
    	
    }

    public function getName()
    {
        return 'assomaker_comptes_perso_bundle_virement';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $collectionConstraint = new Collection(
				array(
				        'raison'=>array(),
						'montant' => new Range(
								array('min' => 0.01,
										'minMessage' => "Veuillez entrer un montant supérieur ou égal à 0,01€ !")),
						'destinataire' => array()));
    
        $resolver->setDefaults(array(
                'validation_constraint' => $collectionConstraint
        ));
    }
    
    
}
