<?php

namespace PHPM\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TacheType extends AbstractType
{
    

    protected $em;
    function __construct($em){
    
        $this->em =$em;

    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $libellesPermis =  json_decode($this->em->getRepository('PHPMBundle:Config')->findOneByField('manifestation_permis_libelles')->getValue(),true);
        $builder
            ->add('nom')
            ->add('consignes')
            ->add('lieu')
            ->add('categorie')
            ->add('confiance')
			->add('consignes')
			->add('materielNecessaire')
			->add('permisNecessaire','choice',array('label'=>'Permis Nécessaire', 'choices'=>$libellesPermis))
			->add('ageNecessaire')
			->add('responsable')
        ;
    }

    public function getName()
    {
        return 'phpm_bundle_tachetype';
    }
}
