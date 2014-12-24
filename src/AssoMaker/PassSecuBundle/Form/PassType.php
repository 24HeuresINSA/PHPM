<?php

namespace AssoMaker\PassSecuBundle\Form;

use AssoMaker\PassSecuBundle\Entity\Pass;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PassType extends AbstractType
{
    
    protected $guest;
    protected $create;
    protected $config;
    
    function __construct($guest,$config,$create,$disabled = false){
        $this->guest =$guest;
        $this->config=$config;
        $this->create=$create;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        

        
        if(!$this->guest){
            $builder
                
                
                ->add('maxPersonnes',null,array('label'=>'Nombre maximum de passes pour cette entité'))
            ;

            
        }
        
        if(!$this->create){
            $builder
        
            ->add('message',null,array('label'=>'Votre message sera transmis à l\'équipe sécurité en même temps que la présente demande.','required'=>false))
            ->add('pointsPassage','hidden',array('disabled'=>$this->guest))
            ->add('personnes','hidden')
            ->add('infosSupplementaires',null,array('label'=>'Informations supplémentaires à faire figurer sur le pass','required'=>false,'disabled'=>$this->guest))
                    
            ;
        }
        $builder
        ->add('entite',null,array('label'=>'Nom de l\'entité','required'=>true,'disabled'=>$this->guest))
        ->add('validiteDebut', 'choice',array('label'=>'Laissez-passer valable de','choices'=>Pass::$validiteChoices,'disabled'=>$this->guest))
        ->add('validiteFin','choice',array('label'=>'à','choices'=>Pass::$validiteChoices,'disabled'=>$this->guest))
        ->add('emailDemandeur',null,array('label'=>'Adresse email de l\'entité'))
        ->add('telephoneDemandeur',null,array('label'=>'Numéro de téléphone de l\'entité'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssoMaker\PassSecuBundle\Entity\Pass'
        ));
    }

    public function getName()
    {
        return 'assomaker_passsecubundle_passtype';
    }
}
