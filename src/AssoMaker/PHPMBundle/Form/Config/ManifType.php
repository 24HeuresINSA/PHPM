<?php

namespace AssoMaker\PHPMBundle\Form\Config;
use AssoMaker\PHPMBundle\Form\RegistrationTokenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\PHPMBundle\Form\EventListener\ConfigFormSubscriber;
use AssoMaker\PHPMBundle\Form\Config\ConfigType;
use AssoMaker\PHPMBundle\Form\LieuType;
use AssoMaker\PHPMBundle\Form\EquipeType;
use AssoMaker\PHPMBundle\Form\ConfianceType;
use AssoMaker\PHPMBundle\Form\MaterielType;
class ManifType extends AbstractType
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

        $builder
                ->add('configItems', 'collection',
                        array(	'type' => new ConfigType(),
                        		'allow_add' => true,
                                'allow_delete' => true,
                                'by_reference' => false,
                                'label' => 'Clés de Configuration',
                				'options'  => array('error_bubbling'=>true,'data_class' => 'AssoMaker\PHPMBundle\Entity\Config')));

        $builder
                ->add('equipeItems', 'collection',
                        array('type' => new EquipeType(), 'allow_add' => true,
                                'allow_delete' => true,
                                'by_reference' => false,
                                'label' => 'Équipes',
                				'options'  => array('error_bubbling'=>true,'data_class'=>'AssoMaker\BaseBundle\Entity\Equipe')));

        $builder
            ->add('registrationTokenItems', 'collection',
                array('type' => new RegistrationTokenType(), 'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => "Jetons d'inscription",
                    'options'  => array('error_bubbling'=>true,'data_class'=>'AssoMaker\BaseBundle\Entity\RegistrationToken')));

        $builder
                ->add('confianceItems', 'collection',
                        array('type' => new ConfianceType(),
                                'allow_add' => true, 'allow_delete' => true,
                                'by_reference' => false,
                                'label' => 'Niveaux de Confiance',
                				'options'  => array('error_bubbling'=>true,'data_class'=>'AssoMaker\BaseBundle\Entity\Confiance')));

        $builder
                ->add('materielItems', 'collection',
                        array('type' => new MaterielType(),
                                'allow_add' => true, 'allow_delete' => true,
                                'by_reference' => false,
                                'label' => 'Matériel',
                				'options'  => array('error_bubbling'=>true, 'data_class' => 'AssoMaker\PHPMBundle\Entity\Materiel')));

        $form = $builder->getForm();

    }

    public function getName()
    {
        return 'phpm_bundle_maniftype';
    }

}
