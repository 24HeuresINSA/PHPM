<?php

namespace AssoMaker\ComptesPersoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Min;
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
									return $or->findAllUsersExcept($userId);
								}))
				->add('raison', null, array('label'=>'Raison'))
								;
		$builder->add('confirm', 'hidden');
		$builder->setData(array('confirm' => '0'));
    
    	
    }

    public function getName()
    {
        return 'assomaker_comptes_perso_bundle_virement';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $collectionConstraint = new Collection(
				array(
				        'confirm'=>array(),
				        'raison'=>array(),
						'montant' => new Min(
								array('limit' => 0,
										'message' => "Veuillez entrer un montant positif")),
						'destinataire' => array()));
    
        $resolver->setDefaults(array(
                'validation_constraint' => $collectionConstraint
        ));
    }
    
    
}
