<?php

namespace AssoMaker\BaseBundle\Form;

use AssoMaker\BaseBundle\Extension\ConfigExtension;
use AssoMaker\BaseBundle\Entity\Orga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AssoMaker\BaseBundle\Entity\EquipeRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrgaUserType extends AbstractType {

    private $securityContext;
    private $config;

    public function __construct(SecurityContext $securityContext, ConfigExtension $config) {
        $this->securityContext = $securityContext;
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $orga = ($options['data']);

        $libellesPermis = json_decode($this->config->getValue('manifestation_permis_libelles'), true);
        $choixCompetences = json_decode($this->config->getValue('phpm_competences_orga'), true);
        $currentYear = date('Y');
        $years = array();

        for ($i = ($currentYear - 27); $i <= ($currentYear - 16); $i++) {
            array_push($years, $i);
        }
        $builder
                ->add('prenom', null, array('label' => 'Prénom'))
                ->add('nom', null, array('label' => 'Nom'))
                ->add('surnom', null, array('label' => 'Surnom'))
                ->add('telephone', null, array('label' => 'Téléphone portable'))
                ->add('email', null, array('label' => 'Adresse email', "disabled" => ($orga->getEmail() != null) && !$this->securityContext->isGranted('ROLE_ADMIN')))
                ->add('dateDeNaissance', 'birthday', array(
                    'input' => 'datetime',
                    'label' => 'Date de naissance',
                    'years' => $years,
                    'widget' => 'single_text',
                    'attr' => array('class' => 'birthdaydp')
                ))
                ->add('datePermis', 'date', array(
                    'input' => 'datetime',
                    'label' => 'Date de permis',
                    'widget' => 'single_text',
                    'required' => false,
                    'attr' => array('class' => 'datep')))
                ->add('anneeEtudes', 'choice', array('label' => 'Année d\'études',
                    'required' => false,
                    'choices' => array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 0 => 'Autre')))
                ->add('departement', 'choice', array('label' => 'Département INSA', 'required' => false, 'choices' => array(
                        'PC' => 'PC', 'GMC' => 'GMC', 'GMD' => 'GMD', 'GMPP' => 'GMPP', 'IF' => 'IF', 'SGM' => 'SGM',
                        'GI' => 'GI', 'GE' => 'GE', 'TC' => 'TC', 'GCU' => 'GCU', 'BIM' => 'BIM', 'BIOCH' => 'BIOCH', 'GEN' => 'GEN', 'Autre' => 'Autre'
            )))
                ->add('groupePC', 'integer', array('label' => 'Groupe (Premier Cycle Uniquement)',
                    'attr' => array('placeHolder' => '00'), 'required' => false))
                ->add('commentaire')
                ->add('celibataire', 'choice', array('label' => 'Célib\'?',
                    'required' => false,
                    'choices' => array('0' => 'Non', '1' => 'Oui'),
                    'attr' => array('class' => 'inline')))
                ->add('profilePicture', 'file', array('label' => 'Photo (800x600px)',
                    'required' => false,
                ))
                ->add('fichierPermis', 'file', array('label' => 'Scan du Permis (PDF)',
                    'required' => false,
                ))
                ->add('amis')
                ->add('publicEmail', null, array('label' => 'Adresse email publique', 'required' => false))
                ->add('role', null, array('label' => 'Rôle'))
                ->add('competences', 'choice', array(
                    'choices' => $choixCompetences,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Compétences'
        ));

//TODO:Change
        if ($options['confianceCode'] != null) {

            $code = $options['confianceCode'];

            $builder->add('equipe', 'entity', array('label' => 'Équipe',
                'class' => 'AssoMakerBaseBundle:Equipe',
                'query_builder' => function(EquipeRepository $er)use($code) {
                    return $er->findAllWithConfianceCode($code);
                }));
        }



        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder
                    ->add('statut', 'choice', array('choices' => array('0' => 'Inscrit', '1' => 'Validé', '2' => 'Complétement affecté')))
                    ->add('equipe', 'entity', array('label' => 'Équipe', 'class' => 'AssoMakerBaseBundle:Equipe'))
            ;
        }
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('membreBureau', null, array('label' => 'Membre du Bureau', 'required' => false));
            $builder->add('privileges', 'choice', array('choices' => Orga::$privilegesTypes));
        }
    }

    public function getName() {
        return 'assomaker_base_bundle_orgatype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'confianceCode' => null
        ));
    }

}

